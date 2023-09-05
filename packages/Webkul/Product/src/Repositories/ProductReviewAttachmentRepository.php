<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\MimeType;
use Webkul\Product\Contracts\ProductReviewAttachment;
use Webkul\Product\Contracts\ProductReview;
use Webkul\Core\Eloquent\Repository;


class ProductReviewAttachmentRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductReviewAttachment::class;
    }

    /**
     * Upload.
     * 
     * @return void
     */
    public function upload(array $attachments, ProductReview $review): void
    {
        foreach ($attachments as $attachment) {
            $fileType = explode('/', $attachment->getMimeType());

            $this->create([
                'path'      => $attachment->store('review/' . $review->id),
                'review_id' => $review->id,
                'type'      => $fileType[0],
                'mime_type' => $fileType[1],
            ]);
        }
    }
}
