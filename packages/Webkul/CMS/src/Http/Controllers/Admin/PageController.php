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
        $this->middleware('auth:admin');

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
        $this->validate(request(), [
            'channel' => 'required|string',
            'locale' => 'required|string',
            'url_key' => 'required|unique:cms_pages,url_key',
            'html_content' => 'required|string',
            'page_title' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'string',
            'meta_keywords' => 'required|string'
        ]);

        $data = request()->all();

        $data['content']['html'] = $data['html_content'];
        $data['content']['page_title'] = $data['page_title'];
        $data['content']['meta_keywords'] = $data['meta_keywords'];
        $data['content']['meta_title'] = $data['meta_title'];
        $data['content']['meta_description'] = $data['meta_description'];

        $data['content'] = json_encode($data['content']);

        $result = $this->cms->create($data);

        if ($result) {
            session()->flash('success', trans('admin::app.cms.pages.create-success'));
        } else {
            session()->flash('success', trans('admin::app.cms.pages.create-failure'));
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

        return view($this->_config['view'])->with('page', $page);
    }

    /**
     * To update the previously created CMS page in storage
     *
     * @return view
     */
    public function update($id)
    {
        $page = $this->cms->findOrFail($id);

        $this->validate(request(), [
            'channel' => 'required|string',
            'locale' => 'required|string',
            'url_key' => 'required|unique:cms_pages,url_key,'.$id,
            'html_content' => 'required|string',
            'page_title' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'string',
            'meta_keywords' => 'required|string'
        ]);

        $data = request()->all();

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
     * @return mixed
     */
    public function preview($urlKey)
    {
        $page = $this->cms->findOneWhere([
            'url_key' => $urlKey
        ]);

        return view('shop::cms.page')->with('page', $page);
    }

    /**
     * To delete the previously create CMS page
     *
     * @return json
     */
    public function delete($id)
    {
        $page = $this->cms->findOrFail($id);

        if ($page->delete()) {
            session()->flash('success', trans('admin::app.cms.delete-success'));

            return response()->json(['message' => true], 200);
        } else {
            session()->flash('success', trans('admin::app.cms.delete-failure'));

            return response()->json(['message' => false], 200);
        }
    }

    /**
     * To extract the page content and load it in the respective view file\
     *
     * @return view
     */
    public function presenter($slug)
    {
        $page = $this->cms->findOneWhere([
            'url_key' => $slug
        ]);

        $layout = $page->layout;

        return view('shop::cms.page')->with('data', $page);
    }
}