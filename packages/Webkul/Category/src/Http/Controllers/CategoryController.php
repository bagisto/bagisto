<?php

namespace Webkul\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Category\Models\CategoryTranslation;
use Illuminate\Support\Facades\Event;

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

        if (strtolower(request()->input('name')) == 'root') {
            $categoryTransalation = new CategoryTranslation();

            $result = $categoryTransalation->where('name', request()->input('name'))->get();

            if(count($result) > 0) {
                session()->flash('error', trans('admin::app.response.create-root-failure'));

                return redirect()->back();
            }
        }

        $category = $this->category->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category']));

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
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Category']));
                }
            }],
            $locale . '.name' => 'required',
            'image.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);

        $this->category->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category']));

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

        if(strtolower($this->category->find($id)->name) == "root") {
            session()->flash('warning', trans('admin::app.response.delete-category-root', ['name' => 'Category']));
        } else {
            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Category']));

            $this->category->delete($id);

            Event::fire('catalog.category.delete.after', $id);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy() {
        $suppressFlash = false;

        if (request()->isMethod('delete') || request()->isMethod('post')) {
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