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
        'gdpr_status',
        'customer_agreement_status',
        'agreement_label',
        'agreement_content',
        'cookie_status',
        'cookie_block_position',
        'cookie_static_block_identifier',
        'data_update_request_template',
        'data_delete_request_template',
        'request_status_update_template',
        'request_status_delete_template',
    ];
}
