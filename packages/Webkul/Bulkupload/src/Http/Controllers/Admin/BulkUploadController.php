<?php

namespace Webkul\Bulkupload\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Webkul\Bulkupload\Models\DataFlowProfile;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Product\Repositories\ProductFlatRepository;
use Illuminate\Database\Eloquent;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Bulkupload\Repositories\BulkProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\ProductImageRepository as BulkUploadImages;
use Illuminate\Support\Facades\Event;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository;
use Webkul\Bulkupload\Repositories\DataFlowProfileRepository as AdminDataFlowProfileRepository;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Storage;
use Exception;
use File;
use DB;
//need to alter above codes
use Webkul\Bulkupload\Repositories\Products\SimpleProductRepository;
use Webkul\Bulkupload\Repositories\Products\ConfigurableProductRepository;
use Webkul\Bulkupload\Repositories\Products\VirtualProductRepository;
use Webkul\Bulkupload\Repositories\Products\DownloadableProductRepository;
use Webkul\Bulkupload\Repositories\Products\GroupedProductRepository;
use Webkul\Bulkupload\Repositories\Products\BundledProductRepository;
use Webkul\Bulkupload\Repositories\Products\BookingProductRepository;





class BulkUploadController extends Controller
{
    protected $attributeFamily;

    protected $product = [];

    protected $productRepository;

    protected $productImage;

    protected $_config;

    protected $attribute;

    protected $bulkUploadImages;

    protected $customerRepository;

    protected $bulkProductRepository;

    /**
     * ProductFlatRepository Repository Object
     *
     * @var object
     */
    protected $productFlatRepository;

    /**
     * ProductAttributeValueRepository Repository Object
     *
     * @var object
     */
    protected $productAttributeValueRepository;

      /**
     * AttributeOptionRepository Repository Object
     *
     * @var object
     */
    protected $attributeOptionRepository;

     /**
     * Marketplace ProductRepository object
     *
     * @var array
     */
    protected $sellerProduct;

    /**
     * CategoryRepository object
     *
     * @var array
     */
    protected $categoryRepository;

    /**
     * dataFlowProfileAdmin object
     *
     * @var array
     */
    // protected $dataFlowProfileAdmin;

    /**
     * adminDataFlowProfileRepository object
     *
     * @var array
     */

    protected $adminDataFlowProfileRepository;

    protected $importNewProductsByAdminRepository;


    protected $simpleProductRepository;
    protected $configurableProductRepository;
    protected $virtualProductRepository;
    protected $downloadableProductRepository;
    protected $bundledProductRepository;
    protected $bookingProductRepository;
    protected $groupedProductRepository;



