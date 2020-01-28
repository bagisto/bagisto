<?php

namespace Webkul\Core\Repositories;

use Storage;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;
use Webkul\Core\Repositories\ChannelRepository;
use Illuminate\Support\Arr;

/**
 * Slider Repository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderRepository extends Repository
{
    /**
     * ChannelRepository object
     *
     * @var Object
     */
    protected $channelRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository $channelRepository
     * @return void
     */
    public function __construct(
        ChannelRepository $channelRepository,
        App $app
    )
    {
        $this->channelRepository = $channelRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\Slider';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        Event::dispatch('core.settings.slider.create.before', $data);

        $channelName = $this->channelRepository->find($data['channel_id'])->name;

        $dir = 'slider_images/' . $channelName;

        $uploaded = false;
        $image = false;

        if (isset($data['image'])) {
            $image = $first = Arr::first($data['image'], function ($value, $key) {
                if ($value)
                    return $value;
                else
                    return false;
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

        $slider = $this->create($data);

        Event::dispatch('core.settings.slider.create.after', $slider);

        return true;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateItem(array $data, $id)
    {
        Event::dispatch('core.settings.slider.update.before', $id);

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

        $slider = $this->update($data, $id);

        Event::dispatch('core.settings.slider.update.after', $slider);

        return true;
    }

    /**
     * Delete a slider item and delete the image from the disk or where ever it is
     *
     * @return Boolean
     */
    public function destroy($id)
    {
        $sliderItem = $this->find($id);

        $sliderItemImage = $sliderItem->path;

        Storage::delete($sliderItemImage);

        return $this->model->destroy($id);
    }
}