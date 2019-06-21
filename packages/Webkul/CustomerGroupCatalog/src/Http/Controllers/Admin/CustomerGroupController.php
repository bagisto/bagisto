<?php

namespace Webkul\CustomerGroupCatalog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\CustomerGroupCatalog\Repositories\CategoryRepository;

/**
 * Customer Group controlller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerGroupController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
    */
    protected $_config;

    /**
     * CustomerGroupRepository object
     *
     * @var object
    */
    protected $customerGroupRepository;

    /**
     * ProductRepository object
     *
     * @var object
    */
    protected $productRepository;

    /**
     * CategoryRepository object
     *
     * @var object
    */
    protected $categoryRepository;

     /**
     * Create a new controller instance.
     *
     * @param \Webkul\Customer\Repositories\CustomerGroupRepository $customerGroupRepositor;
     * @param \Webkul\Product\Repositories\ProductRepository        $productRepository;
     * @param \Webkul\Category\Repositories\CategoryRepository      $categoryRepository;
     * @return void
     */
    public function __construct(
        CustomerGroupRepository $customerGroupRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->_config = request('_config');

        $this->customerGroupRepository = $customerGroupRepository;

        $this->productRepository = $productRepository;

        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Search for catalog
     *
     * @return \Illuminate\Http\Response
    */
    public function search()
    {
        $results = [];

        if (request()->input('type') == 'products') {
            foreach ($this->productRepository->searchProductByAttribute(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'name' => $row->name,
                    ];
            }
        } else {
            foreach ($this->categoryRepository->search(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->id,
                        'name' => $row->name,
                    ];
            }
        }

        return response()->json($results);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'string|required',
        ]);

        $data = request()->all();

        $data['is_user_defined'] = 1;

        $this->customerGroupRepository->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Customer Group']));

        return redirect()->route($this->_config['redirect']);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name' => 'string|required',
        ]);

        $this->customerGroupRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Customer Group']));

        return redirect()->route($this->_config['redirect']);
    }
}