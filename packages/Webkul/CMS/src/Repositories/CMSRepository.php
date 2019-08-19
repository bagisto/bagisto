<?php

namespace Webkul\CMS\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container as App;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Webkul\Core\Repositories\LocaleRepository as Locale;

/**
 * CMS Reposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CMSRepository extends Repository
{
    /**
     * To hold the channel reposotry instance
     */
    protected $channel;

    /**
     * To hold the locale reposotry instance
     */
    protected $locale;

    public function __construct(Channel $channel, Locale $locale, App $app)
    {
        $this->channel = $channel;

        $this->locale = $locale;

        parent::__construct($app);
    }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CMS\Contracts\CMS';
    }

    public function create(array $data)
    {
        $channel = $this->channel->findOneByField('code', $data['channel']);

        $locale = $this->locale->findOneByField('code', $data['locale']);

        $data['channel_id'] = $channel->id;
        $data['locale_id'] = $locale->id;

        $result = $this->model->create($data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}