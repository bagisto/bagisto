<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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
     * Store every attachment and record them only once all of them are readable, so a
     * file that cannot be sanitized refuses the batch instead of recording half of it.
     */
    public function upload(array $attachments, ProductReview $review): void
    {
        $recorded = [];

        foreach ($attachments as $attachment) {
            $mimeType = $attachment->getMimeType();

            $path = $attachment->store('reviews/'.$review->id);

            if (! $this->sanitizeSVG($path, $mimeType)) {
                Storage::delete(array_column($recorded, 'path'));

                throw ValidationException::withMessages([
                    'attachments' => trans('core::app.errors.invalid-svg'),
                ]);
            }

            $fileType = explode('/', $mimeType);

            $recorded[] = [
                'path' => $path,
                'review_id' => $review->id,
                'type' => $fileType[0],
                'mime_type' => $fileType[1] ?? null,
            ];
        }

        foreach ($recorded as $attributes) {
            $this->create($attributes);
        }
    }
}
