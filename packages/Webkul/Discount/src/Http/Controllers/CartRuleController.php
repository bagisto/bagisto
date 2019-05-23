<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Routing\Controller;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleRepository;
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

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product, CatalogRuleRepository $cartRule, Cart $cart)
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
        return view($this->_config['view'])->with('cart_rule', [$this->appliedConfig, $this->fetchOptionableAttributes(), $this->getStatesAndCountries()]);
    }

    public function store()
    {
        $data = request()->all();
        unset($data['_token']);

        $channels = $data['channels'];
        unset($data['channels']);

        $customer_groups = $data['customer_groups'];
        unset($data['customer_groups']);
        unset($data['criteria']);

        $labels = $data['label'];
        unset($data['label']);
        unset($data['cart_attributes']);
        unset($data['attributes']);

        $data['conditions'] = $data['all_conditions'];
        unset($data['all_conditions']);

        $data['disc_amount'] = 1;

        if (isset($data['disc_amount'])) {
            $data['actions'] = [
                'action_type' => $data['action_type'],
                'disc_amount' => $data['disc_amount'],
                'disc_thresold' => $data['disc_threshold']
            ];
        }

        $data['actions'] = json_encode($data['actions']);
        $data['conditions'] = json_encode($data['conditions']);

        // dd($data);

        $r = $this->cartRule->create($data);

        if ($r) {
            dd('true');
        } else {
            dd('false');
        }
        // $cartRule = $this->cartRule->create(request()->all());
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
