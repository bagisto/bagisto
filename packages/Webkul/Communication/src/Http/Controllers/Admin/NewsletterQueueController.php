<?php

namespace Webkul\Communication\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\Communication\Repositories\NewsletterQueueRepository;
use Webkul\Communication\Repositories\NewsletterTemplateRepository;

class NewsletterQueueController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Newsletter template repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterTemplateRepository
     */
    protected $newsletterTemplateRepository;

    /**
     * Newsletter queue repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterQueueRepository
     */
    protected $newsletterQueueRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Communication\Repositories\NewsletterTemplateRepository $newsletterTemplateRepository
     * @return void
     */
    public function __construct(
        NewsletterTemplateRepository $newsletterTemplateRepository,
        NewsletterQueueRepository $newsletterQueueRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->newsletterTemplateRepository = $newsletterTemplateRepository;
        $this->newsletterQueueRepository = $newsletterQueueRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $newsletterTemplateId
     * @return \Illuminate\View\View
     */
    public function create($newsletterTemplateId)
    {
        $newsletterTemplate = $this->newsletterTemplateRepository->findOrFail($newsletterTemplateId);

        return view($this->_config['view'], compact('newsletterTemplate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $newsletterTemplateId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $newsletterTemplateId)
    {
        $inputs = $request->validate([
            'subject' => 'required',
            'sender_name' => 'required',
            'sender_email' => 'required',
            'content' => 'required',
            'queue_datetime' => 'required|date'
        ]);

        $inputs['queue_datetime'] .= ' ' . now()->toTimeString();

        $this->newsletterQueueRepository->create($inputs);

        session()->flash('success', trans('communication::app.newsletter-queue.queue-form.response.store-success'));

        return redirect()->route('communication.newsletter-queue.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
