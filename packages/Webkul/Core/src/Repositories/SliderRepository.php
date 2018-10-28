<?php

namespace Webkul\Core\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Storage;

/**
 * Slider Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SliderRepository extends Repository
{
    protected $channel;

    public function __construct(
        Channel $channel,
        App $app
    )
    {
        $this->channel = $channel;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Models\Slider';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        $channelName = $this->channel->find($data['channel_id'])->name;

        $dir = 'slider_images/' . $channelName;

        $uploaded = false;

        $image = $first = array_first($data['image'], function ($value, $key) {
            if($value)
                return $value;
            else
                return false;
        });

        if($image != false) {
            $uploaded = $image->store($dir);

            unset($data['image'], $data['_token']);
        }

        if($uploaded) {
            $data['path'] = $uploaded;
        } else {
            unset($data['image']);
        }

        return $this->create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateItem(array $data, $id)
    {
        $channelName = $this->channel->find($data['channel_id'])->name;

        $dir = 'slider_images/' . $channelName;

        $uploaded = false;
        $image = $first = array_first($data['image'], function ($value, $key) {
            if($value)
                return $value;
            else
                return false;
        });

        if($image != false) {
            $uploaded = $image->store($dir);

            unset($data['image'], $data['_token']);
        }

        if($uploaded) {
            $sliderItem = $this->find($id);

            $deleted = Storage::delete($sliderItem->path);

            $data['path'] = $uploaded;
        } else {
            unset($data['image']);
        }

        $this->update($data, $id);

        return true;
    }

    /**
     * Delete a slider item and delete the image from the disk or where ever it is
     *
     * @return Boolean
     */
    public function destroy($id) {

        $sliderItem = $this->find($id);

        $sliderItemImage = $sliderItem->path;

        Storage::delete($sliderItemImage);

        return $this->model->destroy($id);
    }
}