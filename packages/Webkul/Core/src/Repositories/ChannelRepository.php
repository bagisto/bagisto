<?php

namespace Webkul\Core\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Webkul\Core\Contracts\Channel;
use Webkul\Core\Eloquent\Repository;

class ChannelRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Channel::class;
    }

    /**
     * Create channel.
     */
    public function create(array $data): Channel
    {
        foreach (core()->getAllLocales() as $locale) {
            foreach ($this->getModel()->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }
        }

        $channel = parent::create($data);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        $channel->inventory_sources()->sync($data['inventory_sources']);

        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }

    /**
     * Update the channel.
     *
     * @param  int  $id
     * @param  string  $attribute
     */
    public function update(array $data, $id, $attribute = 'id'): Channel
    {
        $channel = parent::update($data, $id, $attribute);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        $channel->inventory_sources()->sync($data['inventory_sources']);

        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }

    /**
     * Upload images.
     */
    public function uploadImages(array $data, Channel $channel, string $type = 'logo'): void
    {
        if (empty($data[$type])) {
            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $channel->{$type} = null;

            $channel->save();

            return;
        }

        foreach ($data[$type] as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $image = (new ImageManager())->make($image)->encode('webp');

            $channel->{$type} = 'channel/'.$channel->id.'/'.Str::uuid()->toString().'.webp';

            Storage::put($channel->{$type}, $image);

            $channel->save();
        }
    }
}
