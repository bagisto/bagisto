<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductReviewAttachment;

class ProductReviewAttachmentRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
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

        foreach ($data['attachments'] as $key => $attachment) {
            $fileType = explode('/', $attachment->getMimeType());

            $file = 'attachments.' . $key;
            
            $dir = 'review/' . $review->id;

            if (
                $attachment instanceof UploadedFile
                && request()->hasFile($file)
            ) {
                $this->create([
                    'path'      => request()->file($file)->store($dir),
                    'review_id' => $review->id,
                    'type'      => $fileType[0],
                    'mime_type' => $fileType[1],
                ]);
            }
        }
    }
}
