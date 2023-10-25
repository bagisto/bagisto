<?php

namespace Webkul\Shop\Http\Controllers\CMS;

use Webkul\CMS\Repositories\CmsRepository;
use Webkul\Shop\Http\Controllers\Controller;

class PagePresenterController extends Controller
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
     * To extract the page content and load it in the respective view file
     *
     * @param  string  $urlKey
     * @return \Illuminate\View\View
     */
    public function presenter($urlKey)
    {
        $page = $this->cmsRepository->findByUrlKeyOrFail($urlKey);

        return view('shop::cms.page')->with('page', $page);
    }
}
