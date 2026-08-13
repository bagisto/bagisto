<?php

namespace Webkul\DataTransfer\Helpers\Importers\Concerns;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Jobs\Import\DownloadImages;
use Webkul\DataTransfer\Repositories\ImportRepository;

/**
 * Fetches the URLs in an import's `images` column before any row is written, and
 * records them in a manifest keyed by URL that prepareImages() reads instead of
 * going to the network.
 *
 * Each URL is fetched once however many rows name it, and one that fails is
 * recorded rather than raised, so the rest of the import proceeds without it.
 */
trait DownloadsImages
{
    /**
     * Images fetched per wave on the browser-driven path, and per job on the
     * queued one.
     */
    public const IMAGE_DOWNLOAD_CHUNK_SIZE = 25;

    /**
     * Largest image accepted, in bytes. A response bigger than this is abandoned
     * rather than read into memory.
     */
    public const MAX_IMAGE_BYTES = 10485760;

    /**
     * Seconds to wait on one image before giving up on it.
     */
    public const IMAGE_REQUEST_TIMEOUT = 15;

    /*
    |--------------------------------------------------------------------------
    | Manifest
    |--------------------------------------------------------------------------
    */

    /**
     * Every remote image this import references, as `url => entry`. Built once,
     * from the validated batches, and updated in place as images are fetched.
     */
    public function buildImageManifest(): array
    {
        $existing = $this->readImageManifest();

        if (! is_null($existing)) {
            return $existing;
        }

        $manifest = [];

        foreach ($this->allImportRows() as $rowData) {
            foreach ($this->remoteImageUrls($rowData) as $url) {
                if (isset($manifest[$url])) {
                    continue;
                }

                $manifest[$url] = ['status' => 'pending'];
            }
        }

        $this->writeImageManifest($manifest);

        return $manifest;
    }

    /*
    |--------------------------------------------------------------------------
    | Browser-driven waves
    |--------------------------------------------------------------------------
    */

    /**
     * Fetch the next wave of pending images and return progress. Called
     * repeatedly by the browser until `done`.
     */
    public function downloadImagesBatch(): array
    {
        $manifest = $this->buildImageManifest();

        $pending = array_keys(array_filter(
            $manifest,
            fn ($entry) => ($entry['status'] ?? 'pending') === 'pending'
        ));

        if (empty($pending)) {
            return $this->imageProgress($manifest);
        }

        foreach (array_slice($pending, 0, self::IMAGE_DOWNLOAD_CHUNK_SIZE) as $url) {
            $manifest[$url] = $this->fetchImage($url);
        }

        $this->writeImageManifest($manifest);

        return $this->imageProgress($manifest);
    }

    /*
    |--------------------------------------------------------------------------
    | Queued, parallel download
    |--------------------------------------------------------------------------
    */

    /**
     * Dispatch the image download as a parallel queue batch and return how many
     * images there are. Zero means there was nothing to fetch.
     */
    public function queueImageDownload(): int
    {
        $manifest = $this->buildImageManifest();

        $pending = array_keys(array_filter(
            $manifest,
            fn ($entry) => ($entry['status'] ?? 'pending') === 'pending'
        ));

        $total = count($manifest);

        if (empty($pending)) {
            return 0;
        }

        Storage::disk('private')->deleteDirectory($this->imageFragmentDir());

        $importId = $this->import->id;

        $jobs = [];

        foreach (array_chunk($pending, self::IMAGE_DOWNLOAD_CHUNK_SIZE) as $index => $urls) {
            $jobs[] = new DownloadImages($importId, $urls, $index);
        }

        $batch = Bus::batch($jobs)
            ->name('import-images-'.$importId)
            ->allowFailures()
            ->finally(function () use ($importId) {
                $import = app(ImportRepository::class)->find($importId);

                if (! $import) {
                    return;
                }

                app(Import::class)
                    ->setImport($import)
                    ->getTypeImporter()
                    ->mergeImageFragments();
            })
            ->dispatch();

        Storage::disk('private')->put($this->imageBatchIdPath(), $batch->id);

        return $total;
    }

    /**
     * Fetch a set of URLs and return the manifest entries for them. The body of
     * one DownloadImages job.
     */
    public function downloadImageUrls(array $urls): array
    {
        $entries = [];

        foreach ($urls as $url) {
            $entries[$url] = $this->fetchImage($url);
        }

        return $entries;
    }

    /**
     * Has this wave already produced its fragment? The queue can hand the same
     * wave to a second worker while the first still holds it, and fetching every
     * image in it twice is exactly the network cost this phase exists to avoid.
     */
    public function hasImageFragment(int $chunkIndex): bool
    {
        return Storage::disk('private')->exists($this->imageFragmentDir().'/'.$chunkIndex.'.json');
    }

