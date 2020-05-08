<?php

namespace Webkul\Bulkupload\Http\Controllers\Admin;

use File;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Webkul\Admin\Imports\DataGridImport;
use Maatwebsite\Excel\Validators\Failure;
use Webkul\Bulkupload\Repositories\Products\SimpleProductRepository;
use Webkul\Bulkupload\Repositories\Products\ConfigurableProductRepository;
use Webkul\Bulkupload\Repositories\Products\VirtualProductRepository;
use Webkul\Bulkupload\Repositories\Products\DownloadableProductRepository;
use Webkul\Bulkupload\Repositories\Products\GroupedProductRepository;
use Webkul\Bulkupload\Repositories\Products\BundledProductRepository;
use Webkul\Bulkupload\Repositories\Products\BookingProductRepository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Bulkupload\Repositories\DataFlowProfileRepository as AdminDataFlowProfileRepository;
// use Webkul\Product\Models\Product;
// use Webkul\Product\Models\ProductAttributeValue;

class HelperController extends Controller
{
    protected $product = [];

    protected $_config;

    /**
     * adminDataFlowProfileRepository object
     *
     */
    protected $adminDataFlowProfileRepository;

    /**
     * simpleProductRepository object
     *
     */
    protected $simpleProductRepository;

    /**
     * configurableProductRepository object
     *
     */
    protected $configurableProductRepository;

    /**
     * virtualProductRepository object
     *
     */
    protected $virtualProductRepository;

    /**
     * downloadableProductRepository object
     *
     */
    protected $downloadableProductRepository;

    /**
     * bundledProductRepository object
     *
     */
    protected $bundledProductRepository;

    /**
     * bookingProductRepository object
     *
     */
    protected $bookingProductRepository;

    /**
     * groupedProductRepository object
     *
     */
    protected $groupedProductRepository;

    public function __construct(
        ImportNewProducts $importNewProducts,
        AdminDataFlowProfileRepository $adminDataFlowProfileRepository,
        SimpleProductRepository $simpleProductRepository,
        ConfigurableProductRepository $configurableProductRepository,
        VirtualProductRepository $virtualProductRepository,
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

        $this->importNewProducts = $importNewProducts;

        $this->adminDataFlowProfileRepository = $adminDataFlowProfileRepository;

        $this->_config = request('_config');
    }

    /**
     * Download sample files
     */
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

    /**
     * Get profiles on basis of attribute family
     */
    public function getAllDataFlowProfiles()
    {
        $attribute_family_id = request()->attribute_family_id;

        $dataFlowProfiles = $this->adminDataFlowProfileRepository->findByField('attribute_family_id', request()->attribute_family_id);

        return ['dataFlowProfiles' => $dataFlowProfiles];
    }

    /**
     * Read count of records in CSV/XLSX
     */
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

    /**
     * profile execution
     */
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
}