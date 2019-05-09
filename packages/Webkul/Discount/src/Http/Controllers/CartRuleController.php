<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;

/**
 * Cart Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleController extends Controller
{
    protected $_config;
    protected $attribute;
    protected $attributeFamily;
    protected $category;
    protected $product;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily, Category $category, Product $product)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->category = $category;
        $this->product = $product;
    }

    public function index()
    {
        return view($this->_config['view'])->with('criteria', [$this->attribute->getNameAndId(), $this->category->getNameAndId()]);
    }

    public function create()
    {
        return view($this->_config['view']);
    }
}
