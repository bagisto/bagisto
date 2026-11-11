<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Notification\TypeRegistry;

class NotificationResource extends JsonResource
{
    /**
     * Transform the notification into what the feed shows, rendered by its type in the admin's language.
     */
    public function toArray(Request $request): array
    {
        $type = app(TypeRegistry::class)->instance($this->type);

        $data = $this->data ?? [];

        return [
            'id' => $this->id,
            'title' => $type->title($data),
            'message' => $type->message($data),
            'icon' => $type->icon($data),
            'tone' => $type->tone($data),
            'url' => route('admin.notification.open', $this->id),
            'read' => (bool) $this->read,
            'created_at' => $this->created_at->toIso8601String(),
            'time_ago' => $this->created_at->diffForHumans(),
            'day' => $this->created_at->toDateString(),
            'day_label' => $this->dayLabel(),
        ];
    }

    /**
     * Today, yesterday, or the date, heading the notifications raised that day.
     */
    protected function dayLabel(): string
    {
        if ($this->created_at->isToday()) {
            return trans('admin::app.notifications.today');
        }

        if ($this->created_at->isYesterday()) {
            return trans('admin::app.notifications.yesterday');
        }

        return $this->created_at->translatedFormat('j F Y');
    }
}
