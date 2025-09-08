<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id'              => $this->id,
            'transaction_id'  => $this->transaction_id,
            'order_id'        => $this->order_id,
            'payment_title'   => $this->payment_title,
            'amount'          => core()->formatPrice($this->amount),
            'invoice_id'      => $this->invoice_id,
            'status'          => match ($this->status) {
                'paid'      => '<span class="label-active">'.ucfirst($this->status).'</span>',
                'pending'   => '<span class="label-pending">'.ucfirst($this->status).'</span>',
                'completed' => '<span class="label-active">'.ucfirst($this->status).'</span>',
                default     => $this->status,
            },
            'created_at'      => $this->created_at->format('d M Y'),
        ];
    }
}
