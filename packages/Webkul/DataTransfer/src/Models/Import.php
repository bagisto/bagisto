<?php

namespace Webkul\DataTransfer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\DataTransfer\Contracts\Import as ImportContract;

class Import extends Model implements ImportContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state',
        'process_in_queue',
        'type',
        'action',
        'validation_strategy',
        'validation_strategy',
        'allowed_errors',
        'processed_rows_count',
        'invalid_rows_count',
        'errors_count',
        'errors',
        'field_separator',
        'file_path',
        'images_directory_path',
        'error_file_path',
        'summary',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'summary'      => 'array',
        'errors'       => 'array',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the options.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(ImportBatchProxy::modelClass());
    }
}
