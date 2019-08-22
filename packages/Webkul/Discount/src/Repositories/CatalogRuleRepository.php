<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Discount\Repositories\CatalogRuleChannelsRepository as CatalogRuleChannels;
use Webkul\Discount\Repositories\CatalogRuleCustomerGroupsRepository as CatalogRuleCustomerGroups;
use Illuminate\Container\Container as App;

/**
 * CatalogRuleReposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleRepository extends Repository
{
    /**
     * Will hold catalogRuleChannelsRepository instance
     */
    protected $catalogRuleChannels;

    /**
     * Will hold catalogRuleCustomerGroupsRepository instance
     */
    protected $catalogRuleCustomerGroups;

    /**
     * @param CatalogRuleChannels $catalogRuleChannels
     * @param CatalogRuleCustomerGroups $catalogRuleCustomerGroups
     * @param App $app
     */
    public function __construct(CatalogRuleChannels $catalogRuleChannels, CatalogRuleCustomerGroups $catalogRuleCustomerGroups, App $app)
    {
        $this->catalogRuleChannels = $catalogRuleChannels;

        $this->catalogRuleCustomerGroups = $catalogRuleCustomerGroups;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRule';
    }

    /**
     * To sync the customer groups related records
     *
     * @param Array $newCustomerGroups
     * @param CatalogRule $catalogRule
     *
     * @return Boolean
     */
    public function CustomerGroupSync($newCustomerGroups, $catalogRule)
    {
        $oldCustomerGroups = array();
        foreach ($catalogRule->customer_groups as $oldCustomerGroup) {
            array_push($oldCustomerGroups, ['id' => $oldCustomerGroup->id, 'customer_group_id' => $oldCustomerGroup->customer_group_id]);
        }

        foreach ($oldCustomerGroups as $key => $oldCustomerGroup) {
            $found = 0;
            foreach($newCustomerGroups as $newCustomerGroup) {
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
            $data['catalog_rule_id'] = $catalogRule->id;

            $this->catalogRuleCustomerGroups->create($data);
        }

        return true;
    }

    /**
     * To sync the channels related records
     *
     * @param Array $newChannels
     * @param CatalogRule $catalogRule
     *
     * @return Boolean
     */
    public function ChannelSync($newChannels, $catalogRule)
    {
        $oldChannels = array();
        foreach ($catalogRule->channels as $oldChannel) {
            array_push($oldChannels, ['id' => $oldChannel->id, 'channel_id' => $oldChannel->channel_id]);
        }

        foreach ($oldChannels as $key => $oldChannel) {
            $found = 0;
            foreach($newChannels as $newChannel) {
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
            $data['catalog_rule_id'] = $catalogRule->id;
            $this->catalogRuleChannels->create($data);
        }

        return true;
    }
}