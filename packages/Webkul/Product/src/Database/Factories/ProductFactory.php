<?php

namespace Webkul\Bulkupload\Http\Controllers\Admin;

use Webkul\Attributes\Repositores\AtrributeFamilyRepository;

class ProductFaker
{
    protected $attributeFamily;

    protected $product = [];

    protected $productRepository;

    protected $productImage;

    protected $_config;

    protected $attribute;

    protected $bulkUploadImages;

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
    protected $dataFlowProfileAdmin;

    /**
     * dataFlowProfileRepository object
     *
     * @var array
     */
    // protected $dataFlowProfileRepository;

    public function __construct(
        AttributeFamily $attributeFamily, DataFlowProfile $dataFlowProfile,DataFlowProfileAdmin $dataFlowProfileAdmin,
        ImportNewProducts $importNewProducts, ProductRepository $productRepository,ProductImageRepository $productImage, AttributeRepository $attribute, ProductFlatRepository $productFlatRepository,
        ProductAttributeValueRepository $productAttributeValueRepository, AttributeOptionRepository $attributeOptionRepository,
        BulkUploadImages $bulkUploadImages, SellerProduct $sellerProduct,
        CategoryRepository $categoryRepository)
    {
        $this->attributeFamily = $attributeFamily;

        $this->dataFlowProfile = $dataFlowProfile;

        $this->dataFlowProfileAdmin = $dataFlowProfileAdmin;

        $this->importNewProducts = $importNewProducts;

        $this->productRepository = $productRepository;

        $this->productImage = $productImage;

        $this->attribute = $attribute;

        $this->productFlatRepository = $productFlatRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->sellerProduct = $sellerProduct;

        $this->categoryRepository = $categoryRepository;

        // $this->dataFlowProfileRepository = $dataFlowProfileRepository;

        $this->_config = request('_config');
    }

