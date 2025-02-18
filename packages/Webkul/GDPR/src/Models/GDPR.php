<?php

namespace Webkul\GDPR\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\GDPR\Contracts\GDPR as GDPRContract;

class GDPR extends Model implements GDPRContract
{
    /**
     * The table associated with the model.
     */
    protected $table = 'gdpr';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'strictly_necessary',
        'basic_interaction',
        'experience_enhancement',
        'measurements',
        'targeting_advertising',
    ];
}
