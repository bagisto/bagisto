<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Slider as SliderContract;

class Slider extends Model implements SliderContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'sliders';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'title',
        'path',
        'content',
        'channel_id',
        'locale',
        'expired_at',
        'sort_order'
    ];

	/**
	 * Get image url for the category image.
	 *
	 * @return null|string
	 */
	public function image_url(): ?string
	{
        if (! $this->path) {
            return null;
        }

        return Storage::url($this->path);
    }

	/**
	 * Get image url for the category image.
	 *
	 * @return null|string
	 */
    public function getImageUrlAttribute(): ?string
	{
        return $this->image_url();
    }

	/**
	 * Get the slider channel name associated with the channel.
	 *
	 * @return BelongsTo relationship
	 */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }
}
