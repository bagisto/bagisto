<?php

namespace Webkul\Admin\Http\Controllers\Marketing\SearchSEO;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\DataGrids\Marketing\SearchSEO\URLRewriteDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Marketing\Repositories\URLRewriteRepository;

class URLRewriteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public URLRewriteRepository $urlRewriteRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(URLRewriteDataGrid::class)->process();
        }

        return view('admin::marketing.search-seo.url-rewrites.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'entity_type'   => 'required:in:category,product,cms_page',
            'request_path'  => 'required',
            'target_path'   => 'required',
            'redirect_type' => 'required|in:301,302',
            'locale'        => 'required|exists:locales,code',
        ]);

        Event::dispatch('marketing.search_seo.url_rewrites.create.before');

        $urlRewrite = $this->urlRewriteRepository->create(request()->only([
            'entity_type',
            'request_path',
            'target_path',
            'redirect_type',
            'locale',
        ]));

        Event::dispatch('marketing.search_seo.url_rewrites.create.after', $urlRewrite);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.url-rewrites.index.create.success'),
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
            'entity_type'   => 'required:in:category,product,cms_page',
            'request_path'  => 'required',
            'target_path'   => 'required',
            'redirect_type' => 'required|in:301,302',
            'locale'        => 'required|exists:locales,code',
        ]);

        Event::dispatch('marketing.search_seo.url_rewrites.update.before', $id);

        $urlRewrite = $this->urlRewriteRepository->update(request()->only([
            'entity_type',
            'request_path',
            'target_path',
            'redirect_type',
            'locale',
        ]), $id);

        Event::dispatch('marketing.search_seo.url_rewrites.update.after', $urlRewrite);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.url-rewrites.index.edit.success'),
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
        try {
            Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $id);

            $this->urlRewriteRepository->delete($id);

            Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.marketing.search-seo.url-rewrites.index.edit.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.marketing.search-seo.url-rewrites.delete-failed'),
        ], 500);
    }

    /**
     * Mass delete the search terms.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $urlRewriteIds = $massDestroyRequest->input('indices');

        try {
            foreach ($urlRewriteIds as $urlRewriteId) {
                $urlRewrite = $this->urlRewriteRepository->find($urlRewriteId);

                if (isset($urlRewrite)) {
                    Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $urlRewriteId);

                    $this->urlRewriteRepository->delete($urlRewriteId);

                    Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $urlRewriteId);
                }
            }

            return new JsonResponse([
                'message' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
