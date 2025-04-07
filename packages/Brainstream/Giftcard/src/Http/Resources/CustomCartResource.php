<?php

namespace Brainstream\Giftcard\Http\Resources;

use Webkul\Shop\Http\Resources\CartResource;

class CustomCartResource extends CartResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['giftcard_number'] = $this->giftcard_number;
        $data['giftcard_amount'] = $this->giftcard_amount;
        $data['remaining_giftcard_amount'] = $this->remaining_giftcard_amount;

        return $data;
    }
}
