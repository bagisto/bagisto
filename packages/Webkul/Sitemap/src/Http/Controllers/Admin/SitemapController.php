<?php

namespace Webkul\Sitemap\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\DataGrids\SitemapDataGrid;
use Webkul\Sitemap\Repositories\SitemapRepository;

class SitemapController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public SitemapRepository $sitemapRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SitemapDataGrid::class)->toJson();
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
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'file_name' => 'required',
            'path'      => 'required',
        ]);

        Event::dispatch('marketing.sitemaps.create.before');

        $sitemap = $this->sitemapRepository->create(request()->all());

        Event::dispatch('marketing.sitemaps.create.after', $sitemap);

        session()->flash('success', trans('admin::app.marketing.sitemaps.create-success'));

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
        $sitemap = $this->sitemapRepository->findOrFail($id);

        return view($this->_config['view'], compact('sitemap'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'file_name' => 'required',
            'path'      => 'required',
        ]);

        Event::dispatch('marketing.sitemaps.update.before', $id);

        $sitemap = $this->sitemapRepository->update(request()->all(), $id);

        Event::dispatch('marketing.sitemaps.update.after', $sitemap);

        session()->flash('success', trans('admin::app.marketing.sitemaps.update-success'));

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
        $sitemap = $this->sitemapRepository->findOrFail($id);

        Storage::delete($sitemap->path . '/' . $sitemap->file_name);

        try {
            Event::dispatch('marketing.sitemaps.delete.before', $id);

            $this->sitemapRepository->delete($id);

            Event::dispatch('marketing.sitemaps.delete.after', $id);

            return response()->json(['message' => trans('admin::app.marketing.sitemaps.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Sitemap'])], 500);
    }
}
