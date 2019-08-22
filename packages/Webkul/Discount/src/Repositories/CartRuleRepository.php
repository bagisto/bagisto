<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Discount\Repositories\CartRuleChannelsRepository as CartRuleChannels;
use Webkul\Discount\Repositories\CartRuleCustomerGroupsRepository as CartRuleCustomerGroups;
use Webkul\Discount\Repositories\CartRuleCouponsRepository as CartRuleCoupons;
use Webkul\Discount\Repositories\CartRuleLabelsRepository as CartRuleLabels;
use Illuminate\Container\Container as App;

/**
 * CartRuleReposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleRepository extends Repository
{
    /**
     * Will hold cartRuleChannelsRepository instance
     */
    protected $cartRuleChannels;

    /**
     * Will hold cartRuleCustomerGroupsRepository instance
     */
    protected $cartRuleCustomerGroups;

    /**
     * Will hold cartRuleCoupons instance
     */
    protected $cartRuleCoupons;

    /**
     * Will hold cartRuleCustomerGroupsRepository instance
     */
    protected $cartRuleLabels;

    /**
     * @param CartRuleChannels $cartRuleChannels
     * @param CartRuleCustomerGroups $cartRuleCustomerGroups
     * @param CartRuleCoupons $cartRuleCoupons
     * @param
     */
    public function __construct(
        CartRuleChannels $cartRuleChannels,
        CartRuleCustomerGroups $cartRuleCustomerGroups,
        CartRuleCoupons $cartRuleCoupons,
        CartRuleLabels $cartRuleLabels,
        App $app
        ) {
        $this->cartRuleChannels = $cartRuleChannels;
        $this->cartRuleCustomerGroups = $cartRuleCustomerGroups;
        $this->cartRuleCoupons = $cartRuleCoupons;
        $this->cartRuleLabel = $cartRuleLabels;

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
                $this->cartRuleCustomerGroups->find($oldCustomerGroup['id'])->delete();
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
                $this->cartRuleChannels->find($oldChannel['id'])->delete();
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

    /**
     * To sync the labels associated with the cart rule
     */
    public function LabelSync($labels, $cartRule)
    {
        foreach ($labels as $channelCode => $value) {
            $localeCode = array_keys($value)[0];
            $localeValue = array_values($value)[0];

            $updated = 0;
            foreach ($cartRule->labels as $label) {
                if ($label->channel->code == $channelCode && $label->locale->code == $localeCode) {
                    $label->update([
                        'label' => $localeValue
                    ]);

                    $updated = 1;
                }
            }

            if ($updated == 0) {
                foreach (core()->getAllChannels() as $channel) {
                    if ($channel->code == $channelCode) {
                        $newLabel['channel_id'] = $channel->id;
                    }

                    foreach($channel->locales as $locale) {
                        if ($localeCode == $locale->code) {
                            $newLabel['locale_id'] = $locale->id;
                            $newLabel['label'] = $localeValue;
                            $newLabel['cart_rule_id'] = $cartRule->id;

                            $ruleLabelCreated = $this->CartRuleLabels->create($newLabel);
                        }
                    }
                }
            }

            $updated = 0;
        }

        return true;
    }
}