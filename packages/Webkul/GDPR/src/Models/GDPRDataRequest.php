<?php

namespace Webkul\GDPR\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\GDPR\Contracts\GDPRDataRequest as GDPRDataRequestContract;

class GDPRDataRequest extends Model implements GDPRDataRequestContract
{
    protected $table = 'gdpr_data_request';

    protected $fillable = [
        'customer_id',
        'email',
        'status',
        'type',
        'message',
    ];
}
