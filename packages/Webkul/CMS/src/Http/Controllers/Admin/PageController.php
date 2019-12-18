<?php

namespace Webkul\CMS\Http\Controllers\Admin;

use Webkul\CMS\Http\Controllers\Controller;
use Webkul\CMS\Repositories\CMSRepository as CMS;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Webkul\Core\Repositories\LocaleRepository as Locale;

/**
 * CMS controller
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
 class PageController extends Controller
{
    /**
     * To hold the request variables from route file
     */
    protected $_config;

    /**
     * To hold the channel reposotry instance
     */
    protected $channel;

    /**
     * To hold the locale reposotry instance
     */
    protected $locale;

    /**
     * To hold the CMSRepository instance
     */
    protected $cms;

    public function __construct(Channel $channel, Locale $locale, CMS $cms)
    {
        /**
         * Pass the class instance through admin middleware
         */
       // $this->middleware('auth:admin');

        $this->middleware('admin');

        /**
         * Channel repository instance
         */
        $this->channel = $channel;

        /**
         * Locale repository instance
         */
        $this->locale = $locale;

        /**
         * CMS repository instance
         */
        $this->cms = $cms;

        $this->_config = request('_config');
    }

    /**
     * Loads the index page showing the static pages resources
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * To create a new CMS page
     *
     * @return view
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * To store a new CMS page in storage
     *
     * @return view
     */
    public function store()
    {
        $data = request()->all();

        // part one of the validation in case partials pages were generated or generating partial pages
        $this->validate(request(), [
            'channels' => 'required',
            'locales' => 'required',
            'url_key' => 'required'
        ]);

        $channels = $data['channels'];
        $locales = $data['locales'];

        $this->validate(request(), [
            'html_content' => 'required|string',
            'page_title' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'string',
            'meta_keywords' => 'required|string'
        ]);

        $data['content']['html'] = $data['html_content'];
        $data['content']['page_title'] = $data['page_title'];
        $data['content']['meta_keywords'] = $data['meta_keywords'];
        $data['content']['meta_title'] = $data['meta_title'];
        $data['content']['meta_description'] = $data['meta_description'];

        $data['content'] = json_encode($data['content']);

        $totalCount = 0;
        $actualCount = 0;

        foreach ($channels as $channel) {
            foreach ($locales as $locale) {
                $pageFound = $this->cms->findOneWhere([
                    'channel_id' => $channel,
                    'locale_id' => $locale,
                    'url_key' => $data['url_key']
                ]);

                $totalCount++;

                $data['channel_id'] = $channel;

                $data['locale_id'] = $locale;

                if (! $pageFound) {
                    $result = $this->cms->create($data);

                    if ($result) {
                        $actualCount++;
                    }
                }

                unset($pageFound);
            }
        }

        if (($actualCount != 0 && $totalCount != 0) && ($actualCount == $totalCount)) {
            session()->flash('success', trans('admin::app.cms.pages.create-success'));
        } else if (($actualCount != 0 && $totalCount != 0) && ($actualCount != $totalCount)) {
            session()->flash('warning', trans('admin::app.cms.pages.create-partial'));
        } else {
            session()->flash('error', trans('admin::app.cms.pages.create-failure'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To edit a previously created CMS page
     *
     * @return view
     */
    public function edit($id)
    {
        $page = $this->cms->findOrFail($id);

        if (request()->has('channel') && request()->has('locale')) {
            $channel = $this->channel->findOneWhere([
                'code' => request()->input('channel')
            ]);

            $locale = $this->locale->findOneWhere([
                'code' => request()->input('locale')
            ]);

            $page = $this->cms->findOneWhere([
                'channel_id' => $channel->id,
                'locale_id' => $locale->id,
                'url_key' => $page->url_key
            ]);

            if (! $page) {
                $page  = $this->cms->create([
                    'url_key' => str_random(8),
                    'channel' => $channel->code,
                    'locale' => $locale->code
                ]);

                return redirect()->route('admin.cms.edit', $page->id);
            }
        } else {
            $page = $this->cms->findOrFail($id);
        }

        return view($this->_config['view'])->with('page', $page);
    }

    /**
     * To update the previously created CMS page in storage
     *
     * @param Integer $id
     *
     * @return View
     */
    public function update($id)
    {
        $page = $this->cms->findOrFail($id);

        $data = request()->all();

        $this->validate(request(), [
            'page_title' => 'required|string',
            'html_content' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'string',
            'meta_keywords' => 'required|string'
        ]);

        $data['content']['html'] = $data['html_content'];
        $data['content']['page_title'] = $data['page_title'];
        $data['content']['meta_keywords'] = $data['meta_keywords'];
        $data['content']['meta_title'] = $data['meta_title'];
        $data['content']['meta_description'] = $data['meta_description'];
        $data['content'] = json_encode($data['content']);

        $result = $this->cms->update($data, $id);

        if ($result) {
            session()->flash('success', trans('admin::app.cms.pages.update-success'));
        } else {
            session()->flash('success', trans('admin::app.cms.pages.update-failure'));
        }
        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To preview the content of the currently creating page or previously creating page
     *
     * @param Integer $id
     *
     * @return mixed
     */
    public function preview($id)
    {
        $page = $this->cms->findOrFail($id);

        return view('shop::cms.page')->with('page', $page);
    }

    /**
     * To delete the previously create CMS page
     *
     * @param Integer $id
     *
     * @return Response JSON
     */
    public function delete($id)
    {
        $page = $this->cms->findOrFail($id);

        if ($page->delete()) {
            session()->flash('success', trans('admin::app.cms.pages.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('admin::app.cms.pages.delete-failure'));

            return response()->json(['message' => false], 200);
        }
    }

    /**
     * To mass delete the CMS resource from storage
     *
     * @return Response redirect
     */
    public function massDelete()
    {
        $data = request()->all();

        if ($data['indexes']) {
            $pageIDs = explode(',', $data['indexes']);

            $actualCount = count($pageIDs);

            $count = 0;

            foreach ($pageIDs as $pageId) {

                $page = $this->cms->find($pageId);

                if ($page) {
                    $page->delete();

                    $count++;
                }
            }

            if ($actualCount == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => 'CMS Pages'
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => 'CMS Pages'
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.cms.index');
    }
}