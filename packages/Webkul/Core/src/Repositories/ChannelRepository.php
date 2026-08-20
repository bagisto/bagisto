<?php

namespace Webkul\Core\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Channel;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Helpers\MediaFileName;

class ChannelRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected MediaFileName $mediaFileName,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Core\Contracts\Channel';
    }

    /**
     * Create.
     *
     * @return Channel
     */
    public function create(array $data)
    {

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
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
     * Update.
     *
     * @param  int  $id
     * @return Channel
     */
    public function update(array $data, $id)
    {
        $channel = parent::update($data, $id);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        $channel->inventory_sources()->sync($data['inventory_sources']);

        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }

    /**
     * Upload images.
     *
     * @param  array  $data
     * @param  Channel  $channel
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $channel, $type = 'logo')
    {
        $meta = collect($data[$type.'_meta'] ?? [])->first() ?? [];

        if (request()->hasFile($type)) {
            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $file = current(request()->file($type));

            $channel->{$type} = $this->mediaFileName->resolve(
                'channel/'.$channel->id,
                $meta['file_name'] ?? null,
                $file->getClientOriginalExtension()
            );

            Storage::put($channel->{$type}, $file->get());

            $channel->save();
        } elseif (! isset($data[$type])) {
            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $channel->{$type} = null;

            $channel->save();

            $this->clearMediaAltText($channel, $type.'_alt');

            return;
        } elseif ($channel->{$type}) {
            $renamed = $this->mediaFileName->rename($channel->{$type}, $meta['file_name'] ?? null);

            if ($renamed !== $channel->{$type}) {
                $channel->{$type} = $renamed;

                $channel->save();
            }
        }

        if (
            array_key_exists('alt_text', $meta)
            && in_array($type.'_alt', $channel->translatedAttributes)
        ) {
            foreach (core()->getRequestedLocaleCodes() as $localeCode) {
                if (! $translation = $channel->translate($localeCode)) {
                    continue;
                }

                $translation->{$type.'_alt'} = $meta['alt_text'];
            }

            $channel->save();
        }
    }

    /**
     * Drop the alt text of a channel image across every locale, used when the image
     * itself is removed.
     *
     * @param  Channel  $channel
     */
    protected function clearMediaAltText($channel, string $attribute): void
    {
        if (! in_array($attribute, $channel->translatedAttributes)) {
            return;
        }

        foreach ($channel->translations as $translation) {
            $translation->{$attribute} = null;

            $translation->save();
        }
    }
}
