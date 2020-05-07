<?php

namespace Webkul\Bulkupload\Repositories\Products;

use Illuminate\Container\Container as App;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\Products\HelperRepository;
use Illuminate\Support\Facades\Validator;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Bulkupload\Repositories\ProductImageRepository as BulkUploadImages;
use Storage;
use Illuminate\Support\Facades\Log;

/**
 * BulkProduct Repository
 *
 * @author    Prateek Sivastava
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class VirtualProductRepository extends Repository
{
    protected $importNewProducts;

    protected $categoryRepository;

    protected $productFlatRepository;

    protected $productRepository;

    protected $attributeFamily;

    protected $helperRepository;

    protected $bulkUploadImages;

    protected $attributeOptionRepository;

    protected $inventorySource;

    public function __construct(
        ImportNewProducts $importNewProducts,
        CategoryRepository $categoryRepository,
        ProductFlatRepository $productFlatRepository,
        ProductRepository $productRepository,
        AttributeFamily $attributeFamily,
        HelperRepository $helperRepository,
        BulkUploadImages $bulkUploadImages,
        AttributeOptionRepository $attributeOptionRepository,
        InventorySourceRepository $inventorySource
       )
    {
        $this->importNewProducts = $importNewProducts;

        $this->categoryRepository = $categoryRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->productRepository = $productRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->attributeFamily = $attributeFamily;

        $this->helperRepository = $helperRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->inventorySource = $inventorySource;
    }

    /*
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     *
     */
    public function createProduct($requestData, $imageZipName)
    {
        try  {
            $dataFlowProfileRecord = $this->importNewProducts->findOneByField
            ('data_flow_profile_id', $requestData['data_flow_profile_id']);

            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            if ($requestData['totalNumberOfCSVRecord'] < 1000) {
                $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/10);
            } else {
                $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/100);
            }

            $uptoProcessCSVRecords = (int)$requestData['countOfStartedProfiles'] + 10;
            $processRecords = (int)$requestData['countOfStartedProfiles'] + (int)$requestData['numberOfCSVRecord'];

            $inventory = [];

            if ($requestData['numberOfCSVRecord'] > $processCSVRecords) {
                for ($i = $requestData['countOfStartedProfiles']; $i < $uptoProcessCSVRecords; $i++)
                {
                    $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

                    if (isset($createValidation)) {
                        return $createValidation;
                    }

                    $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => $csvData[$i]['url_key']])->first();

                    $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                    $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                    if (! isset($productFlatData) && empty($productFlatData)) {
                        $data['type'] = $csvData[$i]['type'];
                        $data['attribute_family_id'] = $attributeFamilyData->id;
                        $data['sku'] = $csvData[$i]['sku'];

                        $virtualProductData = $this->productRepository->create($data);
                    } else {
                        $virtualProductData = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($virtualProductData->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
                    {
                        $searchIndex = $value['code'];
                        if (array_key_exists($searchIndex, $csvData[$i])) {
                            array_push($attributeCode, $searchIndex);

                            if ($searchIndex == "color" || $searchIndex == "size" || $searchIndex == "brand") {
                                $attributeOption = $this->attributeOptionRepository->findOneByField(['admin_name' => ucwords($csvData[$i][$searchIndex])]);

                                array_push($attributeValue, $attributeOption['id']);
                             } else {
                                 array_push($attributeValue, $csvData[$i][$searchIndex]);
                             }

                            $data = array_combine($attributeCode, $attributeValue);
                        }
                    }

                    $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;

                    $inventorySource = $csvData[$i]['inventory_sources'];
                    $inventoryCode = explode(',', $inventorySource);

                    foreach ($inventoryCode as $key => $value) {
                        $inventoryId = $this->inventorySource->findOneByfield(['code' => trim($value)])->pluck('id')->toArray();
                    }

                    $inventoryData[] = (string)$csvData[$i]['inventories'];

                    foreach ($inventoryData as $key => $d) {
                        $inventoryQuantity = explode(',', trim($d));

                        if (count($inventoryId) != count($inventoryQuantity)) {
                            array_push($inventoryQuantity, "0");
                        }

                        $inventory = array_combine($inventoryId, $inventoryQuantity);
                    }

                    $data['inventories'] =  $inventory;

                    // $inventory_data = core()->getCurrentChannel()->inventory_sources;

                    // foreach($inventory_data as $key => $datas)
                    // {
                    //     $inventoryId = $datas->id;
                    // }

                    // $inventoryData[] = (string)$csvData[$i]['inventories'];

                    // foreach ($inventoryData as $key => $d)
                    // {
                    //     $inventory[$inventoryId] = $d;
                    // }

                    // $data['inventories'] =  $inventory;

                    $categoryData = explode(',', $csvData[$i]['categories_slug']);

                    foreach ($categoryData as $key => $value)
                    {
                        $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                    }
                    $data['categories'] = $categoryID;

                    $data['channel'] = core()->getCurrentChannel()->code;
                    $data['locale'] = core()->getCurrentLocale()->code;

                    //Product Images
                    $individualProductimages = explode(',', $csvData[$i]['images']);

                    if (isset($imageZipName)) {
                        $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                        foreach ($images as $imageArraykey => $imagePath)
                        {
                            $imageName = explode('/', $imagePath);

                            if (in_array(last($imageName), preg_replace('/[\'"]/', '',$individualProductimages))) {
                                $data['images'][$imageArraykey] = $imagePath;
                            }
                        }
                    }

                    //to check validation
                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $virtualProductData);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($virtualProductData->id);

                        foreach($errors as $key => $error)
                        {
                            if ($error[0] == "The url key has already been taken.") {
                                $errorToBeReturn[] = "The url key " . $data['url_key'] . " has already been taken";
                            } else {
                                $errorToBeReturn[] = $error[0] . " for sku " . $data['sku'];
                            }
                        }

                        $requestData['countOfStartedProfiles'] =  $i + 1;

                        $productsUploaded = $i - $requestData['errorCount'];

                        if ($requestData['numberOfCSVRecord'] != 0) {
                            $remainDataInCSV = (int)$requestData['totalNumberOfCSVRecord'] - (int)$requestData['countOfStartedProfiles'];
                        } else {
                            $remainDataInCSV = 0;
                        }

                        $dataToBeReturn = array(
                            'remainDataInCSV' => $remainDataInCSV,
                            'productsUploaded' => $productsUploaded,
                            'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                            'error' => $errorToBeReturn,
                        );

                        return $dataToBeReturn;
                    }

                    $this->productRepository->update($data, $virtualProductData->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $virtualProductData, $imageZipName);
                    }
                }
            } else if ($requestData['numberOfCSVRecord'] <= 10) {
                for ($i = $requestData['countOfStartedProfiles']; $i < $processRecords; $i++)
                {
                    $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

                    if (isset($createValidation)) {
                        return $createValidation;
                    }

                    $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => $csvData[$i]['url_key']])->first();

                    $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                    $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                    if (! isset($productFlatData) && empty($productFlatData)) {
                        $data['type'] = $csvData[$i]['type'];
                        $data['attribute_family_id'] = $attributeFamilyData->id;
                        $data['sku'] = $csvData[$i]['sku'];

                        $virtualProductData = $this->productRepository->create($data);
                    } else {
                        $virtualProductData = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($virtualProductData->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
                    {
                        $searchIndex = $value['code'];
                        if (array_key_exists($searchIndex, $csvData[$i])) {
                            array_push($attributeCode, $searchIndex);

                            if ($searchIndex == "color" || $searchIndex == "size" || $searchIndex == "brand") {
                                $attributeOption = $this->attributeOptionRepository->findOneByField(['admin_name' => ucwords($csvData[$i][$searchIndex])]);

                                array_push($attributeValue, $attributeOption['id']);
                            } else {
                                array_push($attributeValue, $csvData[$i][$searchIndex]);
                            }

                            $data = array_combine($attributeCode, $attributeValue);
                        }
                    }

                    $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;

                    $inventorySource = $csvData[$i]['inventory_sources'];
                    $inventoryCode = explode(',', $inventorySource);

                    foreach ($inventoryCode as $key => $value) {
                        $inventoryId = $this->inventorySource->findOneByfield(['code' => trim($value)])->pluck('id')->toArray();
                    }

                    $inventoryData[] = (string)$csvData[$i]['inventories'];

                    foreach ($inventoryData as $key => $d) {
                        $inventoryQuantity = explode(',', trim($d));

                        if (count($inventoryId) != count($inventoryQuantity)) {
                            array_push($inventoryQuantity, "0");
                        }

                        $inventory = array_combine($inventoryId, $inventoryQuantity);
                    }

                    $data['inventories'] =  $inventory;

                    // $inventory_data = core()->getCurrentChannel()->inventory_sources;

                    // foreach($inventory_data as $key => $datas)
                    // {
                    //     $inventoryId = $datas->id;
                    // }

                    // $inventoryData[] = (string)$csvData[$i]['inventories'];

                    // foreach ($inventoryData as $key => $d)
                    // {
                    //     $inventory[$inventoryId] = $d;
                    // }

                    // $data['inventories'] =  $inventory;

                    $categoryData = explode(',', $csvData[$i]['categories_slug']);

                    foreach ($categoryData as $key => $value)
                    {
                        $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                    }

                    $data['categories'] = $categoryID;

                    $data['channel'] = core()->getCurrentChannel()->code;
                    $data['locale'] = core()->getCurrentLocale()->code;

                    //Product Images
                    $individualProductimages = explode(',', $csvData[$i]['images']);

                    if (isset($imageZipName)) {
                        $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                        foreach ($images as $imageArraykey => $imagePath)
                        {
                            $imageName = explode('/', $imagePath);

                            if (in_array(last($imageName), preg_replace('/[\'"]/', '',$individualProductimages))) {
                                $data['images'][$imageArraykey] = $imagePath;
                            }
                        }
                    }

                    //to check validation
                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $virtualProductData);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($virtualProductData->id);

                        foreach($errors as $key => $error)
                        {
                            if ($error[0] == "The url key has already been taken.") {
                                $errorToBeReturn[] = "The url key " . $data['url_key'] . " has already been taken";
                            } else {
                                $errorToBeReturn[] = str_replace(".", "", $error[0]) . " for sku " . $data['sku'];
                            }
                        }

                        $requestData['countOfStartedProfiles'] =  $i + 1;

                        $productsUploaded = $i - $requestData['errorCount'];

                        if ($requestData['numberOfCSVRecord'] != 0) {
                            $remainDataInCSV = (int)$requestData['totalNumberOfCSVRecord'] - (int)$requestData['countOfStartedProfiles'];
                        } else {
                            $remainDataInCSV = 0;
                        }

                        $dataToBeReturn = array(
                            'remainDataInCSV' => $remainDataInCSV,
                            'productsUploaded' => $productsUploaded,
                            'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                            'error' => $errorToBeReturn,
                        );

                        return $dataToBeReturn;
                    }

                    $this->productRepository->update($data, $virtualProductData->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $virtualProductData, $imageZipName);
                    }
                }
            }

            //error handling
            if ($requestData['numberOfCSVRecord'] > 10) {
                $remainDataInCSV = (int)$requestData['numberOfCSVRecord'] - (int)$processCSVRecords;
            } else {
                $remainDataInCSV = 0;

                // $this->adminDataFlowProfileRepository->findOneByField('id', $requestData['data_flow_profile_id'])->delete();

                if($requestData['errorCount'] > 0) {
                    $uptoProcessCSVRecords = $requestData['totalNumberOfCSVRecord'] - $requestData['errorCount'];
                } else {
                    $uptoProcessCSVRecords = $processRecords;
                }
            }

            $requestData['countOfStartedProfiles'] = $i;

            $dataToBeReturn = [
                'remainDataInCSV' => $remainDataInCSV,
                'productsUploaded' => $uptoProcessCSVRecords,
                'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
            ];

            return $dataToBeReturn;
        } catch(\Exception $e) {
            Log::error('virtual create product log: '. $e->getMessage());
            $categoryError = explode('[' ,$e->getMessage());
            $categorySlugError = explode(']' ,$e->getMessage());
            $requestData['countOfStartedProfiles'] =  $i + 1;
            $productsUploaded = $i - $requestData['errorCount'];

            if ($requestData['numberOfCSVRecord'] != 0) {
                $remainDataInCSV = (int)$requestData['totalNumberOfCSVRecord'] - (int)$requestData['countOfStartedProfiles'];
            } else {
                $remainDataInCSV = 0;
            }

            if ($categoryError[0] == "No query results for model ") {
                $dataToBeReturn = array(
                    'remainDataInCSV' => $remainDataInCSV,
                    'productsUploaded' => $productsUploaded,
                    'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                    'error' => "Invalid Category Slug: " . $categorySlugError[1],
                );
                $categoryError[0] = null;
            } else if (isset($e->errorInfo)) {
                $dataToBeReturn = array(
                    'remainDataInCSV' => $remainDataInCSV,
                    'productsUploaded' => $productsUploaded,
                    'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                    'error' => $e->errorInfo[2],
                );
            } else {
                $dataToBeReturn = array(
                    'remainDataInCSV' => $remainDataInCSV,
                    'productsUploaded' => $productsUploaded,
                    'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                    'error' => $e->getMessage(),
                );
            }

            return $dataToBeReturn;
        }
    }

    // public function createProductValidation($record, $loopCount)
    // {
    //     try {
    //         $validateProduct = Validator::make($record, [
    //             'type' => 'required',
    //             'sku' => 'required',
    //             'attribute_family_name' => 'required'
    //         ]);

    //         if ($validateProduct->fails()) {
    //             $errors = $validateProduct->errors()->getMessages();

    //             foreach($errors as $key => $error)
    //             {
    //                 $recordCount = (int)$loopCount + (int)1;

    //                 $errorToBeReturn[] = str_replace(".", "", $error[0]) . " for record " . $recordCount;
    //             }

    //             request()->countOfStartedProfiles =  $loopCount + 1;

    //             $productsUploaded = $loopCount - request()->errorCount;

    //             if (request()->numberOfCSVRecord != 0) {
    //                 $remainDataInCSV = (int)request()->totalNumberOfCSVRecord - (int)request()->countOfStartedProfiles;
    //             } else {
    //                 $remainDataInCSV = 0;
    //             }

    //             $dataToBeReturn = array(
    //                 'remainDataInCSV' => $remainDataInCSV,
    //                 'productsUploaded' => $productsUploaded,
    //                 'countOfStartedProfiles' => request()->countOfStartedProfiles,
    //                 'error' => $errorToBeReturn,
    //             );

    //             return $dataToBeReturn;
    //         }

    //         return null;
    //     } catch(\EXception $e) {
    //     }
    // }
}