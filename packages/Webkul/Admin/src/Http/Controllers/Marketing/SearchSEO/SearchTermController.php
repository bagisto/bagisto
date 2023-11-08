<?php

namespace Webkul\Admin\Http\Controllers\Marketing\SearchSEO;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\DataGrids\Marketing\SearchSEO\SearchTermDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketing\Repositories\SearchTermRepository;

class SearchTermController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public SearchTermRepository $searchTermRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SearchTermDataGrid::class)->toJson();
        }

        return view('admin::marketing.search-seo.search-terms.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'file_name' => 'required',
            'path'      => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_terms.create.before');

        $sitemap = $this->sitemapRepository->create(request()->only([
            'file_name',
            'path',
        ]));

        Event::dispatch('marketing.search_seo.search_terms.create.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.search-terms.index.create.success'),
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
            'file_name' => 'required',
            'path'      => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_terms.update.before', $id);

        $sitemap = $this->sitemapRepository->update(request()->only([
            'file_name',
            'path',
        ]), $id);

        Event::dispatch('marketing.search_seo.search_terms.update.after', $sitemap);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.search-terms.index.edit.success'),
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

        Storage::delete($sitemap->path . '/' . $sitemap->file_name);

        try {
            Event::dispatch('marketing.search_seo.search_terms.delete.before', $id);

            $this->sitemapRepository->delete($id);

            Event::dispatch('marketing.search_seo.search_terms.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.marketing.search-seo.search-terms.index.edit.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.marketing.search-seo.search-terms.delete-failed', ['name' => 'admin::app.marketing.search-seo.search-terms.index.sitemap']),
        ], 500);
    }
}
