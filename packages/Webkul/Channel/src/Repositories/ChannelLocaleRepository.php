<?php 

namespace Webkul\Channel\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Channel Locale Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ChannelLocaleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Channel\Models\ChannelLocale';
    }
}