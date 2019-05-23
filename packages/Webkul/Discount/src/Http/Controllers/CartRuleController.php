<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Checkout\Repositories\CartRepository as Cart;

/**
 * Cart Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleController extends Controller
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
     * Property for Cart rule application
     */
    protected $appliedConfig;

    /**
     * To hold the Cart repository instance
     */
    protected $cartRule;

    /**
     * To hold the cart repository instance
     */
    protected $cart;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CartRule $cartRule, Cart $cart)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->category = $category;
        $this->product = $product;
        $this->CartRule = $cartRule;
        $this->cart = $cart;
        $this->appliedConfig = config('pricerules.cart');
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        // dd($this->appliedConfig);
        return view($this->_config['view'])->with('cart_rule', [$this->appliedConfig, $this->fetchOptionableAttributes(), $this->getStatesAndCountries()]);
    }

    public function store()
    {
        dd(request()->all());
        $this->validate(request(), [
            'name' => 'required|string',
            'description' => 'string',
            'customer_groups' => 'required|array',
            'channels' => 'required|array',
            'starts_from' => 'required|date_format:Y-m-d H:i:s',
            'ends_till' => 'required|date_format:Y-m-d H:i:s',
            'apply' => 'numeric|min:1|max:4'
        ]);

        $cartRule = $this->cartRule->create(request()->all());
    }

    public function getStatesAndCountries()
    {
        $countries = core()->countries()->toArray();
        $states = core()->groupedStatesByCountries();

        return [
            'countries' => $countries,
            'states' => $states
        ];
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
