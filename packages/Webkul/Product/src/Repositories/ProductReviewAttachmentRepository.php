<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Traits\Sanitizer;
use Webkul\Product\Contracts\ProductReview;
use Webkul\Product\Contracts\ProductReviewAttachment;

class ProductReviewAttachmentRepository extends Repository
{
    use Sanitizer;

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductReviewAttachment::class;
    }

    /**
     * Upload.
     */
    public function upload(array $attachments, ProductReview $review): void
    {
        foreach ($attachments as $attachment) {
            $mimeType = $attachment->getMimeType();

            $fileType = explode('/', $mimeType);

            $path = $attachment->store('review/'.$review->id);

            $this->sanitizeSVG($path, $mimeType);

            $this->create([
                'path'      => $path,
                'review_id' => $review->id,
                'type'      => $fileType[0],
                'mime_type' => $fileType[1] ?? null,
            ]);
        }
    }
}