    public function index()
    {
        $families = $this->attributeFamily->all();
        $profiles = $this->dataFlowProfileAdmin->all();
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
            return response()->download(public_path('downloads/sample-files/mpbulksimpleproductupload.csv'));
        } else if (request()->download_sample == 'configurable-csv') {
            return response()->download(public_path('downloads/sample-files/mpbulkconfigurableproductupload.csv'));
        } else if (request()->download_sample == 'simple-xls') {
            return response()->download(public_path('downloads/sample-files/mpbulksimpleproductupload.xls'));
        } else if (request()->download_sample == 'configurable-xls') {
            return response()->download(public_path('downloads/sample-files/mpbulkconfigurableproductupload.xls'));
        }
    }

    public function importNewProductsStore(Request $request)
    {
        $seller = $request->set_seller;
        $attribute_family_id = $request->attribute_family_id;
        $data_flow_profile_id = $request->data_flow_profile_id;

        $valid_extension = ['csv', 'xls'];
        $valid_image_extension = ['zip', 'rar'];

        $request->validate([
            'set_seller' => 'required',
            'attribute_family_id' => 'required',
            'file_path' => 'required',
            'image_path' => 'required',
        ]);

        $imageDir = 'imported-products/admin/images';
        $fileDir = 'imported-products/admin/files';

        $image = $request->file('image_path');
        $file = $request->file('file_path');

        $product['customer_id'] = $seller;
        $product['data_flow_profile_id'] = $data_flow_profile_id;
        $product['attribute_family_id'] = $attribute_family_id;

        if (in_array($image->getClientOriginalExtension(), $valid_image_extension) && (in_array($file->getClientOriginalExtension(), $valid_extension))) {
            $uploadedImage = $image->storeAs($imageDir, uniqid().'.'.$image->getClientOriginalExtension());

            $uploadedFile = $file->storeAs($fileDir, uniqid().'.'.$file->getClientOriginalExtension());

            $product['image_path'] = $uploadedImage;
            $product['file_path'] = $uploadedFile;
        } else {
            session()->flash('error', trans('admin::app.export.upload-error'));

            return redirect()->route('admin.marketplace.bulk-upload.index');
        }

        if ($data_flow_profile_id) {
            $data = $this->importNewProducts->findOneByField('data_flow_profile_id', $data_flow_profile_id);

            if ($data) {
                $this->importNewProducts->Update($product, $data->id);

                return redirect()->route('admin.marketplace.bulk-upload.index');
            } else {
                $importNewProductsStore = $this->importNewProducts->create($product);

                return redirect()->route('admin.marketplace.bulk-upload.index');
            }
        } else {
            dd("data_flow_profile_id Not Selected from Dropdown .Mark It required Field");
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_name' => 'required',
            'attribute_family_id' => 'required'
        ]);

        $dataFlowProfile = new DataFlowProfileAdmin();
        $dataFlowProfile->profile_name = $request->profile_name;
        $dataFlowProfile->attribute_family_id = $request->attribute_family_id;

        $dataFlowProfile->save();

        return redirect()->route('admin.marketplace.dataflow-profile.index');
    }

    public function runProfile()
    {
        $data_flow_profile_id = request()->data_flow_profile_id;
        $numberOfCSVRecord = request()->numberOfCSVRecord;
        $countOfStartedProfiles = request()->countOfStartedProfiles;
        $totalNumberOfCSVRecord = request()->totalNumberOfCSVRecord;
        $productUploaded = request()->productUploaded;
        $errorCount = request()->errorCount;
        $product = array();
        $dataToBeReturn = array();
        $categoryID = array();
        $error = null;
        $productSample = null;
        $imageZipName = '';

        if ($totalNumberOfCSVRecord < 1000) {
            $processCSVRecords = $totalNumberOfCSVRecord/($totalNumberOfCSVRecord/10);
        } else {
            $processCSVRecords = $totalNumberOfCSVRecord/($totalNumberOfCSVRecord/100);
        }

        $dataFlowProfileRecord = $this->importNewProducts->findOneByField
        ('data_flow_profile_id', $data_flow_profile_id);

        if ($dataFlowProfileRecord) {
            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            $imageZip = new \ZipArchive();

            $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/');

            if ($imageZip->open(storage_path('app/public/'.$dataFlowProfileRecord->image_path))) {
                for ($i = 0; $i < $imageZip->numFiles; $i++) {
                    $filename = $imageZip->getNameIndex($i);
                    $imageZipName = pathinfo($filename);
                }

                $imageZip->extractTo($extractedPath);
                $imageZip->close();
            } else {
                dd('false');
            }

            foreach ($csvData as $key => $value)
            {
                if ($numberOfCSVRecord >= 0) {
                    for ($i = $countOfStartedProfiles; $i < count($csvData); $i++)
                    {
                        $product['loopCount'] = $i;

                        if ($csvData[$i]['type'] == 'configurable') {
                            try {
                                $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

                                unset($data);
                                $data['type'] = $csvData[$i]['type'];
                                $data['attribute_family_id'] = $csvData[$i]['attribute_family_id'];
                                $data['sku'] = $csvData[$i]['sku'];

                                $product = $this->productRepository->create($data);
                                unset($data);

                                //necessary attributes for configurable product
                                $categoryData = explode(',', $csvData[$i]['categories_slug']);

                                foreach ($categoryData as $key => $value)
                                {
                                    $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                                }

                                $data['categories'] = $categoryID;
                                $data['name'] = $csvData[$i]['name'];
                                $data['sku'] = (string)$csvData[$i]['sku'];
                                $data['description'] = $csvData[$i]['description'];
                                $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                $data['url_key'] = $csvData[$i]['url_key'];
                                $data['channel'] = core()->getCurrentChannel()->code;
                                $data['locale'] = core()->getCurrentLocale()->code;
                                $data['new'] = (string)$csvData[$i]['new'];
                                $data['price'] = (string)$csvData[$i]['price'];
                                $data['meta_title'] = (string)$csvData[$i]['meta_title'];
                                $data['meta_keywords'] = (string)$csvData[$i]['meta_keyword'];
                                $data['meta_description'] = (string)$csvData[$i]['meta_description'];
                                $data['featured'] = (string)$csvData[$i]['featured'];
                                $data['visible_individually'] = (string)$csvData[$i]['visible_individually'];
                                $data['tax_category_id'] = (string)$csvData[$i]['tax_category_id'];
                                $data['status'] = (string)$csvData[$i]['status'];
                                $data['attribute_family_id'] = (string)$csvData[$i]['attribute_family_id'];
                                $data['short_description'] = (string)$csvData[$i]['short_description'];

                                //Product Images
                                $individualProductimages = explode(',', $csvData[$i]['images']);

                                $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                                foreach ($images as $imageArraykey => $imagePath)
                                {
                                    $imageName = explode('/', $imagePath);

                                    if (in_array(last($imageName), $individualProductimages)) {
                                        $data['images'][$imageArraykey] = $imagePath;
                                    }
                                }
                                //end necessary attributes for config product

                                $productAttributeStore = $this->productRepository->update($data, $product->id);

                                $this->bulkUploadImages->bulkuploadImages($data, $product, $imageZipName);

                                $productFlatData = DB::table('product_flat')->select('id')->orderBy('id', 'desc')->first();

                                $product['productFlatId'] = $productFlatData->id;

                                $arr[] = $productFlatData->id;
                            } catch (\Exception $e) {
                                $error = $e;
                                $productUploadedWithError = $productUploaded + 1;
                                $remainDataInCSV = $totalNumberOfCSVRecord - $productUploadedWithError;
                                $countOfStartedProfiles = $i + 1;

                                $dataToBeReturn = array(
                                    'remainDataInCSV' => $remainDataInCSV,
                                    'productsUploaded' => $productUploaded,
                                    'countOfStartedProfiles' => $countOfStartedProfiles,
                                    'error' => $error->errorInfo[2],
                                );

                                return response()->json($dataToBeReturn);
                            }
                        } else if (isset($product['productFlatId'])) {
                            try {
                                $current = $product['loopCount'];
                                $num = 0;
                                $inventory = [];

                                $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

                                for ($i = $current; $i < count($csvData); $i++)
                                {
                                    $product['loopCount'] = $i;

                                    if ($csvData[$i]['type'] != 'configurable') {
                                        unset($data);
                                        $data['parent_id'] = $product->id;
                                        $data['type'] = $csvData[$i]['type'];
                                        $data['attribute_family_id'] = $csvData[$i]['attribute_family_id'];
                                        $data['sku'] = $csvData[$i]['sku'];

                                        $configSimpleproduct = $this->productRepository->create($data);
                                        unset($data);

                                        $inventory_data = core()->getCurrentChannel()->inventory_sources;

                                        foreach($inventory_data as $key => $datas)
                                        {
                                            $inventoryId = $datas->id;
                                        }

                                        $inventoryData[] = (string)$csvData[$i]['super_attribute_qty'];

                                        foreach ($inventoryData as $key => $d)
                                        {
                                            $inventory[$inventoryId] = $d;
                                        }

                                        $data['inventories'] =  $inventory;

                                        $superAttributes = explode(',', $csvData[$i]['super_attributes']);
                                        $superAttributesOption = explode(',', $csvData[$i]['super_attribute_option']);

                                        $data['super_attributes'] = array_combine($superAttributes, $superAttributesOption);

                                        if (isset($data['super_attributes']) && $i == $current) {
                                            $super_attributes = [];

                                            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                                                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                                                $super_attributes[$attribute->id] = $attributeOptions;

                                                $product->super_attributes()->attach($attribute->id);
                                            }
                                        }

                                        $data['channel'] = core()->getCurrentChannel()->code;
                                        $data['locale'] = core()->getCurrentLocale()->code;
                                        $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                        $data['price'] = (string)$csvData[$i]['super_attribute_price'];
                                        $data['special_price'] = (string)$csvData[$i]['special_price'];
                                        $data['new'] = (string)$csvData[$i]['new'];
                                        $data['featured'] = (string)$csvData[$i]['featured'];
                                        $data['visible_individually'] = (string)$csvData[$i]['visible_individually'];
                                        $data['tax_category_id'] = (string)$csvData[$i]['tax_category_id'];
                                        $data['cost'] = (string)$csvData[$i]['cost'];
                                        $data['width'] = (string)$csvData[$i]['width'];
                                        $data['height'] = (string)$csvData[$i]['height'];
                                        $data['depth'] = (string)$csvData[$i]['depth'];
                                        $data['status'] = (string)$csvData[$i]['status'];
                                        $data['attribute_family_id'] = (string)$csvData[$i]['attribute_family_id'];
                                        $data['short_description'] = (string)$csvData[$i]['short_description'];
                                        $data['sku'] = (string)$csvData[$i]['sku'];
                                        $data['name'] = (string)$csvData[$i]['name'];
                                        $data['color'] = $superAttributesOption[0];
                                        $data['size'] = $superAttributesOption[1];
                                        $data['weight'] = (string)$csvData[$i]['super_attribute_weight'];
                                        $data['status'] = (string)$csvData[$i]['status'];

                                        $configSimpleProductAttributeStore = $this->productRepository->update($data, $configSimpleproduct->id);

                                        $configSimpleProductAttributeStore['parent_id'] = $product['productFlatId'];

                                        $this->createFlat($configSimpleProductAttributeStore);
                                    } else {
                                        $savedProduct = $productUploaded + 1;
                                        $remainDataInCSV = $totalNumberOfCSVRecord - $savedProduct;
                                        $productsUploaded = $savedProduct;

                                        $countOfStartedProfiles = $product['loopCount'];

                                        $dataToBeReturn = array(
                                            'remainDataInCSV' => $remainDataInCSV,
                                            'productsUploaded' => $productsUploaded,
                                            'countOfStartedProfiles' => $countOfStartedProfiles
                                        );

                                        return response()->json($dataToBeReturn);
                                    }
                                }

                                if ($errorCount == 0) {
                                    $dataToBeReturn = [
                                        'remainDataInCSV' => 0,
                                        'productsUploaded' => $totalNumberOfCSVRecord,
                                        'countOfStartedProfiles' => count($csvData),
                                    ];

                                    return response()->json($dataToBeReturn);
                                } else {
                                    $dataToBeReturn = [
                                        'remainDataInCSV' => 0,
                                        'productsUploaded' => $totalNumberOfCSVRecord - $errorCount,
                                        'countOfStartedProfiles' => count($csvData),
                                    ];

                                    return response()->json($dataToBeReturn);
                                }

                                $product['productFlatId'] = null;
                            } catch (\Exception $e) {
                                $error = $e;
                                $countOfStartedProfiles = $i + 1;
                                $remainDataInCSV = $totalNumberOfCSVRecord - $productUploaded;

                                $dataToBeReturn = array(
                                    'remainDataInCSV' => $remainDataInCSV,
                                    'productsUploaded' => $productUploaded,
                                    'countOfStartedProfiles' => $countOfStartedProfiles,
                                    'error' => $error->errorInfo[2],
                                );

                                return response()->json($dataToBeReturn);
                            }
                        } else if ($csvData[$i]['type'] == "simple" && empty($csvData[$i]['super_attribute_option'] )) {
                            try  {
                                $uptoProcessCSVRecords = (int)$countOfStartedProfiles + 10;
                                $processRecords = (int)$countOfStartedProfiles + (int)$numberOfCSVRecord;
                                $inventory = [];

                                if ($numberOfCSVRecord > $processCSVRecords) {
                                    for ($i = $countOfStartedProfiles; $i < $uptoProcessCSVRecords; $i++)
                                    {
                                        $data['type'] = $csvData[$i]['type'];
                                        $data['attribute_family_id'] = $csvData[$i]['attribute_family_id'];
                                        $data['sku'] = $csvData[$i]['sku'];

                                        $simpleproductData = $this->productRepository->create($data);

                                        unset($data);

                                        $inventory_data = core()->getCurrentChannel()->inventory_sources;

                                        foreach($inventory_data as $key => $datas)
                                        {
                                            $inventoryId = $datas->id;
                                        }

                                        $inventoryData[] = (string)$csvData[$i]['inventories'];

                                        foreach ($inventoryData as $key => $d)
                                        {
                                            $inventory[$inventoryId] = $d;
                                        }

                                        $data['inventories'] =  $inventory;

                                        $categoryData = explode(',', $csvData[$i]['categories_slug']);

                                        foreach ($categoryData as $key => $value)
                                        {
                                            $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                                        }

                                        $data['categories'] = $categoryID;
                                        $data['channel'] = core()->getCurrentChannel()->code;
                                        $data['locale'] = core()->getCurrentLocale()->code;
                                        $data['description'] = $csvData[$i]['description'];
                                        $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                        $data['url_key'] = $csvData[$i]['url_key'];
                                        $data['name'] = (string)$csvData[$i]['name'];
                                        $data['sku'] = (string)$csvData[$i]['sku'];
                                        $data['price'] = (string)$csvData[$i]['price'];
                                        $data['weight'] = (string)$csvData[$i]['weight'];
                                        $data['new'] = (string)$csvData[$i]['new'];
                                        $data['meta_title'] = (string)$csvData[$i]['meta_title'];
                                        $data['meta_keywords'] = (string)$csvData[$i]['meta_keyword'];
                                        $data['meta_description'] = (string)$csvData[$i]['meta_description'];
                                        $data['featured'] = (string)$csvData[$i]['featured'];
                                        $data['tax_category_id'] = (string)$csvData[$i]['tax_category_id'];
                                        $data['status'] = (string)$csvData[$i]['status'];
                                        $data['cost'] = (string)$csvData[$i]['cost'];
                                        $data['width'] = (string)$csvData[$i]['width'];
                                        $data['height'] = (string)$csvData[$i]['height'];
                                        $data['depth'] = (string)$csvData[$i]['depth'];
                                        $data['attribute_family_id'] = (string)$csvData[$i]['attribute_family_id'];
                                        $data['short_description'] = (string)$csvData[$i]['short_description'];
                                        $data['visible_individually'] = (string)$csvData[$i]['visible_individually'];

                                        //Product Images
                                        $individualProductimages = explode(',', $csvData[$i]['images']);

                                        $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                                        foreach ($images as $imageArraykey => $imagePath)
                                        {
                                            $imageName = explode('/', $imagePath);

                                            if (in_array(last($imageName), $individualProductimages)) {
                                                $data['images'][$imageArraykey] = $imagePath;
                                            }
                                        }

                                        $configSimpleProductAttributeStore = $this->productRepository->update($data, $simpleproductData->id);

                                        $this->bulkUploadImages->bulkuploadImages($data, $simpleproductData, $imageZipName);

                                        $simpleProductUploaded = count($simpleproductData);
                                    }
                                } else if ($numberOfCSVRecord <= 10) {
                                    for ($i = $countOfStartedProfiles; $i < $processRecords; $i++)
                                    {
                                        $data['type'] = $csvData[$i]['type'];
                                        $data['attribute_family_id'] = $csvData[$i]['attribute_family_id'];
                                        $data['sku'] = $csvData[$i]['sku'];

                                        $simpleproductData = $this->productRepository->create($data);

                                        unset($data);

                                        $inventory_data = core()->getCurrentChannel()->inventory_sources;

                                        foreach($inventory_data as $key => $datas)
                                        {
                                            $inventoryId = $datas->id;
                                        }

                                        $inventoryData[] = (string)$csvData[$i]['inventories'];

                                        foreach ($inventoryData as $key => $d)
                                        {
                                            $inventory[$inventoryId] = $d;
                                        }

                                        $data['inventories'] =  $inventory;

                                        $categoryData = explode(',', $csvData[$i]['categories_slug']);

                                        foreach ($categoryData as $key => $value)
                                        {
                                            $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                                        }
                                        $data['categories'] = $categoryID;

                                        $data['channel'] = core()->getCurrentChannel()->code;
                                        $data['locale'] = core()->getCurrentLocale()->code;
                                        $data['description'] = $csvData[$i]['description'];
                                        $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                        $data['url_key'] = $csvData[$i]['url_key'];
                                        $data['name'] = (string)$csvData[$i]['name'];
                                        $data['sku'] = (string)$csvData[$i]['sku'];
                                        $data['price'] = (string)$csvData[$i]['price'];
                                        $data['weight'] = (string)$csvData[$i]['weight'];
                                        $data['new'] = (string)$csvData[$i]['new'];
                                        $data['meta_title'] = (string)$csvData[$i]['meta_title'];
                                        $data['meta_keywords'] = (string)$csvData[$i]['meta_keyword'];
                                        $data['meta_description'] = (string)$csvData[$i]['meta_description'];
                                        $data['featured'] = (string)$csvData[$i]['featured'];
                                        $data['tax_category_id'] = (string)$csvData[$i]['tax_category_id'];
                                        $data['status'] = (string)$csvData[$i]['status'];
                                        $data['cost'] = (string)$csvData[$i]['cost'];
                                        $data['width'] = (string)$csvData[$i]['width'];
                                        $data['height'] = (string)$csvData[$i]['height'];
                                        $data['depth'] = (string)$csvData[$i]['depth'];
                                        $data['attribute_family_id'] = (string)$csvData[$i]['attribute_family_id'];
                                        $data['short_description'] = (string)$csvData[$i]['short_description'];
                                        $data['visible_individually'] = (string)$csvData[$i]['visible_individually'];

                                        //Product Images
                                        $individualProductimages = explode(',', $csvData[$i]['images']);

                                        $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                                        foreach ($images as $imageArraykey => $imagePath)
                                        {
                                            $imageName = explode('/', $imagePath);

                                            if (in_array(last($imageName), $individualProductimages)) {
                                                $data['images'][$imageArraykey] = $imagePath;
                                            }
                                        }

                                        $configSimpleProductAttributeStore = $this->productRepository->update($data, $simpleproductData->id);

                                        $this->bulkUploadImages->bulkuploadImages($data, $simpleproductData, $imageZipName);

                                        $simpleProductUploaded = count($simpleproductData);
                                    }
                                }

                                if ($numberOfCSVRecord > 10) {
                                    $remainDataInCSV = (int)$numberOfCSVRecord - (int)$processCSVRecords;
                                } else {
                                    $remainDataInCSV = 0;

                                    if($errorCount > 0) {
                                        $uptoProcessCSVRecords = $totalNumberOfCSVRecord - $errorCount;
                                    } else {
                                        $uptoProcessCSVRecords = $processRecords;
                                    }
                                }

                                $countOfStartedProfiles = $i;

                                $dataToBeReturn = [
                                    'remainDataInCSV' => $remainDataInCSV,
                                    'productsUploaded' => $uptoProcessCSVRecords,
                                    'countOfStartedProfiles' => $countOfStartedProfiles,
                                ];

                                return response()->json($dataToBeReturn);
                            } catch (\Exception $e) {
                                $error = $e;
                                $countOfStartedProfiles =  $i + 1;
                                $productsUploaded = $i - 1;

                                if ($numberOfCSVRecord != 0) {
                                    $remainDataInCSV = (int)$totalNumberOfCSVRecord - (int)$countOfStartedProfiles;
                                } else {
                                    $remainDataInCSV = 0;
                                }

                                $dataToBeReturn = array(
                                    'remainDataInCSV' => $remainDataInCSV,
                                    'productsUploaded' => $productsUploaded,
                                    'countOfStartedProfiles' => $countOfStartedProfiles,
                                    'error' => $error->errorInfo[2],
                                );

                                return response()->json($dataToBeReturn);
                            }
                        }
                        else {
                            $product['loopCount'] = $i + 1;
                            $countOfStartedProfiles = $product['loopCount'];
                            $savedProduct = $productUploaded + 1;
                            $remainDataInCSV = $totalNumberOfCSVRecord - $savedProduct;
                            $countOfStartedProfiles = $i + 1;

                            $dataToBeReturn = array(
                                'remainDataInCSV' => $remainDataInCSV,
                                'productsUploaded' => $productUploaded,
                                'countOfStartedProfiles' => $countOfStartedProfiles
                            );

                            return response()->json($dataToBeReturn);
                        }
                    }

                    if ($errorCount == 0) {
                        $dataToBeReturn = [
                            'remainDataInCSV' => 0,
                            'productsUploaded' => $totalNumberOfCSVRecord,
                            'countOfStartedProfiles' => count($csvData),
                        ];

                        return response()->json($dataToBeReturn);
                    }

                } else {
                    dd("No more records EXist");
                }
            }
        } else {
            dd("Record Doesn't EXist");
        }
    }


    public function readCSVData(Request $request)
    {
        $countCSV = 0;
        $data_flow_profile_id = request()->data_flow_profile_id;
        $dataFlowProfileRecord = $this->importNewProducts->findOneByField('data_flow_profile_id', $data_flow_profile_id);

        if ($dataFlowProfileRecord) {

            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            for ($i = 0; $i < count($csvData); $i++)
            {
                if ($csvData[$i]['type'] == 'configurable') {
                    $countCSV += 1;
                } else if($csvData[0]['type'] != 'configurable') {
                    $countCSV = count($csvData);
                }
            }

            return $countCSV;
        } else {
            dd("Records Doesn't Exist");
        }
    }

    // shop edit actions
    public function edit($id)
    {
        $families = $this->attributeFamily->all();
        $profiles = $this->dataFlowProfileAdmin->findOrFail($id);
        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'profiles', 'configurableFamily'));
    }

    public function update($id)
    {
        dd("jkd");
        $product = $this->dataFlowProfileAdmin->update(request()->except('_token'), $id);
        dd("jk");
        $families = $this->attributeFamily->all();
        $profiles = $this->dataFlowProfileAdmin->findOrFail($id);

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

       return view($this->_config['view'], compact('families', 'profiles', 'configurableFamily'));
    }

    public function destroy($id)
    {
        $product = $this->dataFlowProfileAdmin->findOrFail($id)->delete();

        return redirect()->route('admin.marketplace.dataflow-profile.index');
    }

    public function createFlat($product, $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        if (! array_key_exists($product->attribute_family->id, $familyAttributes))
            $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;

        if ($parentProduct && ! array_key_exists($parentProduct->id, $superAttributes))
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $productFlat = $this->productFlatRepository->findOneWhere([
                    'product_id' => $product->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code
                ]);

                if (! $productFlat) {
                    $productFlat = $this->productFlatRepository->create([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                        'locale' => $locale->code
                    ]);
                }
                foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
                    if ($parentProduct && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], ['sku', 'name', 'price', 'weight', 'status'])))
                        continue;

                    if (in_array($attribute->code, ['tax_category_id']))
                        continue;

                    if (! Schema::hasColumn('product_flat', $attribute->code))
                        continue;

                    if ($attribute->value_per_channel) {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('attribute_id', $attribute->id)->first();
                        }
                    } else {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                        }
                    }

                    if ($product->type == 'configurable' && $attribute->code == 'price') {
                        $productFlat->{$attribute->code} = app('Webkul\Product\Helpers\Price')->getVariantMinPrice($product);
                    } else {
                        $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
                    }

                    if ($attribute->type == 'select') {
                        $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

                        if ($attributeOption) {
                            if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                            } else {
                                $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                            }
                        }
                    } elseif ($attribute->type == 'multiselect') {
                        $attributeOptionIds = explode(',', $product->{$attribute->code});

                        if (count($attributeOptionIds)) {
                            $attributeOptions = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);

                            $optionLabels = [];

                            foreach ($attributeOptions as $attributeOption) {
                                if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                    $optionLabels[] = $attributeOptionTranslation->label;
                                } else {
                                    $optionLabels[] = $attributeOption->admin_name;
                                }
                            }

                            $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
                        }
                    }
                }

                $productFlat->created_at = $product->created_at;

                $productFlat->updated_at = $product->updated_at;

                if ($parentProduct) {
                    $parentProductFlat = $this->productFlatRepository->findOneWhere([

                            'product_id' => $parentProduct->id,
                            'channel' => $channel->code,
                            'locale' => $locale->code
                        ]);
                }
                $productFlat->parent_id = $product->parent_id;

                $productFlat->save();
            }
        }
    }
}