<?php

namespace Webkul\DataTransfer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;

class ImportBatch extends Model implements ImportBatchContract
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state',
        'data',
        'summary',
        'import_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'summary' => 'array',
        'data' => 'array',
    ];

    /**
     * Get the import that owns the import batch.
     *
     * @return BelongsTo
     */
    public function import()
    {
        return $this->belongsTo(ImportProxy::modelClass());
    }
}
