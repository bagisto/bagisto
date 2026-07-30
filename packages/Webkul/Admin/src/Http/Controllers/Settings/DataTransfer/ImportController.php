<?php

namespace Webkul\Admin\Http\Controllers\Settings\DataTransfer;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\DataTransfer\ImportDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Repositories\ImportRepository;
use ZipArchive;

class ImportController extends Controller
{
    /**
     * Largest images archive accepted, in KB (100 MB).
     */
    protected const MAX_IMAGES_ARCHIVE_SIZE = 102400;

    /**
     * Supported formats.
     */
    protected array $supportedFormats = ['csv', 'xls', 'xlsx', 'xml'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportRepository $importRepository,
        protected Import $importHelper
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ImportDataGrid::class)->process();
        }

        return view('admin::settings.data-transfer.imports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin::settings.data-transfer.imports.create', [
            'supportedFormats' => $this->supportedFormats,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $importers = implode(',', array_keys(config('importers')));

        $supportedFormats = implode(',', $this->supportedFormats);

        $this->validate(request(), [
            'type' => 'required|in:'.$importers,
            'action' => 'required|in:append,delete',
            'validation_strategy' => 'required|in:stop-on-errors,skip-errors',
            'allowed_errors' => 'required|integer|min:0',
            'field_separator' => 'required',
            'file' => 'required|file|extensions:'.$supportedFormats.'|mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/xml,application/xml',
            'image_source' => 'nullable|in:url,upload,directory',
            'upload_images' => 'required_if:image_source,'.Import::IMAGE_SOURCE_UPLOAD.'|nullable|file|mimes:zip|max:'.$this->maxUploadSize(),
            'images_directory_path' => 'required_if:image_source,'.Import::IMAGE_SOURCE_DIRECTORY.'|nullable|string',
        ]);

        Event::dispatch('data_transfer.imports.create.before');

        $data = request()->only([
            'type',
            'action',
            'process_in_queue',
            'validation_strategy',
            'validation_strategy',
            'allowed_errors',
            'field_separator',
            'images_directory_path',
        ]);

        $data['process_in_queue'] = request()->boolean('process_in_queue');

        $data['image_source'] = request()->input('image_source', Import::IMAGE_SOURCE_DIRECTORY);

        /**
         * The record is created first so every file it owns can live under a
         * single, predictable "imports/{id}" folder. If storing those files
         * fails, the record is rolled back so no orphan import is left behind.
         */
        $import = $this->importRepository->create(array_merge($data, ['file_path' => '']));

        try {
            $import = $this->importRepository->update(
                $this->storeImportFiles(request(), $import->id),
                $import->id
            );
        } catch (\Throwable $e) {
            $this->purgeImportFiles($import->id);

            $this->importRepository->delete($import->id);

            throw $e;
        }

        Event::dispatch('data_transfer.imports.create.after', $import);

        session()->flash('success', trans('admin::app.settings.data-transfer.imports.create-success'));

        return redirect()->route('admin.settings.data_transfer.imports.import', $import->id);
    }

    /**
     * Show the form for editing a new resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        return view('admin::settings.data-transfer.imports.edit', [
            'import' => $import,
            'supportedFormats' => $this->supportedFormats,
            'uploadedImagesCount' => $this->countUploadedImages($import->id),
        ]);
    }

    /**
     * Update a resource in storage.
     *
     * @return Response
     */
    public function update(int $id)
    {
        $importers = implode(',', array_keys(config('importers')));

        $supportedFormats = implode(',', $this->supportedFormats);

        $import = $this->importRepository->findOrFail($id);

        $this->validate(request(), [
            'type' => 'required|in:'.$importers,
            'action' => 'required|in:append,delete',
            'validation_strategy' => 'required|in:stop-on-errors,skip-errors',
            'allowed_errors' => 'required|integer|min:0',
            'field_separator' => 'required',
            'file' => 'nullable|file|extensions:'.$supportedFormats.'|mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/xml,application/xml',
            'image_source' => 'nullable|in:url,upload,directory',

            /**
             * An archive already unpacked for this import counts, so editing
             * anything else on the form does not mean uploading it a second time.
             */
            'upload_images' => ($this->countUploadedImages($id) ? '' : 'required_if:image_source,'.Import::IMAGE_SOURCE_UPLOAD.'|')
                .'nullable|file|mimes:zip|max:'.$this->maxUploadSize(),
            'images_directory_path' => 'required_if:image_source,'.Import::IMAGE_SOURCE_DIRECTORY.'|nullable|string',
        ]);

        Event::dispatch('data_transfer.imports.update.before');

        $data = array_merge(
            request()->only([
                'type',
                'action',
                'process_in_queue',
                'validation_strategy',
                'validation_strategy',
                'allowed_errors',
                'field_separator',
                'images_directory_path',
            ]),
            [
                'state' => 'pending',
                'processed_rows_count' => 0,
                'invalid_rows_count' => 0,
                'errors_count' => 0,
                'errors' => null,
                'error_file_path' => null,
                'started_at' => null,
                'completed_at' => null,
                'summary' => null,
            ]
        );

        $data['process_in_queue'] = request()->boolean('process_in_queue');

        $data['image_source'] = request()->input('image_source', Import::IMAGE_SOURCE_DIRECTORY);

        /**
         * Editing an import resets it back to pending, and the file itself may
         * have been replaced — so everything generated from the old one (the
         * resumable validation state, the fragments, the image manifest and the
         * error report) describes a file that no longer exists. Left behind, a
         * re-validation would resume mid-way through the previous file.
         */
        $this->purgeProcessedFiles($import->id);

        if (request()->file('file')?->isValid()) {
            Storage::disk('private')->delete($import->file_path);
        }

        $data = array_merge($data, $this->storeImportFiles(request(), $import->id));

        $import = $this->importRepository->update($data, $import->id);

        Event::dispatch('data_transfer.imports.update.after', $import);

        session()->flash('success', trans('admin::app.settings.data-transfer.imports.update-success'));

        return redirect()->route('admin.settings.data_transfer.imports.import', $import->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $import = $this->importRepository->findOrFail($id);

        try {
            $this->purgeImportFiles($import->id);

            $this->importRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.settings.data-transfer.imports.delete-failed'),
        ], 500);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function import(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        $isValid = $this->importHelper
            ->setImport($import)
            ->isValid();

        if ($import->state == Import::STATE_LINKING) {
            if ($this->importHelper->isIndexingRequired()) {
                $state = Import::STATE_INDEXING;
            } else {
                $state = Import::STATE_COMPLETED;
            }
        } elseif ($import->state == Import::STATE_INDEXING) {
            $state = Import::STATE_COMPLETED;
        } else {
            $state = Import::STATE_COMPLETED;
        }

        $stats = $this->importHelper->stats($state);

        /**
         * Which phases this import actually walks through decides the steps the
         * screen shows, so they are resolved from the importer here rather than
         * inferred in the view — linking and indexing depend on the type being
         * imported, not on whether it carries images.
         */
        $hasImagePhase = $this->importHelper->hasImagePhase();

        $hasLinkPhase = $this->importHelper->isLinkingRequired();

        $hasIndexPhase = $this->importHelper->isIndexingRequired();

        $import->unsetRelations();

        return view('admin::settings.data-transfer.imports.import', compact(
            'import',
            'isValid',
            'stats',
            'hasImagePhase',
            'hasLinkPhase',
            'hasIndexPhase'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function validateImport(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $isValid = $this->importHelper
            ->setImport($import)
            ->validate();

        return new JsonResponse([
            'is_valid' => $isValid,
            'import' => $this->importHelper->getImport()->unsetRelations(),
        ]);
    }

    /**
     * Validate the next window of the source file and return progress.
     *
     * A large file takes longer to validate than a request may stay open, so the
     * browser drives validation in short windows — showing a live "x of N" count
     * — instead of one long request that would time out. Each call validates a
     * bounded number of rows and resumes where the previous one left off.
     */
    public function validateChunk(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $progress = $this->importHelper
            ->setImport($import)
            ->validateChunk();

        return new JsonResponse($progress);
    }

    /**
     * Dispatch validation as a background, parallel queue batch. Returns the row
     * total; the browser then polls validateStatus while the worker fleet
     * validates and, on completion, the fragments are merged and the record
     * finalised.
     */
    public function validateQueued(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $total = $this->importHelper
            ->setImport($import)
            ->queueValidation();

        return new JsonResponse(['total' => $total]);
    }

    /**
     * Progress of the queued validation. Once done, returns the finalised import
     * and its verdict so the browser can move on.
     */
    public function validateStatus(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $this->importHelper->setImport($import);

        $progress = $this->importHelper->queuedValidationProgress();

        if ($progress['done']) {
            $progress['is_valid'] = $this->importHelper->isValid();

            $progress['import'] = $this->importHelper->getImport()->unsetRelations();
        }

        return new JsonResponse($progress);
    }

    /**
     * Fetch the next wave of the import's remote images, as a phase of its own
     * before any row is written, and return progress.
     */
    public function downloadImages(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        return new JsonResponse(
            $this->importHelper->setImport($import)->downloadImages()
        );
    }

    /**
     * Dispatch the image download as a background, parallel queue batch. Returns
     * the image total; the browser then polls downloadImagesStatus while the
     * worker fleet fetches them.
     */
    public function downloadImagesQueued(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $total = $this->importHelper
            ->setImport($import)
            ->queueImageDownload();

        return new JsonResponse(['total' => $total]);
    }

    /**
     * Progress of the queued image download.
     */
    public function downloadImagesStatus(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        return new JsonResponse(
            $this->importHelper->setImport($import)->queuedImageProgress()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function start(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->processed_rows_count) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.nothing-to-import'),
            ], 400);
        }

        $this->importHelper->setImport($import);

        if (! $this->importHelper->isValid()) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.not-valid'),
            ], 400);
        }

        if (
            $import->process_in_queue
            && config('queue.default') == 'sync'
        ) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.setup-queue-error'),
            ], 400);
        }

        /**
         * A queued import dispatches every one of its batches from this single
         * request, so the dispatch is claimed rather than repeated: whoever
         * moves the record out of `validated` owns it, and any later caller
         * just gets the progress back. Without the claim a reload or a second
         * click dispatches the whole chain again over the same rows.
         */
        if ($import->process_in_queue) {
            try {
                if ($this->importHelper->claimForProcessing()) {
                    $this->importHelper->start();
                }
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }

            return new JsonResponse([
                'stats' => $this->importHelper->stats(Import::STATE_PROCESSED),
                'import' => $this->importHelper->getImport()->unsetRelations(),
            ]);
        }

        /**
         * Set the import state to processing.
         */
        if ($import->state == Import::STATE_VALIDATED) {
            $this->importHelper->started();
        }

        /**
         * Get the first pending batch to import.
         */
        $importBatch = $import->batches->where('state', Import::STATE_PENDING)->first();

        if ($importBatch) {
            /**
             * Start the import process.
             */
            try {
                $this->importHelper->start($importBatch);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }
        } else {
            if ($this->importHelper->isLinkingRequired()) {
                $this->importHelper->linking();
            } elseif ($this->importHelper->isIndexingRequired()) {
                $this->importHelper->indexing();
            } else {
                $this->importHelper->completed();
            }
        }

        return new JsonResponse([
            'stats' => $this->importHelper->stats(Import::STATE_PROCESSED),
            'import' => $this->importHelper->getImport()->unsetRelations(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function link(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->processed_rows_count) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.nothing-to-import'),
            ], 400);
        }

        $this->importHelper->setImport($import);

        if (! $this->importHelper->isValid()) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.not-valid'),
            ], 400);
        }

        /**
         * Set the import state to linking.
         */
        if ($import->state == Import::STATE_PROCESSED) {
            $this->importHelper->linking();
        }

        /**
         * Get the first processing batch to link.
         */
        $importBatch = $import->batches->where('state', Import::STATE_PROCESSED)->first();

        /**
         * Set the import state to linking/completed.
         */
        if ($importBatch) {
            /**
             * Start the resource linking process.
             */
            try {
                $this->importHelper->link($importBatch);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }
        } else {
            if ($this->importHelper->isIndexingRequired()) {
                $this->importHelper->indexing();
            } else {
                $this->importHelper->completed();
            }
        }

        return new JsonResponse([
            'stats' => $this->importHelper->stats(Import::STATE_LINKED),
            'import' => $this->importHelper->getImport()->unsetRelations(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function indexData(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->processed_rows_count) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.nothing-to-import'),
            ], 400);
        }

        $this->importHelper->setImport($import);

        if (! $this->importHelper->isValid()) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.not-valid'),
            ], 400);
        }

        /**
         * Set the import state to linking.
         */
        if ($import->state == Import::STATE_LINKED) {
            $this->importHelper->indexing();
        }

        /**
         * Get the first processing batch to link.
         */
        $importBatch = $import->batches->where('state', Import::STATE_LINKED)->first();

        /**
         * Set the import state to linking/completed.
         */
        if ($importBatch) {
            /**
             * Start the resource linking process.
             */
            try {
                $this->importHelper->index($importBatch);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }
        } else {
            /**
             * Set the import state to completed.
             */
            $this->importHelper->completed();
        }

        return new JsonResponse([
            'stats' => $this->importHelper->stats(Import::STATE_INDEXED),
            'import' => $this->importHelper->getImport()->unsetRelations(),
        ]);
    }

    /**
     * Returns import stats.
     */
    public function stats(int $id, string $state = Import::STATE_PROCESSED): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $stats = $this->importHelper
            ->setImport($import)
            ->stats($state);

        return new JsonResponse([
            'stats' => $stats,
            'import' => $this->importHelper->getImport()->unsetRelations(),
        ]);
    }

    /**
     * Download sample file.
     */
    public function downloadSample(string $type, string $format)
    {
        $samplePath = config("importers.{$type}.sample_paths.{$format}");

        return Storage::download($samplePath);
    }

    /**
     * Download the sample images archive for an importer.
     *
     * It holds exactly the files the sample sheet's `images` column names, so
     * uploading the two together imports cleanly and shows what the archive is
     * expected to look like.
     */
    public function downloadSampleImagesZip(string $type = 'products')
    {
        $samplePath = config("importers.{$type}.sample_images_zip_path");

        /**
         * Not every importer carries images, and one that does not has no
         * archive to hand back.
         */
        abort_if(
            empty($samplePath) || ! Storage::exists($samplePath),
            404
        );

        return Storage::download($samplePath);
    }

    /**
     * Download import file.
     */
    public function download(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        return Storage::disk('private')->download($import->file_path);
    }

    /**
     * Download import error report.
     */
    public function downloadErrorReport(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->error_file_path) {
            abort(404);
        }

        return Storage::disk('private')->download($import->error_file_path);
    }

    /**
     * Store the files that arrived with an import and return the columns they
     * set. Everything lands under the import's own "imports/{id}" folder.
     */
    protected function storeImportFiles(Request $request, int $importId): array
    {
        $data = [];

        if ($request->file('file')?->isValid()) {
            $data['file_path'] = $this->storeSourceFile($request->file('file'), $importId);
        }

        /**
         * An archive only matters when the operator chose to upload one. Picking
         * a different method leaves any previously unpacked images behind
         * untouched, so switching back does not mean uploading again.
         *
         * Note this never touches `images_directory_path`: that column belongs to
         * the server-directory method alone. Writing the unpack location into it
         * would overwrite the path the operator typed, and hand it back to them
         * as their own directory the next time they opened the form.
         */
        if (
            $request->input('image_source') == Import::IMAGE_SOURCE_UPLOAD
            && $request->file('upload_images')?->isValid()
        ) {
            /**
             * A replacement archive starts from an empty directory. Unpacking
             * over the previous one would only overwrite same-named files,
             * leaving any image dropped from the archive still on disk and still
             * matched by the sheet.
             */
            $this->purgeImportImages($importId);

            $this->storeImagesArchive($request->file('upload_images'), $importId);

            $data['images_archive_name'] = $request->file('upload_images')->getClientOriginalName();
        }

        return $data;
    }

    /**
     * How many images an import currently has unpacked, so the edit form can say
     * that an archive was already uploaded rather than showing an empty field.
     */
    protected function countUploadedImages(int $importId): int
    {
        return count(Storage::disk('private')->files('imports/'.$importId.'/images'));
    }

    /**
     * Store the uploaded source file under the import's own folder, keeping its
     * original name so it is recognisable later (e.g. "imports/24/products.csv").
     * Generated artifacts live in a sibling "processed/" folder, so the source
     * name can never collide with them.
     */
    protected function storeSourceFile(UploadedFile $file, int $importId): string
    {
        return $file->storeAs(
            'imports/'.$importId,
            basename($file->getClientOriginalName()),
            'private'
        );
    }

    /**
     * Unpack an uploaded images archive into the import's own folder and return
     * the directory the importer should resolve images from.
     */
    protected function storeImagesArchive(UploadedFile $file, int $importId): string
    {
        $disk = Storage::disk('private');

        $directory = 'imports/'.$importId.'/images';

        $archivePath = $file->storeAs($directory, $file->getClientOriginalName(), 'private');

        $zip = new ZipArchive;

        if ($zip->open($disk->path($archivePath)) === true) {
            $zip->extractTo($disk->path($directory));

            $zip->close();
        }

        /**
         * The archive itself is not needed once unpacked — and leaving it in the
         * images directory would put a .zip where only images belong.
         */
        $disk->delete($archivePath);

        return $directory;
    }

    /**
     * The largest images archive that may be uploaded, in KB.
     *
     * PHP's own `upload_max_filesize` and `post_max_size` still apply and are
     * usually the lower of the two; this is the application's own ceiling so a
     * mistaken upload is rejected by validation rather than by the web server.
     * A catalogue too large for one archive is what the images-directory option
     * is for.
     */
    protected function maxUploadSize(): int
    {
        return self::MAX_IMAGES_ARCHIVE_SIZE;
    }

    /**
     * Remove everything an import owns. Its source file, uploaded images and
     * generated reports all live under "imports/{id}" on the private disk, so
     * that single directory is the import's entire footprint.
     */
    protected function purgeImportFiles(int $importId): void
    {
        Storage::disk('private')->deleteDirectory('imports/'.$importId);
    }

    /**
     * Drop only what was generated from the source file, leaving the source and
     * any uploaded images in place.
     */
    protected function purgeProcessedFiles(int $importId): void
    {
        Storage::disk('private')->deleteDirectory('imports/'.$importId.'/processed');
    }

    /**
     * Drop an import's unpacked images.
     */
    protected function purgeImportImages(int $importId): void
    {
        Storage::disk('private')->deleteDirectory('imports/'.$importId.'/images');
    }
}
