<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Http\Middleware\SecureHeaders;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Http\Resources\CategoryTreeResource;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\SectionRepository;

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
    public function __construct(protected SectionRepository $sectionRepository, protected CategoryRepository $categoryRepository) {}

    /**
     * Loads the home page for the storefront.
     *
     * @return View
     */
    public function index()
    {
        $sections = $this->sectionRepository->getRenderable(
            core()->getCurrentChannel()->id,
            core()->getCurrentChannel()->theme
        );

        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        $categories = CategoryTreeResource::collection($categories);

        return view('shop::home.index', compact('sections', 'categories'));
    }

    /**
     * Render the home page from unpublished section edits, for the appearance editor.
     *
     * @return View
     */
    public function preview()
    {
        abort_unless(bouncer()->hasPermission('appearance.sections'), 403);

        request()->attributes->set(SecureHeaders::FRAMABLE, true);

        request()->attributes->set(SectionRepository::PREVIEWING, true);

        $channel = core()->getAllChannels()->firstWhere('id', (int) request('channel'))
            ?? core()->getCurrentChannel();

        core()->setCurrentChannel($channel);

        $sections = $this->sectionRepository->getDraftedForPreview(
            $channel->id,
            $channel->theme,
            app()->getLocale()
        );

        $categories = CategoryTreeResource::collection(
            $this->categoryRepository->getVisibleCategoryTree($channel->root_category_id)
        );

        return view('shop::home.index', compact('sections', 'categories') + ['preview' => true]);
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
     * Summary of contact.
     *
     * @return View
     */
    public function contactUs()
    {
        return view('shop::home.contact-us');
    }

    /**
     * Summary of store.
     *
     * @return RedirectResponse
     */
    public function sendContactUsMail(ContactRequest $contactRequest)
    {
        try {
            Mail::queue(new ContactUs($contactRequest->only([
                'name',
                'email',
                'contact',
                'message',
            ])));

            session()->flash('success', trans('shop::app.home.thanks-for-contact'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }
}
