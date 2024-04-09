<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\CMS\Repositories\PageRepository;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use Webkul\Shop\Mail\ContactUs;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PageRepository $pageRepository,
        protected URLRewriteRepository $urlRewriteRepository
    ) {
    }

    /**
     * To extract the page content and load it in the respective view file
     *
     * @param  string  $urlKey
     * @return \Illuminate\View\View
     */
    public function view($urlKey)
    {
        $page = $this->pageRepository->findByUrlKey($urlKey);

        if (! $page) {
            $urlRewrite = $this->urlRewriteRepository->findOneWhere([
                'entity_type'  => 'cms_page',
                'request_path' => $urlKey,
                'locale'       => app()->getLocale(),
            ]);

            if ($urlRewrite) {
                return redirect()->to($urlRewrite->target_path, $urlRewrite->redirect_type);
            }
        }

        return view('shop::cms.page')->with('page', $page);
    }

    public function contact()
    {
        return view('shop::cms.page');
    }

    public function store()
    {
        try {
            Mail::queue(new ContactUs(request()->only([
                'name',
                'email',
                'contact',
                'message'
            ])));

            session()->flash('success', trans('contact successfully sent to the site'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }
}
