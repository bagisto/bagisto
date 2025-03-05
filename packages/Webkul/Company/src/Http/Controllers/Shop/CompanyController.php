<?php

namespace Webkul\Company\Http\Controllers\Shop;

use Webkul\Company\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CompanyController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct(private readonly CompanyRepository $companyRepository)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $companies = $this->companyRepository->getListCompany($request->all());
        return view('company::shop.index')->with('companies', $companies);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function detail(string $id)
    {
        $company = $this->companyRepository->detailCompany($id);
        return view('company::shop.detail')->with('company', $company);
    }
}
