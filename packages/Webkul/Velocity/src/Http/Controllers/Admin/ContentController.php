<?php

namespace Webkul\Velocity\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Velocity\Repositories\ContentRepository;

/**
 * Content Controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com> @vivek
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ContentController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var object
    */
    protected $productRepository;

    /**
     * ContentRepository object
     *
     * @var object
    */
    protected $content;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Product\Repositories\ProductRepository $productRepository;
     * @param \Webkul\Velocity\Repositories\ContentRepository $content;
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ContentRepository $content
    )
    {
        $this->productRepository = $productRepository;

        $this->content = $content;

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
     * Search for catalog
     *
     * @return \Illuminate\Http\Response
    */
    public function search()
    {
        $results = [];

        $params = request()->input();

        if ( isset($params['query']) && $params['query'] ) {
            foreach ($this->productRepository->searchProductByAttribute(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'name' => $row->name,
                    ];
            }
        }
        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $params = request()->all();

        if ( isset($params['products']) ) {
            $params['products'] = json_encode($params['products']);
        }

        $this->content->create($params);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Content Page']));

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

        $content = $this->content->findOrFail($id);

        return view($this->_config['view'], compact('content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $params = request()->all();

        if ( isset($params['locale']) && isset($params[$params['locale']]['products']) ) {
            $params[$params['locale']]['products'] = json_encode($params[$params['locale']]['products']);
        }

        $content = $this->content->update($params, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Content']));

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
        $content = $this->content->findOrFail($id);

        try {
            $this->content->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Content']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Content']));
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
        $contentIds = explode(',', request()->input('indexes'));

        foreach ($contentIds as $contentId) {

            $content = $this->content->find($contentId);

            if (isset($content)) {
                $this->content->delete($contentId);
            }
        }

        session()->flash('success', trans('velocity::app.admin.contents.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}