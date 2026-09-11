<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Webkul\ImageCache\TemplateRegistry;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status,
            'position' => $this->position,
            'display_mode' => $this->display_mode,
            'description' => $this->description,
            'logo' => $this->when(
                $this->hasStoredImage($this->logo_path),
                fn () => image_urls($this->logo_path, TemplateRegistry::CATEGORY_IMAGES) + ['alt' => $this->logo_alt ?: $this->name]
            ),
            'banner' => $this->when(
                $this->hasStoredImage($this->banner_path),
                fn () => image_urls($this->banner_path, TemplateRegistry::CATEGORY_IMAGES) + ['alt' => $this->banner_alt ?: $this->name]
            ),
            'meta' => [
                'title' => $this->meta_title,
                'keywords' => $this->meta_keywords,
                'description' => $this->meta_description,
            ],
            'translations' => $this->translations,
            'additional' => $this->additional,
        ];
    }

    /**
     * Whether the stored path still resolves to a file, since a category keeps its path after the file
     * is removed and the storefront should then fall back to its placeholder.
     */
    protected function hasStoredImage(?string $path): bool
    {
        return filled($path) && Storage::exists($path);
    }
}
