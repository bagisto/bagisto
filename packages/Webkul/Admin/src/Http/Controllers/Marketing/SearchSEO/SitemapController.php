<?php

namespace Webkul\Admin\Http\Controllers\Marketing\SearchSEO;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Marketing\SearchSEO\SitemapDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sitemap\Jobs\ProcessSitemap;
use Webkul\Sitemap\Repositories\SitemapRepository;

class SitemapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public SitemapRepository $sitemapRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(SitemapDataGrid::class)->process();
        }

        return view('admin::marketing.search-seo.sitemaps.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'file_name' => 'required|regex:/^[\w\-\.]+$/|ends_with:.xml',
            'path'      => 'required|starts_with:/|regex:/^(?!.*\/\/)[\w\-\.\/]+$/|ends_with:/',
        ]);

        Event::dispatch('marketing.search_seo.sitemap.create.before');

        $sitemap = $this->sitemapRepository->create(request()->only([
            'file_name',
            'path',
        ]));

        ProcessSitemap::dispatch($sitemap);

        Event::dispatch('marketing.search_seo.sitemap.create.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.sitemaps.index.create.success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(): JsonResponse
    {
        $id = request()->id;

        $this->validate(request(), [
            'file_name' => 'required|regex:/^[\w\-\.]+$/|ends_with:.xml',
            'path'      => 'required|starts_with:/|regex:/^(?!.*\/\/)[\w\-\.\/]+$/|ends_with:/',
        ]);

        Event::dispatch('marketing.search_seo.sitemap.update.before', $id);

        $sitemap = $this->sitemapRepository->update(request()->only([
            'file_name',
            'path',
        ]), $id);

        ProcessSitemap::dispatch($sitemap);

        Event::dispatch('marketing.search_seo.sitemap.update.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        $sitemap = $this->sitemapRepository->findOrFail($id);

        $sitemap->deleteFromStorage();

        try {
            Event::dispatch('marketing.search_seo.sitemap.delete.before', $id);

            $this->sitemapRepository->delete($id);

            Event::dispatch('marketing.search_seo.sitemap.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('admin::app.marketing.search-seo.sitemaps.delete-failed', ['name' => 'admin::app.marketing.search-seo.sitemaps.index.sitemap']),
            ], 500);
        }
    }
}
