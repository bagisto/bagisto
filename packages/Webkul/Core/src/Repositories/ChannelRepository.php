<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Channel;
use Webkul\Core\Eloquent\Repository;

class ChannelRepository extends Repository
{
   	// use CacheableRepository; (already imported by Repository)

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
	{
        return Channel::class;
    }

	/**
	 * @param array $data
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 * @return \Webkul\Core\Contracts\Channel
	 */
    public function create(array $data): Channel
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
	 * Update an existing Channel
	 *
	 * @see \Webkul\Core\Models\Channel Model entity
	 * @param int   $id   numeric identifier
	 * @param array $data associative list ['column' => 'value']
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 * @return \Webkul\Core\Contracts\Channel
	 */
	public function update(array $data, $id): Channel
	{
		$channel = $this->find($id);

		$$channel->update($data, $id);
        //$channel = parent::update($data, $id, $attribute);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        $channel->inventory_sources()->sync($data['inventory_sources']);

        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }

	/**
	 * @param array                          $data
	 * @param \Webkul\Core\Contracts\Channel $channel
	 * @param string                         $type
	 * @return void
	 */
    public function uploadImages(array $data, Channel $channel, string $type = "logo")
    {
        if (isset($data[$type])) {
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'channel/' . $channel->id;

                if (request()->hasFile($file)) {
                    if ($channel->{$type}) {
                        Storage::delete($channel->{$type});
                    }

                    $channel->{$type} = request()->file($file)->store($dir);
                    $channel->save();
                }
            }
        } else {
            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $channel->{$type} = null;
            $channel->save();
        }
    }
}
