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

        foreach ($data['locales'] as $group) {

        }

        foreach ($data['currencies'] as $group) {

        }

        return $channel;
    }
}