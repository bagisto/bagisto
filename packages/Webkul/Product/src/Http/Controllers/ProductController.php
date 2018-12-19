<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductGridRepository as ProductGrid;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Event;

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
        ProductGrid $productGrid)
    {
        $this->middleware('admin');

        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->productGrid = $productGrid;

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

        if($familyId = request()->get('family')) {
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
        //before store of the product
        // Event::fire('product.save.before', false);

        if(!request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if(request()->input('type') == 'configurable' && (!request()->has('super_attributes') || !count(request()->get('super_attributes')))) {
            session()->flash('error', 'Please select atleast one configurable attribute.');

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        //after store of the product
        Event::fire('product.save.after', $product);

        session()->flash('success', 'Product created successfully.');

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
        // before update of product
        // Event::fire('product.update.before', $id);

        $product = $this->product->update(request()->all(), $id);

        //after update of product
        Event::fire('product.update.after', $product);

        session()->flash('success', 'Product updated successfully.');

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
        Event::fire('product.delete.before', $id);

        $this->product->delete($id);

        //before update of product
        // Event::fire('product.delete.after', $id);

        session()->flash('success', 'Product deleted successfully.');

        return redirect()->back();
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy() {
        $data = request()->all();
        $productIds = explode(',', $data['indexes']);

        if(count($productIds)) {
            foreach($productIds as $productId) {
                $product = $this->product->find($productId);

                if(!is_null($product)) {
                    $product->delete();
                }
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate() {
        $data = request()->all();
        $attribute = 'status';

        $productIds = explode(',', $data['indexes']);

        if(!isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if(count($productIds)) {
            foreach($productIds as $productId) {
                $product = $this->product->find($productId);

                if($data['update-options'] == 0 && $data['selected-option-text'] == 'In Active') {
                    $result = $this->product->updateAttribute($product, $attribute, $data['update-options']);

                    if($result)
                        Event::fire('product.update.after', $product);
                } else if($data['update-options'] == 1 && $data['selected-option-text'] == 'Active') {
                    $result = $this->product->updateAttribute($product, $attribute, $data['update-options']);

                    if($result)
                        Event::fire('product.update.after', $product);
                }
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /*
     * To be manually invoked when data is seeded into products
     */
    public function sync() {
        Event::fire('products.datagrid.sync', true);

        return redirect()->route('admin.catalog.products.index');
    }
}