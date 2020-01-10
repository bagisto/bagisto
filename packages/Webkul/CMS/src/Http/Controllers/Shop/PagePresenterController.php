<?php

namespace Webkul\CMS\Http\Controllers\Shop;

use Webkul\CMS\Http\Controllers\Controller;
use Webkul\CMS\Repositories\CMSRepository;
use Webkul\Core\Repositories\LocaleRepository;

/**
 * PagePresenter controller
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PagePresenterController extends Controller
{
    /**
     * CMSRepository object
     *
     * @var Object
     */
    protected $cmsRepository;

    /**
     * LocaleRepository object
     *
     * @var Object
     */
    protected $localeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CMS\Repositories\CMSRepository     $cmsRepository
     * @param  \Webkul\Core\Repositories\LocaleRepository $localeRepository
     * @return void
     */
    public function __construct(
        CMSRepository $cmsRepository,
        LocaleRepository $localeRepository
    )
    {
        $this->cmsRepository = $cmsRepository;

        $this->localeRepository = $localeRepository;
    }

    /**
     * To extract the page content and load it in the respective view file
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function presenter($slug)
    {
        $currentLocale = $this->localeRepository->findOneByField('code', app()->getLocale());

        $page = $this->cmsRepository->findOneWhere([
                'url_key' => $slug,
                'locale_id' => $currentLocale->id,
                'channel_id' => core()->getCurrentChannel()->id
            ]);

        if (! $page)
            abort(404);

        return view('shop::cms.page')->with('page', $page);
    }
}