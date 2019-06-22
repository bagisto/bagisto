<?php

namespace Webkul\SAASCustomizer\Http\Controllers;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\CompanyDetailsRepository;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\SAASCustomizer\Helpers\DataPurger;
use Webkul\SAASCustomizer\Helpers\StatsPurger;

use Company;
use DB;
use Request;
use Validator;

/**
 * Company controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyController extends Controller
{
    protected $attribute;
    protected $_config;
    protected $details;
    protected $admin;
    protected $role;
    protected $dataSeed;
    protected $companyStats;

    public function __construct(CompanyRepository $company, CompanyDetailsRepository $details, Admin $admin, Role $role, DataPurger $dataSeed, StatsPurger $companyStats)
    {
        $this->company = $company;
        $this->details = $details;
        $this->admin = $admin;
        $this->role = $role;
        $this->dataSeed = $dataSeed;
        $this->companyStats = $companyStats;
        $this->_config = request('_config');

        if (! Company::isAllowed()) {
            abort(404);
        }
    }

    public function showCompanyStats($id)
    {
        $aggregates = $this->companyStats->getAggregates($id);

        $company = $this->company->find($id);

        return view('saas::companies.company.index')->with('company', [$company, $aggregates]);
    }

    public function create()
    {
        if (session()->has('company')) {
            session()->regenerate();
            session()->forget('company');
        }

        return view($this->_config['view']);
    }

    protected function store()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|max:191|unique:admins,email',
            'password' => 'required|string|confirmed|min:6',
            'username' => 'required|alpha_num|min:3|max:64',
            'first_name' => 'required|string|max:191',
            'last_name' => 'nullable|string|max:191',
            'phone_no' => 'required|digits_between:10,11',
            'org_name' => 'required|string|max:191'
        ]);

        $data = Request::all();

        $authEmail = $data['email'];
        unset($data['email']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 403);
        }

        $validator = Validator::make(Request::all(), [
            'username' => 'not_in:www,http,https,WWW,HTTP,HTTPS'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 403);
        }

        if (strtolower($data['username']) == 'www' || strtolower($data['username']) == 'http' || strtolower($data['username']) == 'https') {
            session()->flash('warning', 'Illegal subdomain name');
        }

        $data['name'] = $data['org_name'];
        unset($data['org_name']);

        $primaryServerName = config('app.url');

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        $data['domain'] = strtolower($data['username']). '.' . $primaryServerNameWithoutProtocol;

        $validator = Validator::make($data, [
            'domain' => 'required|unique:companies,domain'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 403);
        }

        $company = $this->company->create($data);

        if ($company) {
            $info = [
                'created' => true,
                'seeded' => false
            ];

            $info = json_encode($info);

            $company->update([
                'extra_info' => $info
            ]);

            $data['password'] = bcrypt($data['password']);
            $data['name'] = $data['first_name'].' '.$data['last_name'];
            $data['status'] = 1;

            //creates a new full privilege role when new company is registered
            $role = $this->role->create([
                'name' => 'Administrator',
                'description' => 'Administrator role',
                'permission_type' => 'all',
                'company_id' => $company->id
            ]);

            $data['role_id'] = $role->id;
            $data['email'] = $authEmail;
            $data['company_id'] = $company->id;

            //creates a new full privilege admin with newly created role above
            $this->admin->create($data);

            //creates the personal details record for the company
            $this->details->create($data);

            if (session()->has('company')) {
                session()->forget('company');

                session()->put('company', $company);
            } else {
                session()->put('company', $company);
            }

            return response()->json([
                'success' => true, 'redirect' => $data['domain']
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 403);
        }
    }

    public function validateStepOne()
    {
        $this->validate(request(),[
            'email' => 'required|email|unique:admins,email'
        ]);

        $niceNames = array(
            'email' => 'Email'
        );

        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|unique:admins,email'
        ]);

        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 403);
        } else {
            return response()->json([
                'success' => true,
                'errors' => null
            ], 200);
        }
    }

    public function validateStepThree()
    {
        $niceNames = array(
            'username' => 'Username',
            'org_name' => 'Organization Name'
        );

        $validator = Validator::make(request()->all(), [
            'username' => 'required|alpha_num|min:3|max:64|unique:companies,username',
            'org_name' => 'required|string|max:191|unique:companies,name'
        ]);

        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 403);
        } else {
            return response()->json([
                'success' => true,
                'errors' => null
            ], 200);
        }

        // $this->validate(request(), [
        //     'username' => 'required|alpha_num|min:3|max:64|unique:companies,username',
        //     'org_name' => 'required|string|max:191|unique:companies,name'
        // ]);
    }

    public function edit($id)
    {
        $company = $this->company->findOrFail($id);

        return view('saas::companies.company.edit')->with('company', $company);
    }

    public function update($id)
    {
        $this->validate(request(), [
            'email' => 'email|max:191|unique:companies,email,'.$id,
            'name' => 'required|string|max:191|unique:companies,name,'.$id,
            'domain' => 'required|string|max:191|unique:companies,domain,'.$id,
            'is_active' => 'required|boolean'
        ]);

        $data = request()->all();

        $company = $this->company->findOrFail($id);

        if ($company) {
            $result = $company->update($data);

            if ($result) {
                session()->flash('success', 'Company Updated Successfully');
            } else {
                session()->flash('warning', 'Something went wrong');
            }
        } else {
            session()->flash('warning', 'Something went wrong');
        }

        return redirect()->back();
    }

    protected function changeStatus($id)
    {
        $company = $this->company->find($id);

        if ($company->is_active == 0) {
            $company->update([
                'is_active' => 1
            ]);

            session()->flash('success', 'Company successfully activated');
        } else {
            $company->update([
                'is_active' => 0
            ]);

            session()->flash('warning', 'Company successfully deactivated');
        }

        return redirect()->back();
    }
}