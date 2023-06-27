<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductReviewAttachment;

class ProductReviewAttachmentRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */

    function model(): string
    {
        return ProductReviewAttachment::class;
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\ProductReview  $review
     * @return void
     */
    public function uploadImages($data, $review)
    {
        if (! isset($data['attachments'])) {
            return;
        }

        foreach ($data['attachments'] as $imageId => $image) {
            $file = 'attachments.' . $imageId;
            
            $dir = 'review/' . $review->id;

            if (
                $image instanceof UploadedFile
                && request()->hasFile($file)
            ) {
                $this->create([
                    'path'      => request()->file($file)->store($dir),
                    'review_id' => $review->id,
                ]);
            }
        }
    }
}