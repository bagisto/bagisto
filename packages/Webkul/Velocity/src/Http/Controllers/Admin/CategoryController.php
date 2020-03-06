<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Velocity\Repositories\CategoryRepository as VelocityCategoryRepository;

class CategoryController extends Controller
{
    /**
     * Category Repository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
    */
    protected $categoryRepository;

    /**
     * VelocityCategory Repository object
     *
     * @var \Webkul\Velocity\Repositories\CategoryRepository
    */
    protected $velocityCategoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository;
     * @param  \Webkul\Velocity\Repositories\CategoryRepository  $velocityCategory;
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        VelocityCategoryRepository $velocityCategoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;

        $this->velocityCategoryRepository = $velocityCategoryRepository;

        $this->_config = request('_config');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! core()->getConfigData('velocity.configuration.general.status')) {
            session()->flash('error', trans('velocity::app.admin.system.velocity.error-module-inactive'));

            return redirect()->route('admin.configuration.index', ['slug' => 'velocity', 'slug2' => 'configuration']);
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->velocityCategoryRepository->getChannelCategories();

        return view($this->_config['view'], compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->velocityCategoryRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category Menu']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        $velocityCategory = $this->velocityCategoryRepository->findOrFail($id);

        return view($this->_config['view'], compact('categories', 'velocityCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $velocityCategory = $this->velocityCategoryRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category Menu']));

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
        $velocityCategory = $this->velocityCategoryRepository->findOrFail($id);

        try {
            $this->velocityCategoryRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Category Menu']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Category Menu']));
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
        $menuIds = explode(',', request()->input('indexes'));

        foreach ($menuIds as $menuId) {
            $velocityCategory = $this->velocityCategoryRepository->find($menuId);

            if (isset($velocityCategory)) {
                $this->velocityCategoryRepository->delete($menuId);
            }
        }

        session()->flash('success', trans('velocity::app.admin.category.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}