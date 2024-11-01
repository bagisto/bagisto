<?php

namespace Frosko\FairySync\Models\Sync;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property string $firstname
 * @property string $lastname
 * @property string $city
 * @property string $country
 * @property ?string $company
 * @property string $address_line_1
 * @property ?string $address_line_2
 * @property ?string $postcode
 * @property ?string $custom
 */
class Address extends Model
{
    protected $connection = 'sync';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'firstname',
        'lastname',
        'city',
        'country',
        'company',
        'address_line_1',
        'address_line_2',
        'postcode',
        'custom',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'order_id',
        );
    }
}
