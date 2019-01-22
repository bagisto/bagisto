<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductGridRepository as ProductGrid;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\Product\Repositories\ProductAttributeValueRepository as ProductAttributeValue;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * CategoryRepository object
     *
     * @var array
     */
    protected $category;

    /**
     * InventorySourceRepository object
     *
     * @var array
     */
    protected $inventorySource;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductGrid Repository object
     *
     * @var array
     */
    protected $productGrid;

    /**
     * ProductFlat Repository Object
     *
     * @vatr array
     */
    protected $productFlat;
    protected $productAttributeValue;
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @param  Webkul\Category\Repositories\CategoryRepository          $category
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySource
     * @param  Webkul\Product\Repositories\ProductRepository            $product
     * @return void
     */
    public function __construct(
        AttributeFamily $attributeFamily,
        Category $category,
        InventorySource $inventorySource,
        Product $product,
        ProductGrid $productGrid,
        ProductFlat $productFlat,
        ProductAttributeValue $productAttributeValue)
    {
        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->productGrid = $productGrid;

        $this->productFlat = $productFlat;

        $this->productAttributeValue = $productAttributeValue;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $families = $this->attributeFamily->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (!request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (request()->input('type') == 'configurable' && (! request()->has('super_attributes') || ! count(request()->get('super_attributes')))) {
            session()->flash('error', 'Please select atleast one configurable attribute.');

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->with(['variants'])->find($id);

        $categories = $this->category->getCategoryTree();

        $inventorySources = $this->inventorySource->all();

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $product = $this->product->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->product->delete($id);

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));

        return redirect()->back();
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $this->product->delete($productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (!isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if (!$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $this->product->update([
                'channel' => null,
                'locale' => null,
                'status' => $data['update-options']
            ], $productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /*
     * To be manually invoked when data is seeded into products
     */
    public function sync()
    {
        Event::fire('products.datagrid.sync', true);

        return redirect()->route('admin.catalog.products.index');
    }

    /**
     * Testing for the product flat sync method on product creation and updation
     */
    public function testProductFlat() {
        $product = $this->product->find(4);
        $productAttributes = $product->attribute_family->custom_attributes;
        $allLocales = core()->getAllLocales();
        $productsFlat = array();
        $attributeNames = array();
        $channelLocaleMap = array();
        $nonDependentAttributes = array();
        $localeDependentAttributes = array();
        $channelDependentAttributes = array();
        $channelLocaleDependentAttributes = array();

        foreach($productAttributes as $key => $productAttribute) {
            $attributeNames[$key] = [
                'id' => $productAttribute->id,
                'code' => $productAttribute->code,
                'value_per_locale' => $productAttribute->value_per_locale,
                'value_per_channel' => $productAttribute->value_per_channel,
            ];
        }

        foreach($productAttributes as $productAttribute) {
            if($productAttribute->value_per_channel) {
                if($productAttribute->value_per_locale) {
                    array_push($channelLocaleDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
                } else {
                    array_push($channelDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
                }
            } else if($productAttribute->value_per_locale && !$productAttribute->value_per_channel) {
                array_push($localeDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
            } else {
                array_push($nonDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
            }
        }

        foreach(core()->getAllChannels() as $channel) {
            $dummy = [
                'product_id' => $product->id,
                'channel' => $channel->code,
                'locale' => null,
                'data' => $channelDependentAttributes
            ];

            array_push($channelLocaleMap, $dummy);

            $dummy = [];

            foreach($channel->locales as $locale) {
                $dummy = [
                    'product_id' => $product->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'data' => $channelLocaleDependentAttributes
                ];

                array_push($channelLocaleMap, $dummy);

                $dummy = [];
            }
        }

        $dummy = [
            'product_id' => $product->id,
            'channel' => null,
            'locale' => null,
            'data' => $nonDependentAttributes
        ];

        array_push($channelLocaleMap, $dummy);

        $dummy = [];

        foreach($allLocales as $key => $allLocale) {
            $dummy = [
                'product_id' => $product->id,
                'channel' => null,
                'locale' => $allLocale->code,
                'data' => $localeDependentAttributes
            ];

            array_push($channelLocaleMap, $dummy);

            $dummy = [];
        }

        $productFlatObjects = $channelLocaleMap;

        foreach($productAttributes as $productAttribute) {
            foreach($productFlatObjects as $flatKey => $productFlatObject) {
                foreach($productFlatObject['data'] as $key => $value) {
                    if($productAttribute->code == $value['code']) {
                        $valueOf = $this->productAttributeValue->findOneWhere([
                            'product_id' => $product->id,
                            'channel' => $productFlatObject['channel'],
                            'locale' => $productFlatObject['locale'],
                            'attribute_id' => $productAttribute->id
                        ]);

                        if($valueOf != null) {
                            $productAttributeColumn = $this->productAttributeValue->model()::$attributeTypeFields[$productAttribute->type];

                            $valueOf = $valueOf->{$productAttributeColumn};

                            $productFlatObjects[$flatKey][$productAttribute->code] = $valueOf;
                        } else {
                            $productFlatObjects[$flatKey][$productAttribute->code] = 'null';
                        }

                    }
                }
            }
        }

        dd($productFlatObjects);
    }
}