<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\RMA\Contracts\RMAMessage as RMAMessageContract;

class RMAMessage extends Model implements RMAMessageContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'rma_id',
        'is_admin',
        'attachment_path',
        'attachment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * The attributes appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'attachment_url',
    ];

    /**
     * The url the attachment is served from.
     *
     * A message records the path the file was stored at, so the disk it is
     * configured on decides the url rather than the application's own address.
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (empty($this->attachment_path)) {
            return null;
        }

        return Storage::url($this->attachment_path);
    }

    /**
     * Get the RMA that owns the message.
     */
    public function rma()
    {
        return $this->belongsTo(RMA::class);
    }
}