    /**
     * Persist one download job's results.
     */
    public function writeImageFragment(int $chunkIndex, array $fragment): void
    {
        Storage::disk('private')->put(
            $this->imageFragmentDir().'/'.$chunkIndex.'.json',
            json_encode($fragment, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Fold every download job's results back into the manifest, which is what
     * the row-writing phase reads.
     */
    public function mergeImageFragments(): void
    {
        $disk = Storage::disk('private');

        $manifest = $this->readImageManifest() ?? [];

        foreach ($disk->files($this->imageFragmentDir()) as $file) {
            foreach (json_decode($disk->get($file), true) ?: [] as $url => $entry) {
                $manifest[$url] = $entry;
            }
        }

        $this->writeImageManifest($manifest);

        $disk->deleteDirectory($this->imageFragmentDir());
    }

    /**
     * Progress of a queued image download, read from the fragments on disk.
     */
    public function queuedImageProgress(): array
    {
        $manifest = $this->readImageManifest() ?? [];

        $disk = Storage::disk('private');

        $done = 0;

        foreach ($disk->files($this->imageFragmentDir()) as $file) {
            $done += count(json_decode($disk->get($file), true) ?: []);
        }

        /**
         * Images already settled in the manifest before this run count too, so a
         * resumed download does not restart its bar at zero.
         */
        $settled = count(array_filter(
            $manifest,
            fn ($entry) => ($entry['status'] ?? 'pending') !== 'pending'
        ));

        $total = count($manifest);

        $processed = min($total, max($done, $settled));

        return [
            'total' => $total,
            'processed' => $processed,
            'progress' => $total > 0 ? (int) floor($processed / $total * 100) : 100,
            'done' => $total === 0 || $processed >= $total || $this->imageBatchSettled(),
        ];
    }

    /**
     * How many images were fetched, and how many could not be, for the summary.
     */
    public function imageReportStats(): array
    {
        $manifest = $this->readImageManifest() ?? [];

        $downloaded = 0;

        $failed = 0;

        foreach ($manifest as $entry) {
            if (($entry['status'] ?? null) === 'downloaded') {
                $downloaded++;
            } elseif (($entry['status'] ?? null) === 'failed') {
                $failed++;
            }
        }

        return [
            'total' => count($manifest),
            'downloaded' => $downloaded,
            'failed' => $failed,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Fetching
    |--------------------------------------------------------------------------
    */

    /**
     * The remote image URLs on one row.
     */
    protected function remoteImageUrls(array $rowData): array
    {
        if (empty($rowData['images'])) {
            return [];
        }

        $urls = [];

        foreach (array_map('trim', explode(',', $rowData['images'])) as $image) {
            if ($this->isRemoteImage($image)) {
                $urls[] = $image;
            }
        }

        return $urls;
    }

    /**
     * Walk the rows of every batch this import validated. Reading the batches
     * rather than re-parsing the source keeps this to a handful of queries and
     * guarantees we only look at rows that actually passed validation.
     */
    protected function allImportRows(): iterable
    {
        $batches = $this->importBatchRepository->findWhere([
            'import_id' => $this->import->id,
        ]);

        foreach ($batches as $batch) {
            foreach ($batch->data as $rowData) {
                yield $rowData;
            }
        }
    }

    /**
     * Fetch one image and return its manifest entry. Never throws: a failure is
     * recorded against the URL so the import continues without that image, which
     * is the right trade — one unreachable host should not fail an import of
     * thousands of products.
     */
    protected function fetchImage(string $url): array
    {
        if (! $this->isSafeRemoteUrl($url)) {
            return [
                'status' => 'failed',
                'reason' => 'unsafe-host',
            ];
        }

        try {
            $response = Http::timeout(self::IMAGE_REQUEST_TIMEOUT)
                ->withOptions(['stream' => false])
                ->get($url);

            if (! $response->successful()) {
                return [
                    'status' => 'failed',
                    'reason' => 'http-'.$response->status(),
                ];
            }

            $contents = $response->body();

            if (strlen($contents) > self::MAX_IMAGE_BYTES) {
                return [
                    'status' => 'failed',
                    'reason' => 'too-large',
                ];
            }

            /**
             * Trust the bytes, not the content-type header: a server that lies
             * about the type would otherwise get an arbitrary file written into
             * the media directory.
             */
            $dimensions = @getimagesizefromstring($contents);

            if ($dimensions === false) {
                return [
                    'status' => 'failed',
                    'reason' => 'not-an-image',
                ];
            }

            return $this->storeImage($url, $contents, $dimensions);
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'reason' => 'unreachable',
            ];
        }
    }

    /**
     * Write a fetched image alongside the import's other files and return its
     * manifest entry.
     */
    protected function storeImage(string $url, string $contents, array $dimensions): array
    {
        $extension = image_type_to_extension($dimensions[2], false) ?: 'jpg';

        /**
         * Named by a hash of the URL so the same link fetched twice lands on the
         * same file, and so nothing in a remote name can escape the directory.
         */
        $name = sha1($url).'.'.$extension;

        $path = $this->imageDownloadDir().'/'.$name;

        Storage::disk('private')->put($path, $contents);

        return [
            'status' => 'downloaded',
            'path' => $path,
            'name' => basename(parse_url($url, PHP_URL_PATH) ?: '') ?: $name,
        ];
    }

    /**
     * Is this an image reference we should fetch rather than look for on disk?
     */
    protected function isRemoteImage(string $image): bool
    {
        return (bool) preg_match('#^https?://#i', $image);
    }

    /**
     * Would fetching this URL reach somewhere it should not?
     *
     * An import file is operator-supplied but its contents are frequently not —
     * a URL column is an open invitation to make the server issue requests on
     * someone else's behalf. Only http(s) is allowed, and only to addresses
     * outside the private, loopback and reserved ranges, so a URL cannot be used
     * to read the machine's own metadata service or probe the internal network.
     */
    protected function isSafeRemoteUrl(string $url): bool
    {
        $parts = parse_url($url);

        if (
            empty($parts['host'])
            || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
        ) {
            return false;
        }

        $host = $parts['host'];

        $addresses = filter_var($host, FILTER_VALIDATE_IP)
            ? [$host]
            : array_merge(
                gethostbynamel($host) ?: [],
                $this->resolveIpv6($host)
            );

        if (empty($addresses)) {
            return false;
        }

        foreach ($addresses as $address) {
            if (! filter_var(
                $address,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            )) {
                return false;
            }
        }

        return true;
    }

    /**
     * The host's IPv6 addresses, if it has any. A host that resolves to a public
     * IPv4 address and a loopback IPv6 one must still be rejected.
     */
    protected function resolveIpv6(string $host): array
    {
        $records = @dns_get_record($host, DNS_AAAA) ?: [];

        return array_values(array_filter(array_column($records, 'ipv6')));
    }

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    */

    /**
     * Progress numbers for a manifest.
     */
    protected function imageProgress(array $manifest): array
    {
        $total = count($manifest);

        $processed = count(array_filter(
            $manifest,
            fn ($entry) => ($entry['status'] ?? 'pending') !== 'pending'
        ));

        return [
            'total' => $total,
            'processed' => $processed,
            'progress' => $total > 0 ? (int) floor($processed / $total * 100) : 100,
            'done' => $processed >= $total,
        ];
    }

    /**
     * Where fetched images are written.
     *
     * Everything an import owns lives under `imports/{id}` on the private disk,
     * with anything generated from the source kept in a sibling `processed/`
     * folder. That way the whole import — source file, uploaded images, fetched
     * images, fragments and reports — is one directory that can be reasoned
     * about, and removed, as a unit.
     */
    protected function imageDownloadDir(): string
    {
        return 'imports/'.$this->import->id.'/processed/downloads';
    }

    /**
     * Directory holding this import's per-job download fragments.
     */
    protected function imageFragmentDir(): string
    {
        return 'imports/'.$this->import->id.'/processed/image-fragments';
    }

    /**
     * Location of the download manifest.
     */
    protected function imageManifestPath(): string
    {
        return 'imports/'.$this->import->id.'/processed/image-manifest.json';
    }

    /**
     * Where the id of the dispatched download batch is kept, so progress can ask
     * the queue whether the batch is over.
     */
    protected function imageBatchIdPath(): string
    {
        return 'imports/'.$this->import->id.'/processed/image-batch-id.txt';
    }

    /**
     * Has the dispatched download batch stopped running?
     *
     * A job that dies outright never writes its fragment, so counting fragments
     * alone can never reach the total and the caller polls for ever. The batch
     * record is the authority on whether there is still anything to wait for.
     */
    protected function imageBatchSettled(): bool
    {
        $disk = Storage::disk('private');

        if (! $disk->exists($this->imageBatchIdPath())) {
            return false;
        }

        $batch = Bus::findBatch(trim($disk->get($this->imageBatchIdPath())));

        return ! $batch || $batch->finished() || $batch->cancelled();
    }

    /**
     * Read the manifest, or null when one has not been built yet.
     */
    protected function readImageManifest(): ?array
    {
        $disk = Storage::disk('private');

        if (! $disk->exists($this->imageManifestPath())) {
            return null;
        }

        return json_decode($disk->get($this->imageManifestPath()), true) ?: [];
    }

    /**
     * Persist the manifest.
     */
    protected function writeImageManifest(array $manifest): void
    {
        Storage::disk('private')->put(
            $this->imageManifestPath(),
            json_encode($manifest, JSON_UNESCAPED_SLASHES)
        );
    }
}
