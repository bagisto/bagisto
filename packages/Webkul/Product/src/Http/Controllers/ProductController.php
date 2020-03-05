<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Product\Helpers\ProductType;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * ProductDownloadableLinkRepository object
     *
     * @var \Webkul\Product\Repositories\ProductDownloadableLinkRepository
     */
    protected $productDownloadableLinkRepository;

    /**
     * ProductDownloadableSampleRepository object
     *
     * @var \Webkul\Product\Repositories\ProductDownloadableSampleRepository
     */
    protected $productDownloadableSampleRepository;

    /**
     * AttributeFamilyRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;

    /**
     * InventorySourceRepository object
     *
     * @var \Webkul\Inventory\Repositories\InventorySourceRepository
     */
    protected $inventorySourceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableLinkRepository  $productDownloadableLinkRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableSampleRepository  $productDownloadableSampleRepository
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamilyRepository
     * @param  \Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySourceRepository
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        InventorySourceRepository $inventorySourceRepository
    )
    {
        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;

        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->inventorySourceRepository = $inventorySourceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $families = $this->attributeFamilyRepository->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
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
        if (! request()->get('family')
            && ProductType::hasVariants(request()->input('type'))
            && request()->input('sku') != ''
        ) {
            return redirect(url()->current() . '?type=' . request()->input('type') . '&family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (ProductType::hasVariants(request()->input('type'))
            && (! request()->has('super_attributes')
            || ! count(request()->get('super_attributes')))
        ) {
            session()->flash('error', trans('admin::app.catalog.products.configurable-error'));

            return back();
        }

        $this->validate(request(), [
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug],
        ]);

        $product = $this->productRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = $this->productRepository->with(['variants', 'variants.inventories'])->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTree();

        $inventorySources = $this->inventorySourceRepository->all();

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $product = $this->productRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Uploads downloadable file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadLink($id)
    {
        return response()->json(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Uploads downloadable sample file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadSample($id)
    {
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findOrFail($id);

        try {
            $this->productRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Product']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Mass Delete the products
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $product = $this->productRepository->find($productId);

            if (isset($product)) {
                $this->productRepository->delete($productId);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if (! $data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $this->productRepository->update([
                'channel' => null,
                'locale'  => null,
                'status'  => $data['update-options'],
            ], $productId);
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To be manually invoked when data is seeded into products
     * 
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        Event::dispatch('products.datagrid.sync', true);

        return redirect()->route('admin.catalog.products.index');
    }

    /**
     * Result of search product.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function productLinkSearch()
    {
        if (request()->ajax()) {
            $results = [];

            foreach ($this->productRepository->searchProductByAttribute(request()->input('query')) as $row) {
                $results[] = [
                    'id'   => $row->product_id,
                    'sku'  => $row->sku,
                    'name' => $row->name,
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

     /**
     * Download image or file
     *
     * @param  int  $productId
     * @param  int  $attributeId
     * @return \Illuminate\Http\Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValue->findOneWhere([
            'product_id'   => $productId,
            'attribute_id' => $attributeId,
        ]);

        return Storage::download($productAttribute['text_value']);
    }

    /**
     * Search simple products
     *
     * @return \Illuminate\Http\Response
     */
    public function searchSimpleProducts()
    {
        return response()->json(
            $this->productRepository->searchSimpleProducts(request()->input('query'))
        );
    }
}