<?php

namespace Webkul\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Category\Repositories\CategoryRepository as Category;

/**
 * Catalog category controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryController extends Controller
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
     * @var array
     */
    protected $category;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Category\Repositories\CategoryRepository  $category
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;

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
        $categories = $this->category->getCategoryTree(null, ['id']);

        return view($this->_config['view'], compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'slug' => ['required', 'unique:category_translations,slug', new \Webkul\Core\Contracts\Validations\Slug],
            'name' => 'required',
            'image.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        $category = $this->category->create(request()->all());

        session()->flash('success', 'Category created successfully.');

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
        $categories = $this->category->getCategoryTree($id);

        $category = $this->category->find($id);

        return view($this->_config['view'], compact('category', 'categories'));
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
        $locale = request()->get('locale') ?: app()->getLocale();

        $this->validate(request(), [
            $locale . '.slug' => ['required', new \Webkul\Core\Contracts\Validations\Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->category->isSlugUnique($id, $value)) {
                    $fail('The :attribute has already been taken.');
                }
            }],
            $locale . '.name' => 'required',
            'image.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        $this->category->update(request()->all(), $id);

        session()->flash('success', 'Category updated successfully.');

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
        Event::fire('catalog.category.delete.before', $id);

        $this->category->delete($id);

        Event::fire('catalog.category.delete.after', $id);

        session()->flash('success', 'Category deleted successfully.');

        return redirect()->back();
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy() {
        $suppressFlash = false;

        if (request()->isMethod('delete')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    Event::fire('catalog.category.delete.before', $value);

                    $this->category->delete($value);

                    Event::fire('catalog.category.delete.after', $value);
                } catch(\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success'));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Attribute Family']));

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}