<?php

namespace Webkul\DataTransfer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\DataTransfer\Contracts\Import as ImportContract;

class Import extends Model implements ImportContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'action',
        'validation_strategy',
        'validation_strategy',
        'allowed_errors',
        'field_separator',
        'file_path',
        'images_directory_path',
        'error_file_path',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];
}