    public function __construct(
        AttributeFamily $attributeFamily,
        DataFlowProfile $dataFlowProfile,
        ImportNewProducts $importNewProducts,
        ProductRepository $productRepository,
        ProductImageRepository $productImage,
        AttributeRepository $attribute,
        ProductFlatRepository $productFlatRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        AttributeOptionRepository $attributeOptionRepository,
        BulkUploadImages $bulkUploadImages,
        CategoryRepository $categoryRepository,
        AdminDataFlowProfileRepository $adminDataFlowProfileRepository,
        CustomerRepository $customerRepository,
        ImportNewProductsByAdminRepository $importNewProductsByAdminRepository,
        BulkProductRepository $bulkProductRepository,
        // cm
        SimpleProductRepository $simpleProductRepository,
        ConfigurableProductRepository $configurableProductRepository,        VirtualProductRepository $virtualProductRepository,
        DownloadableProductRepository $downloadableProductRepository,
        BundledProductRepository $bundledProductRepository,
        BookingProductRepository $bookingProductRepository,
        GroupedProductRepository $groupedProductRepository
        )
    {
        $this->simpleProductRepository = $simpleProductRepository;
        $this->configurableProductRepository = $configurableProductRepository;
        $this->virtualProductRepository = $virtualProductRepository;
        $this->downloadableProductRepository = $downloadableProductRepository;
        $this->bundledProductRepository = $bundledProductRepository;
        $this->bookingProductRepository = $bookingProductRepository;
        $this->groupedProductRepository = $groupedProductRepository;


        $this->attributeFamily = $attributeFamily;

        $this->dataFlowProfile = $dataFlowProfile;

        $this->importNewProducts = $importNewProducts;

        $this->productRepository = $productRepository;

        $this->bulkProductRepository = $bulkProductRepository;

        $this->productImage = $productImage;

        $this->attribute = $attribute;

        $this->productFlatRepository = $productFlatRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->importNewProductsByAdminRepository = $importNewProductsByAdminRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->categoryRepository = $categoryRepository;

        $this->customerRepository = $customerRepository;

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

    public function getDataProfilesToExecute()
    {
        $profiler = [];
        $families = $this->attributeFamily->all();
        $allProfiles = $this->importNewProductsByAdminRepository->all();

        foreach($allProfiles as $allProfile)
        {
            $profiler = $allProfile->data_flow_profile_id;
        }

        $configurableFamily = null;

        $sellerData = DB::table('marketplace_sellers')
            ->leftJoin('customers', 'marketplace_sellers.customer_id', '=', 'customers.id')
            ->select('marketplace_sellers.customer_id', DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name'))->get();

        if ($familyId = request()->get('attribute_family_id')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'profiles', 'configurableFamily', 'sellerData'));
    }

    public function downloadFile()
    {
        if (request()->download_sample == 'simple-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulksimpleproductupload.csv'));
        } else if (request()->download_sample == 'configurable-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkconfigurableproductupload.csv'));
        } else if (request()->download_sample == 'simple-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulksimpleproductupload.xlsx'));
        } else if (request()->download_sample == 'configurable-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkconfigurableproductupload.xlsx'));
        } else if (request()->download_sample == 'virtual-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkvirtualproductupload.csv'));
        } else if (request()->download_sample == 'virtual-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkvirtualproductupload.xlsx'));
        } else if (request()->download_sample == 'downloadable-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkdownloadableproductupload.csv'));
        } else if (request()->download_sample == 'downloadable-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkdownloadableproductupload.xlsx'));
        } else if (request()->download_sample == 'grouped-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkgroupedproductupload.csv'));
        } else if (request()->download_sample == 'grouped-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkgroupedproductupload.xlsx'));
        } else if (request()->download_sample == 'bundle-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkbundleproductupload.csv'));
        } else if (request()->download_sample == 'bundle-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkbundleproductupload.xlsx'));
        } else if (request()->download_sample == 'booking-csv') {
            return response()->download(public_path('storage/downloads/sample-files/bulkbookingproductupload.csv'));
        } else if (request()->download_sample == 'booking-xls') {
            return response()->download(public_path('storage/downloads/sample-files/bulkbookingproductupload.xlsx'));
        } else {
            return redirect()->back();
        }
    }

    public function getAllDataFlowProfiles()
    {
        $attribute_family_id = request()->attribute_family_id;

        $dataFlowProfiles = $this->adminDataFlowProfileRepository->findByField('attribute_family_id', request()->attribute_family_id);

        return ['dataFlowProfiles' => $dataFlowProfiles];
    }

    public function importNewProductsStore(Request $request)
    {
        $request->validate([
            'attribute_family' => 'required',
            'file_path' => 'required',
            'data_flow_profile' => 'required',
        ]);

        $valid_extension = ['csv', 'xls', 'xlsx'];
        $valid_image_extension = ['zip', 'rar'];

        $imageDir = 'imported-products/admin/images';
        $fileDir = 'imported-products/admin/files';
        $linkFilesDir = 'imported-products/admin/link-files';
        $linkSampleFilesDir = 'imported-products/admin/link-sample-files';
        $sampleFileDir = 'imported-products/admin/sample-file';

        $attribute_family_id = $request->attribute_family;
        $data_flow_profile_id = $request->data_flow_profile;

        $image = $request->file('image_path');
        $file = $request->file('file_path');
        $linkFiles = $request->file('link_files');
        $linkSampleFiles = $request->file('link_sample_files');
        $sampleFile = $request->file('sample_file');

        if (! isset($image)) {
            $image = '';
        }

        if ($request->is_downloadable) {
            $product['is_downloadable'] = 1;

            if (!empty($linkFiles) && in_array($linkFiles->getClientOriginalExtension(), $valid_image_extension)) {
                $uploadedLinkFiles = $linkFiles->storeAs($linkFilesDir, uniqid().'.'.$linkFiles->getClientOriginalExtension());

                $product['upload_link_files'] = $uploadedLinkFiles;
            } else {
                session()->flash('error', trans('bulkupload::app.shop.message.file-format-error'));

                return redirect()->route('admin.bulk-upload.index');
            }

            if ($request->is_link_have_sample) {
                $product['is_links_have_samples'] = 1;

                if (in_array($linkSampleFiles->getClientOriginalExtension(), $valid_image_extension)) {
                    $uploadedLinkSampleFiles = $linkSampleFiles->storeAs($linkSampleFilesDir, uniqid().'.'.$linkSampleFiles->getClientOriginalExtension());

                    $product['upload_link_sample_files'] = $uploadedLinkSampleFiles;
                } else {
                    session()->flash('error', trans('bulkupload::app.shop.message.file-format-error'));

                    return redirect()->route('admin.bulk-upload.index');
                }
            }

            if ($request->is_sample) {
                $product['is_samples_available'] = 1;

                if (in_array($sampleFile->getClientOriginalExtension(), $valid_image_extension)) {
                    $uploadedSampleFiles = $sampleFile->storeAs($sampleFileDir, uniqid().'.'.$sampleFile->getClientOriginalExtension());

                    $product['upload_sample_files'] = $uploadedSampleFiles;
                } else {
                    session()->flash('error', trans('bulkupload::app.shop.message.file-format-error'));

                    return redirect()->route('admin.bulk-upload.index');
                }
            }
        }

        $product['data_flow_profile_id'] = $data_flow_profile_id;
        $product['attribute_family_id'] = $attribute_family_id;

        if (( !empty($image) && in_array($image->getClientOriginalExtension(), $valid_image_extension)) && (in_array($file->getClientOriginalExtension(), $valid_extension))) {
            $uploadedImage = $image->storeAs($imageDir, uniqid().'.'.$image->getClientOriginalExtension());

            $product['image_path'] = $uploadedImage;

            $uploadedFile = $file->storeAs($fileDir, uniqid().'.'.$file->getClientOriginalExtension());

            $product['file_path'] = $uploadedFile;
        } else if ( empty($image) && (in_array($file->getClientOriginalExtension(), $valid_extension))) {
            $product['image_path'] = '';

            $uploadedFile = $file->storeAs($fileDir, uniqid().'.'.$file->getClientOriginalExtension());

            $product['file_path'] = $uploadedFile;
        } else {
            session()->flash('error', trans('bulkupload::app.shop.message.file-format-error'));

            return redirect()->route('admin.bulk-upload.index');
        }

        if ($data_flow_profile_id) {
            $data = $this->importNewProducts->findOneByField('data_flow_profile_id', $data_flow_profile_id);

            if ($data) {
                $this->adminDataFlowProfileRepository->update(['run_status' => '0'], $data_flow_profile_id);

                $this->importNewProducts->Update($product, $data->id);

                Session()->flash('success',trans('bulkupload::app.shop.profile.edit-success'));

                return redirect()->route('admin.bulk-upload.index');
            } else {
                $importNewProductsStore = $this->importNewProducts->create($product);

                Session()->flash('success',trans('bulkupload::app.shop.profile.success'));

                return redirect()->route('admin.bulk-upload.index');
            }

        } else {
            session()->flash('error', trans('bulkupload::app.shop.message.data-profile-not-selected'));

            return back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_name' => 'required|unique:bulkupload_dataflowprofile',
            'attribute_family' => 'required'
        ]);

        $dataFlowProfileAdmin = new DataFlowProfile();
        $dataFlowProfileAdmin->profile_name = $request->profile_name;
        $dataFlowProfileAdmin->attribute_family_id = $request->attribute_family;
        $dataFlowProfileAdmin->seller_id = "admin";
        $dataFlowProfileAdmin->save();

        Session()->flash('success',trans('bulkupload::app.shop.profile.success'));

        return redirect()->route('admin.dataflow-profile.index');
    }

    public function runProfile(Request $request)
    {
        $data_flow_profile_id = request()->data_flow_profile_id;
        $numberOfCSVRecord = request()->numberOfCSVRecord;
        $countOfStartedProfiles = request()->countOfStartedProfiles;
        $product = array();
        $imageZipName = null;

        $dataFlowProfileRecord = $this->importNewProducts->findOneByField
        ('data_flow_profile_id', $data_flow_profile_id);

        if ($dataFlowProfileRecord) {

            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            if (isset($dataFlowProfileRecord->image_path) && ($dataFlowProfileRecord->image_path != "") ) {
                $imageZip = new \ZipArchive();

                $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/');

                if ($imageZip->open(storage_path('app/public/'.$dataFlowProfileRecord->image_path))) {
                    for ($i = 0; $i < $imageZip->numFiles; $i++) {
                        $filename = $imageZip->getNameIndex($i);
                        $imageZipName = pathinfo($filename);
                    }

                    $imageZip->extractTo($extractedPath);
                    $imageZip->close();
                }

                $listOfImages = scandir($extractedPath.$imageZipName['dirname'].'/');

                foreach ($listOfImages as $key => $imageName)
                {
                    if (preg_match_all('/[\'"]/', $imageName)) {
                        $fileName = preg_replace('/[\'"]/', '',$imageName);

                        rename($extractedPath.$imageZipName['dirname'].'/'.$imageName, $extractedPath.$imageZipName['dirname'].'/'.$fileName);
                    }
                }
            }

            foreach ($csvData as $key => $value)
            {
                if ($numberOfCSVRecord >= 0) {
                    for ($i = $countOfStartedProfiles; $i < count($csvData); $i++)
                    {
                        $product['loopCount'] = $i;

                        switch($csvData[$i]['type']) {
                            case "simple":
                                $simpleProduct = $this->simpleProductRepository->createProduct(request()->all(), $imageZipName, $product);

                                return response()->json($simpleProduct);

                            case "virtual":
                                $virtualProduct = $this->virtualProductRepository->createProduct(request()->all(), $imageZipName);

                                return response()->json($virtualProduct);
                            case "downloadable":
                                $downloadableProduct =  $this->downloadableProductRepository->createProduct(request()->all(), $imageZipName);

                                return response()->json($downloadableProduct);
                            case "grouped":
                                $groupedProduct = $this->groupedProductRepository->createProduct(request()->all(), $imageZipName);

                                return response()->json($groupedProduct);
                            case "booking":
                                $bookingProduct = $this->bookingProductRepository->createProduct(request()->all(), $imageZipName);

                                return response()->json($bookingProduct);
                            case "bundle":
                                $bundledProduct = $this->bundledProductRepository->createProduct(request()->all(), $imageZipName);

                                return response()->json($bundledProduct);
                            case "configurable" OR "variant":
                                $configurableProduct = $this->configurableProductRepository->createProduct(request()->all(), $imageZipName, $product);

                                return response()->json($configurableProduct);
                        }
                    }
                } else {
                    return response()->json([
                        "success" => true,
                        "message" => "CSV Product Successfully Imported"
                    ]);
                }
            }
        }
    }

    public function createProductValidation($record)
    {
        try {
            $validateProduct = Validator::make($record, [
                'type' => 'required',
                'sku' => 'required',
                'attribute_family_name' => 'required'
            ]);

            if ($validateProduct->fails()) {
                $errors = $validateProduct->errors()->getMessages();

                request()->countOfStartedProfiles +=  1;

                foreach($errors as $key => $error)
                {
                    $errorToBeReturn[] = $error[0]. " for record " .request()->countOfStartedProfiles;
                }

                $productUploadedWithError = request()->productUploaded + 1;

                if (request()->numberOfCSVRecord != 0) {
                    $remainDataInCSV = request()->totalNumberOfCSVRecord - $productUploadedWithError;
                } else {
                    $remainDataInCSV = 0;
                }

                $dataToBeReturn = array(
                    'remainDataInCSV' => $remainDataInCSV,
                    'productsUploaded' => $productUploadedWithError,
                    'countOfStartedProfiles' => request()->countOfStartedProfiles,
                    'error' => $errorToBeReturn,
                );

                return $dataToBeReturn;
            }

            return null;
        } catch(\EXception $e) {

        }
    }

    // public function runProfiler(Request $request)
    // {
    //     $data_flow_profile_id = request()->data_flow_profile_id;
    //     $numberOfCSVRecord = request()->numberOfCSVRecord;
    //     $countOfStartedProfiles = request()->countOfStartedProfiles;
    //     $totalNumberOfCSVRecord = request()->totalNumberOfCSVRecord;
    //     $productUploaded = request()->productUploaded;
    //     $errorCount = request()->errorCount;
    //     $product = array();
    //     $dataToBeReturn = array();
    //     $categoryID = array();
    //     $error = null;
    //     $productSample = null;
    //     $categoryError = null;
    //     $imageZipName = null;
    //     $ids = [];
    //     $urlKeyOfCSV = "";
    //     $CSVRecordErrors = [];
    //     $urlKeyValidation = null;
    //     $listOfUrlKey = [];

    //     if ($totalNumberOfCSVRecord < 1000) {
    //         $processCSVRecords = $totalNumberOfCSVRecord/($totalNumberOfCSVRecord/10);
    //     } else {
    //         $processCSVRecords = $totalNumberOfCSVRecord/($totalNumberOfCSVRecord/100);
    //     }

    //     $dataFlowProfileRecord = $this->importNewProducts->findOneByField
    //     ('data_flow_profile_id', $data_flow_profile_id);

    //     if ($dataFlowProfileRecord) {

    //         $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

    //         if (isset($dataFlowProfileRecord->image_path) && ($dataFlowProfileRecord->image_path != "") ) {
    //             $imageZip = new \ZipArchive();

    //             $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/');

    //             if ($imageZip->open(storage_path('app/public/'.$dataFlowProfileRecord->image_path))) {
    //                 for ($i = 0; $i < $imageZip->numFiles; $i++) {
    //                     $filename = $imageZip->getNameIndex($i);
    //                     $imageZipName = pathinfo($filename);
    //                 }

    //                 $imageZip->extractTo($extractedPath);
    //                 $imageZip->close();
    //             }
    //         }

    //         foreach ($csvData as $key => $value)
    //         {
    //             if ($numberOfCSVRecord >= 0) {
    //                 for ($i = $countOfStartedProfiles; $i < count($csvData); $i++)
    //                 {
    //                     $product['loopCount'] = $i;

    //                     switch($csvData[$i]['type']) {
    //                         case "simple":
    //                             $this->simpleProductRepository->createProduct(request()->all());
    //                             break;
    //                         case "configurable":
    //                             $this->configurableProductRepository->createProduct();
    //                             break;
    //                         case "virtual":
    //                             $this->virtualProductRepository->createProduct();
    //                             break;
    //                         case "downloadable":
    //                             $this->downloadableProductRepository->createProduct();
    //                             break;
    //                         case "grouped":
    //                             $this->groupedProductRepository->createProduct();
    //                             break;
    //                         case "booking":
    //                             $this->bookingProductRepository->createProduct();
    //                             break;
    //                         case "bundled":
    //                             $this->bundledProductRepository->createProduct();
    //                             break;
    //                     }
    //                 }

    //                 if ($errorCount == 0) {
    //                     $dataToBeReturn = [
    //                         'remainDataInCSV' => 0,
    //                         'productsUploaded' => $totalNumberOfCSVRecord,
    //                         'countOfStartedProfiles' => count($csvData),
    //                     ];

    //                     return response()->json($dataToBeReturn);
    //                 } else if ($totalNumberOfCSVRecord == $errorCount) {
    //                     $dataToBeReturn = [
    //                         'remainDataInCSV' => 0,
    //                         'productsUploaded' => 0,
    //                         'countOfStartedProfiles' => count($csvData),
    //                     ];

    //                     return response()->json($dataToBeReturn);
    //                 }

    //             } else {
    //                 return response()->json([
    //                     "success" => true,
    //                     "message" => "CSV Product Successfully Imported"
    //                 ]);
    //             }
    //         }
    //     }
    // }

    // public function validateCSV($dataFlowProfileId, $records, $dataFlowProfileRecord, $simpleproductDataId)
    // {
    //     $messages = [];

    //     $profiler = $this->adminDataFlowProfileRepository->findOneByField('id', $dataFlowProfileId);

    //     if ($dataFlowProfileRecord) {
    //         $records = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];
    //         foreach($records as $data) {
    //             $validate = Validator::make($data, [
    //                 'sku' => ['required', 'unique:products,sku,' . $simpleproductDataId, new \Webkul\Core\Contracts\Validations\Slug],
    //                 'variants.*.name' => 'required',
    //                 'variants.*.sku' => 'required',
    //                 'variants.*.price' => 'required',
    //                 'variants.*.weight' => 'required',
    //                 'images.*' => 'mimes:jpeg,jpg,bmp,png',
    //             ]);
    //             if (isset($validate) && $validate->fails()) {
    //                 continue;
    //             } else {
    //                 $attributeFamily = $profiler->attribute_family;

    //                 $attributes = $attributeFamily->custom_attributes;

    //                 foreach ($attributes as $attribute) {
    //                     if ($attribute->code == 'sku') {
    //                         continue;
    //                     }

    //                     if ($data['type'] == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
    //                         continue;
    //                     }

    //                     $validations = [];

    //                     if ($attribute->is_required) {
    //                         array_push($validations, 'required');
    //                     } else {
    //                         array_push($validations, 'nullable');
    //                     }

    //                     if ($attribute->type == 'text' && $attribute->validation) {
    //                         if ($attribute->validation == 'decimal') {
    //                             array_push($validations, new \Webkul\Core\Contracts\Validations\Decimal);
    //                         } else {
    //                             array_push($validations, $attribute->validation);
    //                         }
    //                     }

    //                     if ($attribute->type == 'price') {
    //                         array_push($validations, new \Webkul\Core\Contracts\Validations\Decimal);
    //                     }



    //                     if ($attribute->is_unique) {
    //                         $this->id = $simpleproductDataId;

    //                         array_push($validations, function ($field, $value, $fail) use ($data, $attribute) {
    //                             $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];
    //                             if (! $this->productAttributeValueRepository->isValueUnique($this->id, $attribute->id, $column, $data[$attribute->code])) {
    //                                 $fail('The :attribute has already been taken.');
    //                             }
    //                         });
    //                     }
    //                     $this->rules[$attribute->code] = $validations;
    //                 }
    //             }
    //         }
    //     }

    //     $validationCheckForUpdateData = $this->productFlatRepository->findWhere(['sku' => $data['sku'], 'url_key' => $data['url_key']]);

    //     if (! isset($validationCheckForUpdateData) || empty($validationCheckForUpdateData)) {
    //         $urlKeyUniqueness = "unique:product_flat,url_key";
    //         array_push($this->rules["url_key"], $urlKeyUniqueness);
    //     }

    //     return $this->rules;
    // }

    // public function updateValidatedCSVRecords($validationFormRequest, $id)
    // {
    //     $product = $this->product->update($validationFormRequest, $id);
    // }

    public function readCSVData(Request $request)
    {
        $countCSV = 0;
        $urlKey = "";
        $data_flow_profile_id = request()->data_flow_profile_id;
        $dataFlowProfileRecord = $this->importNewProducts->findOneByField('data_flow_profile_id', $data_flow_profile_id);

        $this->adminDataFlowProfileRepository->update(['run_status' => '1'], $data_flow_profile_id);

        if ($dataFlowProfileRecord) {
            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            for ($i = 0; $i < count($csvData); $i++)
            {
                if ($csvData[$i]['type'] == 'configurable') {
                    $countCSV += 1;
                } else if ($csvData[0]['type'] != 'configurable') {
                    $countCSV = count($csvData);
                }
            }

            return $countCSV;
        } else {
            return response()->json([
                "error" => true,
                "message" => "Record Not Found"
            ]);
        }
    }

    // public function createFlat($product, $parentProduct = null)
    // {
    //     static $familyAttributes = [];

    //     static $superAttributes = [];

    //     if (! array_key_exists($product->attribute_family->id, $familyAttributes))
    //         $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;

    //     if ($parentProduct && ! array_key_exists($parentProduct->id, $superAttributes))
    //         $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();

    //     foreach (core()->getAllChannels() as $channel) {
    //         foreach ($channel->locales as $locale) {
    //             $productFlat = $this->productFlatRepository->findOneWhere([
    //                 'product_id' => $product->id,
    //                 'channel' => $channel->code,
    //                 'locale' => $locale->code
    //             ]);

    //             if (! $productFlat) {
    //                 $productFlat = $this->productFlatRepository->create([
    //                     'product_id' => $product->id,
    //                     'channel' => $channel->code,
    //                     'locale' => $locale->code
    //                 ]);
    //             }
    //             foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
    //                 if ($parentProduct && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], ['sku', 'name', 'price', 'weight', 'status'])))
    //                     continue;

    //                 if (in_array($attribute->code, ['tax_category_id']))
    //                     continue;

    //                 if (! Schema::hasColumn('product_flat', $attribute->code))
    //                     continue;

    //                 if ($attribute->value_per_channel) {
    //                     if ($attribute->value_per_locale) {
    //                         $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
    //                     } else {
    //                         $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('attribute_id', $attribute->id)->first();
    //                     }
    //                 } else {
    //                     if ($attribute->value_per_locale) {
    //                         $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
    //                     } else {
    //                         $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
    //                     }
    //                 }

    //                 if ($product->type == 'configurable' && $attribute->code == 'price') {
    //                     $productFlat->{$attribute->code} = app('Webkul\Product\Helpers\Price')->getVariantMinPrice($product);
    //                 } else {
    //                     $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
    //                 }

    //                 if ($attribute->type == 'select') {
    //                     $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

    //                     if ($attributeOption) {
    //                         if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
    //                             $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
    //                         } else {
    //                             $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
    //                         }
    //                     }
    //                 } elseif ($attribute->type == 'multiselect') {
    //                     $attributeOptionIds = explode(',', $product->{$attribute->code});

    //                     if (count($attributeOptionIds)) {
    //                         $attributeOptions = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);

    //                         $optionLabels = [];

    //                         foreach ($attributeOptions as $attributeOption) {
    //                             if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
    //                                 $optionLabels[] = $attributeOptionTranslation->label;
    //                             } else {
    //                                 $optionLabels[] = $attributeOption->admin_name;
    //                             }
    //                         }

    //                         $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
    //                     }
    //                 }
    //             }

    //             $productFlat->created_at = $product->created_at;

    //             $productFlat->updated_at = $product->updated_at;

    //             if ($parentProduct) {
    //                 $parentProductFlat = $this->productFlatRepository->findOneWhere([

    //                         'product_id' => $parentProduct->id,
    //                         'channel' => $channel->code,
    //                         'locale' => $locale->code
    //                     ]);
    //             }
    //             $productFlat->parent_id = $product->parent_id;

    //             $productFlat->save();
    //         }
    //     }
    // }

    public function edit($id)
    {
        $families = $this->attributeFamily->all();
        $profiles = $this->dataFlowProfile->findOrFail($id);
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
        $profiles = $this->dataFlowProfile->findOrFail($id);

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

    // public function deleteProductIfNotValidated($id)
    // {
    //     $this->productRepository->findOrFail($id)->delete();
    // }

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