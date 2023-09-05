<?php

namespace Webkul\Admin\Http\Controllers\CMS;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\CMS\Repositories\CmsRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\CMS\CMSPageDataGrid;
use Webkul\Core\Http\Requests\MassDestroyRequest;


class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CmsRepository $cmsRepository)
    {
    }

    /**
     * Loads the index page showing the static pages resources.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CMSPageDataGrid::class)->toJson();
        }

        return view('admin::cms.index');
    }

    /**
     * To create a new CMS page.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::cms.create');
    }

    /**
     * To store a new CMS page in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'url_key'      => ['required', 'unique:cms_page_translations,url_key', new \Webkul\Core\Rules\Slug],
            'page_title'   => 'required',
            'channels'     => 'required',
            'html_content' => 'required',
        ]);

        Event::dispatch('cms.pages.create.before');

        $data = request()->only([
            'page_title',
            'channels',
            'html_content',
            'meta_title',
            'url_key',
            'meta_keywords',
            'meta_description',
        ]);

        $page = $this->cmsRepository->create($data);

        Event::dispatch('cms.pages.create.after', $page);

        session()->flash('success', trans('admin::app.cms.create-success'));

        return redirect()->route('admin.cms.index');
    }

    /**
     * To edit a previously created CMS page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page = $this->cmsRepository->findOrFail($id);

        return view('admin::cms.edit', compact('page'));
    }

    /**
     * To update the previously created CMS page in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $locale = core()->getRequestedLocaleCode();

        $this->validate(request(), [
            $locale . '.url_key'      => ['required', new \Webkul\Core\Rules\Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->cmsRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('admin::app.cms.index.already-taken', ['name' => 'Page']));
                }
            }],
            $locale . '.page_title'   => 'required',
            $locale . '.html_content' => 'required',
            'channels'                => 'required',
        ]);

        Event::dispatch('cms.pages.update.before', $id);

        $data = [
            'en'       => request()->input('en'),
            'channels' => request()->input('channels'),
        ];

        $page = $this->cmsRepository->update($data, $id);

        Event::dispatch('cms.pages.update.after', $page);

        session()->flash('success', trans('admin::app.cms.update-success'));

        return redirect()->route('admin.cms.index');
    }

    /**
     * To delete the previously create CMS page.
     *
     * @param int $id
     * @return JsonResource
     */
    public function delete($id): JsonResource
    {
        Event::dispatch('cms.pages.delete.before', $id);

        $this->cmsRepository->delete($id);

        Event::dispatch('cms.pages.delete.after', $id);

        return new JsonResource(['message' => trans('admin::app.cms.delete-success')]);
    }

    /**
     * To mass delete the CMS resource from storage.
     *
     * @param MassDestroyRequest $massDestroyRequest
     * @return JsonResource
     */
    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResource
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('cms.pages.delete.before', $index);

            $this->cmsRepository->delete($index);

            Event::dispatch('cms.pages.delete.after', $index);
        }

        return new JsonResource([
            'message' => trans('admin::app.cms.index.datagrid.mass-delete-success')
        ], 200);
    }
}
