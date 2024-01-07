<?php

namespace Webkul\Admin\Http\Controllers\Marketing\SearchSEO;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\DataGrids\Marketing\SearchSEO\SearchSynonymDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Marketing\Repositories\SearchSynonymRepository;

class SearchSynonymController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public SearchSynonymRepository $searchSynonymRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SearchSynonymDataGrid::class)->toJson();
        }

        return view('admin::marketing.search-seo.search-synonyms.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name'  => 'required',
            'terms' => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_synonyms.create.before');

        $searchSynonym = $this->searchSynonymRepository->create(request()->only([
            'name',
            'terms',
        ]));

        Event::dispatch('marketing.search_seo.search_synonyms.create.after', $searchSynonym);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.search-synonyms.index.create.success'),
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
            'name'  => 'required',
            'terms' => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_synonyms.update.before', $id);

        $searchSynonym = $this->searchSynonymRepository->update(request()->only([
            'name',
            'terms',
        ]), $id);

        Event::dispatch('marketing.search_seo.search_synonyms.update.after', $searchSynonym);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.search-seo.search-synonyms.index.edit.success'),
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
            Event::dispatch('marketing.search_seo.search_synonyms.delete.before', $id);

            $this->searchSynonymRepository->delete($id);

            Event::dispatch('marketing.search_seo.search_synonyms.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.marketing.search-seo.search-synonyms.index.edit.delete-success'),
            ], 200);
        } catch (\Exception $e) {
        }

        return response()->json([
            'message' => trans('admin::app.marketing.search-seo.search-synonyms.delete-failed'),
        ], 500);
    }

    /**
     * Mass delete the search terms.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $searchSynonymIds = $massDestroyRequest->input('indices');

        try {
            foreach ($searchSynonymIds as $searchSynonymId) {
                $searchSynonym = $this->searchSynonymRepository->find($searchSynonymId);

                if (isset($searchSynonym)) {
                    Event::dispatch('marketing.search_seo.search_synonyms.delete.before', $searchSynonymId);

                    $this->searchSynonymRepository->delete($searchSynonymId);

                    Event::dispatch('marketing.search_seo.search_synonyms.delete.after', $searchSynonymId);
                }
            }

            return new JsonResponse([
                'message' => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
