<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;

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
     * To hold the catalog repository instance
     */
    protected $catalogRule;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CatalogRule $catalogRule)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->category = $category;
        $this->product = $product;
        $this->catalogRule = $catalogRule;
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
            'priority' => 'required|numeric',
            'criteria' => 'required',
            'all_conditions' => 'required|array',
            'apply' => 'required|numeric|min:0|max:3'
        ]);

        $data = request()->all();
        $data['status'] = 1;

        dd($data);

        $catalogRule = $this->catalogRule->create($data);
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