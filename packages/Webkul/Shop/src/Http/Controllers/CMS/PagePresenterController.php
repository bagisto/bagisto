<?php

namespace Webkul\Shop\Http\Controllers\CMS;

use Webkul\CMS\Repositories\PageRepository;
use Webkul\Shop\Http\Controllers\Controller;

class PagePresenterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository)
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
        $page = $this->pageRepository->findByUrlKeyOrFail($urlKey);

        return view('shop::cms.page')->with('page', $page);
    }
}
