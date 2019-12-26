<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Velocity\Repositories\CategoryRepository as VelocityCategory;

/**
 * Category Controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com> @vivek
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryController extends Controller
{
    /**
     * Category Repository object
     *
     * @var object
    */
    protected $category;

    /**
     * VelocityCategory Repository object
     *
     * @var object
    */
    protected $velocityCategory;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Category\Repositories\CategoryRepository $category;
     * @param \Webkul\Velocity\Repositories\CategoryRepository $velocityCategory;
     * @return void
     */
    public function __construct(
        Category $category,
        VelocityCategory $velocityCategory
    )
    {
        $this->category = $category;

        $this->velocityCategory = $velocityCategory;

        $this->_config = request('_config');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!core()->getConfigData('velocity.configuration.general.status')) {
            session()->flash('error', trans('velocity::app.admin.system.velocity.error-module-inactive'));

            return redirect()->route('admin.configuration.index', ['slug' => 'velocity', 'slug2' => 'configuration']);
        }



        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->velocityCategory->getChannelCategories();

        return view($this->_config['view'], compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->velocityCategory->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category Menu']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->category->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        $velocityCategory = $this->velocityCategory->findOrFail($id);

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
        $velocityCategory = $this->velocityCategory->update(request()->all(), $id);

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
        $velocityCategory = $this->velocityCategory->findOrFail($id);

        try {
            $this->velocityCategory->delete($id);

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
     * @return response
     */
    public function massDestroy()
    {
        $menuIds = explode(',', request()->input('indexes'));

        foreach ($menuIds as $menuId) {

            $velocityCategory = $this->velocityCategory->find($menuId);

            if (isset($velocityCategory)) {
                $this->velocityCategory->delete($menuId);
            }
        }

        session()->flash('success', trans('velocity::app.admin.category.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}