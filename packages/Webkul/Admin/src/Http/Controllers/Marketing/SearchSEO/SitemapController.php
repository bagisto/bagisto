<?php

namespace Webkul\Admin\Http\Controllers\Marketing\SearchSEO;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
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
    public function __construct(protected SitemapRepository $sitemapRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
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
            'path' => 'required|starts_with:/|regex:/^(?!.*\/\/)[\w\-\.\/]+$/|ends_with:/',
            'channels' => 'required|array|min:1',
        ]);

        Event::dispatch('marketing.search_seo.sitemap.create.before');

        $sitemap = $this->sitemapRepository->create(request()->only([
            'file_name',
            'path',
            'channels',
        ]));

        ProcessSitemap::dispatch($sitemap);

        Event::dispatch('marketing.search_seo.sitemap.create.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.sitemaps.index.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $sitemap = $this->sitemapRepository->findOrFail($id);

        return new JsonResponse([
            'data' => array_merge($sitemap->toArray(), [
                'channels' => $sitemap->channels->pluck('id')->all(),
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $this->validate(request(), [
            'id' => 'required|integer|exists:sitemaps,id',
            'file_name' => 'required|regex:/^[\w\-\.]+$/|ends_with:.xml',
            'path' => 'required|starts_with:/|regex:/^(?!.*\/\/)[\w\-\.\/]+$/|ends_with:/',
            'channels' => 'required|array|min:1',
        ]);

        $id = request()->id;

        Event::dispatch('marketing.search_seo.sitemap.update.before', $id);

        $sitemap = $this->sitemapRepository->update(request()->only([
            'file_name',
            'path',
            'channels',
        ]), $id);

        ProcessSitemap::dispatch($sitemap);

        Event::dispatch('marketing.search_seo.sitemap.update.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $sitemap = $this->sitemapRepository->findOrFail($id);

        $sitemap->deleteFromStorage();

        try {
            Event::dispatch('marketing.search_seo.sitemap.delete.before', $id);

            $this->sitemapRepository->delete($id);

            Event::dispatch('marketing.search_seo.sitemap.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.marketing.search-seo.sitemaps.delete-failed', ['name' => 'admin::app.marketing.search-seo.sitemaps.index.sitemap']),
            ], 500);
        }
    }
}
