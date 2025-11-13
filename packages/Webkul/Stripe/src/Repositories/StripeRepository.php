<?php

namespace Webkul\Stripe\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;

class StripeRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Stripe\Models\Stripe';
    }

    /**
     * Get All Channels
     *
     * @return array
     */
    public function getAllChannels()
    {
        $allChannels = core()->getAllChannels();

        foreach ($allChannels as $channel) {
            $channels[$channel->id] = $channel->name;
        }

        return $channels;
    }
}
