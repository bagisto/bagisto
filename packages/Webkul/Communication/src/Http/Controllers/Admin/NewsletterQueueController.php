<?php

namespace Webkul\Communication\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
     * Newsletter Template Repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterTemplateRepository
     */
    protected $newsletterTemplateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Communication\Repositories\NewsletterTemplateRepository $newsletterTemplateRepository
     * @return void
     */
    public function __construct(
        NewsletterTemplateRepository $newsletterTemplateRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->newsletterTemplateRepository = $newsletterTemplateRepository;
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
        // $inputs = $request->validate([
        //     'template_name' => 'required',
        //     'template_subject' => 'required',
        //     'sender_name' => 'required',
        //     'sender_email' => 'required',
        //     'template_content' => 'required'
        // ]);

        // $this->newsletterTemplateRepository->create($inputs);

        // session()->flash('success', trans('communication::app.newsletter-templates.template-form.response.store-success'));

        // return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // $newsletterTemplate = $this->newsletterTemplateRepository->findOrFail($id);

        // return view($this->_config['view'], compact('newsletterTemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $inputs = $request->validate([
        //     'template_name' => 'required',
        //     'template_subject' => 'required',
        //     'sender_name' => 'required',
        //     'sender_email' => 'required',
        //     'template_content' => 'required'
        // ]);

        // $this->newsletterTemplateRepository->update($inputs, $id);

        // session()->flash('success', trans('communication::app.newsletter-templates.template-form.response.update-success'));

        // return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $newsletterTemplate = $this->newsletterTemplateRepository->findOrFail($id);

        // $newsletterTemplate->delete();

        // session()->flash('success', trans('communication::app.newsletter-templates.template-form.response.destroy-success'));

        // return response()->json(['message' => true], 200);
    }
}
