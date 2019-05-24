<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Discount\Repositories\CartRuleChannelsRepository as CartRuleChannels;
use Webkul\Discount\Repositories\CartRuleCustomerGroupsRepository as CartRuleCustomerGroups;
use Illuminate\Container\Container as App;

/**
 * Cart Rule Reposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleRepository extends Repository
{
    protected $cartRuleChannels;

    protected $cartRuleCustomerGroups;

    /**
     *
     */
    public function __construct(CartRuleChannels $cartRuleChannels, CartRuleCustomerGroups $cartRuleCustomerGroups, App $app)
    {
        $this->cartRuleChannels = $cartRuleChannels;
        $this->cartRuleCustomerGroups = $cartRuleCustomerGroups;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CartRule';
    }

    /**
     * To sync the customer groups related records
     */
    public function CustomerGroupSync($newCustomerGroups, $cartRule)
    {
        $oldCustomerGroups = array();
        foreach ($cartRule->customer_groups as $oldCustomerGroup) {
            array_push($oldCustomerGroups, ['id' => $oldCustomerGroup->id, 'customer_group_id' => $oldCustomerGroup->customer_group_id]);
        }

        foreach ($oldCustomerGroups as $key => $oldCustomerGroup) {
            $found = 0;
            foreach ($newCustomerGroups as $newCustomerGroup) {
                if ($oldCustomerGroup['customer_group_id'] == $newCustomerGroup) {
                    $found = 1;
                }
            }

            if ($found == 0) {
                $this->catalogRuleCustomerGroups->find($oldCustomerGroup['id'])->delete();
            } else {
                $found = 0;
            }
        }

        //unset the commons
        if (count($newCustomerGroups) && count($oldCustomerGroups)) {
            foreach ($oldCustomerGroups as $oldCustomerGroup) {
                $found = 0;
                foreach ($newCustomerGroups as $key => $newCustomerGroup) {
                    if ($oldCustomerGroup['customer_group_id'] == $newCustomerGroup) {
                        unset($newCustomerGroups[$key]);
                    }
                }
            }
        }

        //create the left ones
        foreach ($newCustomerGroups as $newCustomerGroup) {
            $data['customer_group_id'] = $newCustomerGroup;
            $data['cart_rule_id'] = $cartRule->id;

            $this->cartRuleCustomerGroups->create($data);
        }

        return true;
    }

    /**
     * To sync the channels related records
     */
    public function ChannelSync($newChannels, $cartRule)
    {
        $oldChannels = array();
        foreach ($cartRule->channels as $oldChannel) {
            array_push($oldChannels, ['id' => $oldChannel->id, 'channel_id' => $oldChannel->channel_id]);
        }

        foreach ($oldChannels as $key => $oldChannel) {
            $found = 0;
            foreach ($newChannels as $newChannel) {
                if ($oldChannel['channel_id'] == $newChannel) {
                    $found = 1;
                }
            }

            if ($found == 0) {
                $this->catalogRuleChannels->find($oldChannel['id'])->delete();
            } else {
                $found = 0;
            }
        }

        //unset the commons
        if (count($newChannels) && count($oldChannels)) {
            foreach ($oldChannels as $oldChannel) {
                $found = 0;
                foreach ($newChannels as $key => $newChannel) {
                    if ($oldChannel['channel_id'] == $newChannel) {
                        unset($newChannels[$key]);
                    }
                }
            }
        }

        //create the left ones
        foreach ($newChannels as $newChannel) {
            $data['channel_id'] = $newChannel;
            $data['cart_rule_id'] = $cartRule->id;
            $this->cartRuleChannels->create($data);
        }

        return true;
    }
}