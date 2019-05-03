<?php

namespace Webkul\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;

/**
 * Catalog Rule controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleController extends Controller
{
    protected $_config;
    protected $attribute;
    protected $attributeFamily;

    public function __construct(Attribute $attribute, AttributeFamily $attributeFamily)
    {
        $this->_config = request('_config');
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
    }

    public function index()
    {
        return view($this->_config['view']);
    }

    public function create()
    {
        $ruleCriterias = [
            'cart' => [
                0 => 'sub_total',
                1 => 'shipping_total',
                2 => 'grand_total'
            ],

            'attribute' => [
                0 => 'attribute',
                1 => 'attribute_groups',
                2 => 'attribute_familes'
            ]
        ];

        $arr = array();

        foreach($ruleCriterias as $key => $ruleCriteria) {
            array_push($arr, $ruleCriteria);
        }

        $arr;

        return view($this->_config['view'])->with('criteria', [$ruleCriterias, $this->attribute->getNameAndId(), $this->attributeFamily->getNameAndId()]);
    }

    public function store()
    {
        dd(request()->all());
    }
}