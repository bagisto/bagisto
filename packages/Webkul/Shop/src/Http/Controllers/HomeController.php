<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class HomeController extends Controller
{
    /**
     * Using const variable for status
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository)
    {
    }

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        visitor()->visit();

        $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
            'status'     => self::STATUS,
            'channel_id' => core()->getCurrentChannel()->id,
        ]);

        return view('shop::home.index', compact('customizations'));
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Summary of contact
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('shop::home.contact');
    }

    /**
     * Summary of store
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContactRequest $contactRequest)
    {
        try {
            Mail::queue(new ContactUs($contactRequest->only([
                'name',
                'email',
                'contact',
                'message',
            ])));

            session()->flash('success', trans('contact successfully sent to the site'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }
}
