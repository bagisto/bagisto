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
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'title'      => $this->title,
            'comment'    => $this->comment,
            'rating'     => $this->rating,
            'images'     => $this->images,
            'created_at' => $this->created_at->format('M d, Y'),
        ];
    }
}
