<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'logo' => $this->when($this->hasStoredImage($this->logo_path), [
                'small_image_url' => url('cache/small/'.$this->logo_path),
                'medium_image_url' => url('cache/medium/'.$this->logo_path),
                'large_image_url' => url('cache/large/'.$this->logo_path),
                'original_image_url' => url('cache/original/'.$this->logo_path),
                'alt' => $this->logo_alt ?: $this->name,
            ]),
            'banner' => $this->when($this->hasStoredImage($this->banner_path), [
                'small_image_url' => url('cache/small/'.$this->banner_path),
                'medium_image_url' => url('cache/medium/'.$this->banner_path),
                'large_image_url' => url('cache/large/'.$this->banner_path),
                'original_image_url' => url('cache/original/'.$this->banner_path),
                'alt' => $this->banner_alt ?: $this->name,
            ]),
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
     * Whether the stored path still resolves to a file on disk.
     *
     * A category keeps its `logo_path` even after the file is removed from storage, and the
     * cached-image URL built from it then 404s. Omitting the block lets the storefront fall
     * back to its placeholder instead of rendering a broken image.
     */
    protected function hasStoredImage(?string $path): bool
    {
        return filled($path) && Storage::exists($path);
    }
}
