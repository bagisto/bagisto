<?php

namespace Webkul\Bulkupload\Http\Controllers\Admin;

use Storage;
use Exception;
use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Schema;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository;
use Webkul\Bulkupload\Repositories\DataFlowProfileRepository as AdminDataFlowProfileRepository;

class BulkUploadController extends Controller
{
    /**
     * adminDataFlowProfileRepository object
     *
     * @var array
     */
    protected $adminDataFlowProfileRepository;

    /**
     * attributeFamilyRepository object
     */
    protected $attributeFamily;

    /**
     * importNewProductsByAdminRepository object
     */
    protected $importNewProductsByAdminRepository;

    protected $product = [];

    protected $_config;

    public function __construct(
        AttributeFamily $attributeFamily,
        AdminDataFlowProfileRepository $adminDataFlowProfileRepository,
        ImportNewProductsByAdminRepository $importNewProductsByAdminRepository
        )
    {
        $this->attributeFamily = $attributeFamily;

        $this->importNewProductsByAdminRepository = $importNewProductsByAdminRepository;

        $this->adminDataFlowProfileRepository = $adminDataFlowProfileRepository;

        $this->_config = request('_config');
    }

    public function index()
    {
        $profiles = null;
        $families = $this->attributeFamily->all();
        $allProfiles = $this->importNewProductsByAdminRepository->get()->toArray();
        $configurableFamily = null;

        if (! empty($allProfiles)) {
            foreach ($allProfiles as $allProfile)
            {
                $profilers[] = $allProfile['data_flow_profile_id'];
            }

            foreach($profilers as $key => $profiler)
            {
                $profiles[] = $this->adminDataFlowProfileRepository->findByfield(['id' => $profilers[$key], 'run_status' => '0']);
            }
        }

        $customers = DB::table('customers')
            ->select('customers.*' ,DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name'))->get();

        $admins = DB::table('admins')
            ->select('admins.*')->get();

        if ($familyId = request()->get('attribute_family_id')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'profiles', 'configurableFamily', 'customers', 'admins'));
    }

    public function store()
    {
        request()->validate([
            'profile_name' => 'required|unique:bulkupload_data_flow_profiles',
            'attribute_family' => 'required'
        ]);

        $dataFlowProfileAdmin['profile_name'] = request()->profile_name;
        $dataFlowProfileAdmin['attribute_family_id'] = request()->attribute_family;
        $dataFlowProfileAdmin['seller_id'] = "admin";

        $this->adminDataFlowProfileRepository->create($dataFlowProfileAdmin);

        Session()->flash('success',trans('bulkupload::app.shop.profile.success'));

        return redirect()->route('admin.dataflow-profile.index');
    }

    public function edit($id)
    {
        $families = $this->attributeFamily->all();
        $profiles = $this->adminDataFlowProfileRepository->findOrFail($id);
        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'profiles', 'configurableFamily'));
    }

    public function update($id)
    {
        $product = $this->adminDataFlowProfileRepository->update(request()->except('_token'), $id);
        $families = $this->attributeFamily->all();
        $profiles = $this->adminDataFlowProfileRepository->findOrFail($id);

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

        return redirect()->route('admin.dataflow-profile.index');
    }

    public function destroy($id)
    {
        $product = $this->adminDataFlowProfileRepository->findOrFail($id)->delete();

        Session()->flash('success',trans('bulkupload::app.shop.profile.profile-deleted'));

        return redirect()->route('admin.dataflow-profile.index');
    }

    public function massDestroy()
    {
        $profileIds = explode(',', request()->input('indexes'));

        foreach ($profileIds as $profileId) {
            $profile = $this->adminDataFlowProfileRepository->find($profileId);

            if (isset($profile)) {
                $this->adminDataFlowProfileRepository->delete($profileId);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}