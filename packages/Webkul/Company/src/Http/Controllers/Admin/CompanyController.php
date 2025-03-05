<?php

namespace Webkul\Company\Http\Controllers\Admin;

use Webkul\Company\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(private readonly CompanyRepository $companyRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        
        $companies = $this->companyRepository->getListCompany($request->all());
        return view('company::admin.index')->with('companies', $companies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('company::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function detail(string $id)
    {
        $company = $this->companyRepository->detailCompany($id);
        return view('company::admin.detail')->with('company', $company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {

    }
}
