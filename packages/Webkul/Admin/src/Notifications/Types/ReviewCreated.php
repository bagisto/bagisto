<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class ReviewCreated extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-star';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'amber';

    /**
     * Keep the reviewer, the product and the rating given.
     */
    public function data(mixed $review): array
    {
        return [
            'review_id' => $review->id,
            'product_name' => $review->product?->name,
            'customer_name' => $review->name,
            'rating' => (int) $review->rating,
        ];
    }

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.review-created.title');
    }

    /**
     * Who rated which product, and how.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.review-created.message', [
            'customer' => $data['customer_name'] ?? '',
            'product' => $data['product_name'] ?? '',
            'rating' => $data['rating'] ?? 0,
        ]);
    }

    /**
     * The reviews listing, where the review is approved.
     */
    public function url(array $data): ?string
    {
        return route('admin.customers.customers.review.index');
    }
}
