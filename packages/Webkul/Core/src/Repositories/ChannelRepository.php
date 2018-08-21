<?php 

namespace Webkul\Core\Repositories;
 
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
        return 'Webkul\Core\Models\Channel';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $channel = $this->model->create($data);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

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

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        return $channel;
    }
}