<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $images = $this->images->map(function ($image) {
            $image->url = $image->url;
        
            return $image;
        });

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'comment'    => $this->comment,
            'rating'     => $this->rating,
            'images'      => $images,
            'created_at' => $this->created_at->format('M d, Y'),
        ];
    }
}
