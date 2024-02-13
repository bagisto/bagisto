<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\Product;

class ProductDownloadableLinkRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductDownloadableLink';
    }

    /**
     * Upload files related to downloadable products
     */
    public function upload(array $data, int $productId): array
    {
        foreach ($data as $type => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            return [
                $type         => $path = $file->store('product_downloadable_links/'.$productId, 'private'),
                $type.'_name' => $file->getClientOriginalName(),
                $type.'_url'  => Storage::url($path),
            ];
        }

        return [];
    }

    /**
     * Save links.
     */
    public function saveLinks(array $data, Product $product): void
    {
        $previousLinkIds = $product->downloadable_links()->pluck('id');

        if (isset($data['downloadable_links'])) {
            foreach ($data['downloadable_links'] as $linkId => $data) {
                if (Str::contains($linkId, 'link_')) {
                    $this->create(array_merge([
                        'product_id' => $product->id,
                    ], $data));
                } else {
                    if (is_numeric($index = $previousLinkIds->search($linkId))) {
                        $previousLinkIds->forget($index);
                    }

                    $this->update($data, $linkId);
                }
            }
        }

        foreach ($previousLinkIds as $linkId) {
            $this->delete($linkId);
        }
    }
}
