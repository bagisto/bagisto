<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CatalogRuleChannelsRepository as CatalogRuleChannels;
use Webkul\Discount\Repositories\CatalogRuleCustomerGroupsRepository as CatalogRuleCustomerGroups;

/**
 * Catalog Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route
     */
    protected $_config;

    /**
     * Attribute $attribute
     */
    protected $attribute;

    /**
     * AttributeFamily $attributeFamily
     */
    protected $attributeFamily;

    /**
     * Category $category
     */
    protected $category;

    /**
     * Product $product
     */
    protected $product;

    /**
     * Property for catalog rule application
     */
    protected $appliedConfig;

    /**
     * Property for catalog rule application
     */
    protected $appliedConditions;

    /**
     * Property to hold Catalog Rule Channels Repository
     */
    protected $catalogRuleChannels;

    /**
     * Property to hold Catalog Rule Customer Groups Repository
     */
    protected $catalogRuleCustomerGroups;

    /**
     * To hold the catalog repository instance
     */
    protected $catalogRule;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CatalogRule $catalogRule, CatalogRuleChannels $catalogRuleChannels, CatalogRuleCustomerGroups $catalogRuleCustomerGroups)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->category = $category;
        $this->product = $product;
        $this->catalogRule = $catalogRule;
        $this->catalogRuleChannels = $catalogRuleChannels;
        $this->catalogRuleCustomerGroups = $catalogRuleCustomerGroups;
        $this->appliedConfig = config('pricerules.catalog');
        $this->appliedConditions = config('pricerules.conditions');
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        return view($this->_config['view'])->with('criteria', [$this->attribute->getPartial(), $this->category->getPartial(), $this->fetchOptionableAttributes(), $this->appliedConfig, $this->appliedConditions]);
    }

    public function store()
    {
        // dd(request()->all());

        $this->validate(request(), [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required',
            'channels' => 'required',
            'starts_from' => 'required|date',
            'ends_till' => 'required|date',
            'status' => 'required|boolean',
            'end_other_rules' => 'required|boolean',
            'priority' => 'required|numeric',
            'criteria' => 'required',
            'all_conditions' => 'required|array',
            'apply' => 'required|numeric|min:0|max:3',
            'disc_amount' => 'sometimes',
            'disc_percent' => 'sometimes',
        ]);

        $catalog_rule = request()->all();

        $catalog_rule_channels = array();
        $catalog_rule_customer_groups = array();

        $catalog_rule_channels = $catalog_rule['channels'];
        $catalog_rule_customer_groups = $catalog_rule['customer_groups'];
        unset($catalog_rule['channels']); unset($catalog_rule['customer_groups']);

        unset($catalog_rule['criteria']);

        $catalog_rule['conditions'] = $catalog_rule['all_conditions'];
        unset($catalog_rule['all_conditions']);

        if (isset($catalog_rule['disc_amt'])) {
            $catalog_rule['action_type'] = $catalog_rule['apply'];
            $catalog_rule['actions'] = [
                'action_type' => $catalog_rule['apply'],
                'disc_amt' => $catalog_rule['disc_amt']
            ];
        } else if (isset($catalog_rule['disc_percent'])) {
            $catalog_rule['action_type'] = $catalog_rule['apply'];
            $catalog_rule['actions'] = [
                'action_type' => $catalog_rule['apply'],
                'disc_percent' => $catalog_rule['disc_percent'],
            ];
        }

        unset($catalog_rule['apply']);
        unset($catalog_rule['attributes']);
        unset($catalog_rule['_token']);
        unset($catalog_rule['all_actions']);

        $catalog_rule['actions'] = json_encode($catalog_rule['actions']);
        $catalog_rule['conditions'] = json_encode($catalog_rule['conditions']);

        $catalogRule = $this->catalogRule->create($catalog_rule);

        foreach($catalog_rule_channels as $catalog_rule_channel) {
            $catalog_rule_channels['catalog_rule_id'] = $catalog_rule_channel;
            $catalog_rule_channels['channel_id'] = $catalog_rule_channel;
            $catalogRuleChannels = $this->catalogRuleChannels->create($catalog_rule_channels);
        }

        foreach ($catalog_rule_customer_groups as $catalog_rule_channel) {
            $catalog_rule_customer_groups['catalog_rule_id'] = $catalogRule['id'];
            $catalog_rule_customer_groups['customer_group_id'] = $catalog_rule_channel;
            $catalogRuleCustomerGroups = $this->catalogRuleCustomerGroups->create($catalog_rule_customer_groups);
        }

        if($catalogRule && $catalogRuleChannels && $catalogRuleCustomerGroups) {
            session()->flash('success', trans('admin::app.promotion.status.success'));
        } else {
            session()->flash('error', trans('admin::app.promotion.status.failed'));
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $catalog_rule = $this->catalogRule->find($id);
        $catalog_rule_channels = $this->catalogRuleChannels->findByField('catalog_rule_id', $id);
        $catalog_rule_customer_groups = $this->catalogRuleCustomerGroups->findByField('catalog_rule_id', $id);

        dd($catalog_rule, $catalog_rule_channels, $catalog_rule_customer_groups);
    }

    public function fetchOptionableAttributes()
    {
        $attributesWithOptions = array();

        foreach($this->attribute->all() as $attribute) {
            if (($attribute->type == 'select' || $attribute->type == 'multiselect')  && $attribute->code != 'tax_category_id') {
                $attributesWithOptions[$attribute->admin_name] = $attribute->options->toArray();
            }
        }

        return $attributesWithOptions;
    }
}