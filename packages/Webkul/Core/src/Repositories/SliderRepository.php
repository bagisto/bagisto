<?php

namespace Webkul\Core\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

class SliderRepository extends Repository
{
    use CacheableRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        Container $container
    )
    {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Core\Contracts\Slider';
    }

    /**
     * Save slider.
     *
     * @param  array  $data
     * @return \Webkul\Core\Contracts\Slider
     */
    public function create(array $data)
    {
        $channelName = $this->channelRepository->find($data['channel_id'])->name;

        $dir = 'slider_images/' . $channelName;

        $uploaded = $image = false;

        if (isset($data['image'])) {
            $image = $first = Arr::first($data['image'], function ($value, $key) {
                if ($value) {
                    return $value;
                } else {
                    return false;
                }
            });
        }

        if ($image != false) {
            $uploaded = $image->store($dir);

            unset($data['image'], $data['_token']);
        }

        if ($uploaded) {
            $data['path'] = $uploaded;
        } else {
            unset($data['image']);
        }

        return parent::create($data);
    }

    /**
     * Update item.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Core\Contracts\Slider
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $channelName = $this->channelRepository->find($data['channel_id'])->name;

        $dir = 'slider_images/' . $channelName;

        $uploaded = $image = false;

        if (isset($data['image'])) {
            $image = $first = Arr::first($data['image'], function ($value, $key) {
                return $value ? $value : false;
            });
        }

        if ($image != false) {
            $uploaded = $image->store($dir);

            unset($data['image'], $data['_token']);
        }

        if ($uploaded) {
            $sliderItem = $this->find($id);

            Storage::delete($sliderItem->path);

            $data['path'] = $uploaded;
        } else {
            unset($data['image']);
        }

        return parent::update($data, $id);
    }

    /**
     * Delete a slider item and delete the image from the disk or where ever it is.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        $sliderItem = $this->find($id);

        $sliderItemImage = $sliderItem->path;

        Storage::delete($sliderItemImage);

        return $this->model->destroy($id);
    }

    /**
     * Get only active sliders.
     *
     * @return array
     */
    public function getActiveSliders()
    {
        $currentChannel = core()->getCurrentChannel();

        $currentLocale = core()->getCurrentLocale();

        return $this->where('channel_id', $currentChannel->id)
            ->whereRaw("find_in_set(?, locale)", [$currentLocale->code])
            ->where(function ($query) {
                $query->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
                    ->orWhereNull('expired_at');
            })
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->toArray();
    }
}
