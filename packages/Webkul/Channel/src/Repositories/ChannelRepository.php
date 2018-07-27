<?php 

namespace Webkul\Channel\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Channel Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ChannelRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Channel\Models\Channel';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $channel = $this->model->create($data);

        foreach ($data['locales'] as $locale) {
            $channel->locales()->attach($locale);
        }

        foreach ($data['currencies'] as $currency) {
            $channel->currencies()->attach($currency);
        }

        return $channel;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $channel = $this->findOrFail($id);

        $channel->update($data);

        $previousLocaleIds = $channel->locales()->pluck('id');

        foreach ($data['locales'] as $locale) {
            if(is_numeric($index = $previousLocaleIds->search($locale))) {
                $previousLocaleIds->forget($index);
            } else {
                $channel->locales()->attach($locale);
            }
        }

        if($previousLocaleIds->count()) {
            $channel->locales()->detach($previousLocaleIds);
        }

        $previousCurrencyIds = $channel->currencies()->pluck('id');
        foreach ($data['currencies'] as $currency) {
            if(is_numeric($index = $previousCurrencyIds->search($currency))) {
                $previousCurrencyIds->forget($index);
            } else {
                $channel->currencies()->attach($currency);
            }
        }

        if($previousCurrencyIds->count()) {
            $channel->currencies()->detach($previousCurrencyIds);
        }

        return $channel;
    }
}