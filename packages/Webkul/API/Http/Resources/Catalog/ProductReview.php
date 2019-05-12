<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;

class ProductReview extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'rating' => number_format($this->rating, 1, '.', ''),
            'comment' => $this->comment,
            'name' => $this->name,
            'status' => $this->status,
            'product' => new Product($this->product),
            'customer' => $this->when($this->customer_id, new CustomerResource($this->customer)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}