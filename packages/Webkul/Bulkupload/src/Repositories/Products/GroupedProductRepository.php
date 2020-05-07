<?php

namespace Webkul\Bulkupload\Repositories\Products;

use Illuminate\Container\Container as App;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\Products\HelperRepository;
use Illuminate\Support\Facades\Validator;
use Webkul\Bulkupload\Repositories\ProductImageRepository as BulkUploadImages;
use Storage;
use Webkul\Product\Http\Controllers\ProductController;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * BulkProduct Repository
 *
 * @author    Prateek Sivastava
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class GroupedProductRepository extends Repository
{
    protected $importNewProducts;

    protected $categoryRepository;

    protected $productFlatRepository;

    protected $productRepository;

    protected $attributeFamily;

    protected $helperRepository;

    protected $bulkUploadImages;

    protected $productController;

    protected $productDownloadableLinkRepository;

    protected $attributeOptionRepository;

    public function __construct(
        ImportNewProducts $importNewProducts,
        CategoryRepository $categoryRepository,
        ProductFlatRepository $productFlatRepository,
        ProductRepository $productRepository,
        AttributeFamily $attributeFamily,
        HelperRepository $helperRepository,
        BulkUploadImages $bulkUploadImages,
        ProductController $productController,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        AttributeOptionRepository $attributeOptionRepository
       )
    {
        $this->importNewProducts = $importNewProducts;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        $this->categoryRepository = $categoryRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->productRepository = $productRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->attributeFamily = $attributeFamily;

        $this->helperRepository = $helperRepository;

        $this->productController = $productController;
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
        $uploadLinkFilesZipName = null;
        $uploadSampleFilesZipName = null;
        $uploadLinkSampleFilesZipName = null;

        try {
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

                        $groupedProduct = $this->productRepository->create($data);
                    } else {
                        $groupedProduct = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($groupedProduct->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
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

                    $categoryData = explode(',', $csvData[$i]['categories_slug']);

                    foreach ($categoryData as $key => $value)
                    {
                        $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                    }

                    $data['categories'] = $categoryID;

                    $data['channel'] = core()->getCurrentChannel()->code;
                    $data['locale'] = core()->getCurrentLocale()->code;

                    //grouped product links
                    if (isset($csvData[$i]['grouped_product_sku'])) {
                        $groupedProductSku = explode(",", strtolower($csvData[$i]['grouped_product_sku']));
                        $groupedQuantity = explode(",", $csvData[$i]['grouped_quantity']);
                        $groupedSortOrder = explode(",", $csvData[$i]['grouped_sort_order']);

                        for ($j = 0; $j < count($groupedProductSku); $j++)
                        {
                            $link = $j+1;

                            $variants = true;

                            $associatedProducts = $this->productRepository->findOneByField(['sku' => strtolower(trim($groupedProductSku[$j]))]);

                            if (isset($associatedProducts) && ! empty($associatedProducts)) {
                                if (isset($associatedProducts->parent_id) || $associatedProducts->type == "simple") {
                                    $groupedLink['link_'.$link] = [
                                        "associated_product_id" => $associatedProducts->id,
                                        "qty" => $groupedQuantity[$j],
                                        "sort_order" => $groupedSortOrder[$j],
                                    ];
                                }
                            }
                        }

                        $data['links'] = $groupedLink;
                    }

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

                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $groupedProduct);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($groupedProduct->id);

                        foreach ($errors as $key => $error)
                        {
                            if ($error[0] == "The url key has already been taken.") {
                                $errorToBeReturn[] = "The url key " . $data['url_key'] . " has already been taken";
                            } else {
                                $errorToBeReturn[] = str_replace(".", "", $error[0]). " for sku " . $data['sku'];
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

                    $this->productRepository->update($data, $groupedProduct->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $groupedProduct, $imageZipName);
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

                        $groupedProduct = $this->productRepository->create($data);
                    } else {
                        $groupedProduct = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($groupedProduct->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
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

                    $categoryData = explode(',', $csvData[$i]['categories_slug']);

                    foreach ($categoryData as $key => $value)
                    {
                        $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                    }

                    $data['categories'] = $categoryID;
                    $data['channel'] = core()->getCurrentChannel()->code;
                    $data['locale'] = core()->getCurrentLocale()->code;

                    //grouped product links
                    if (isset($csvData[$i]['grouped_product_sku'])) {
                        $groupedProductSku = explode(",", strtolower($csvData[$i]['grouped_product_sku']));
                        $groupedQuantity = explode(",", $csvData[$i]['grouped_quantity']);
                        $groupedSortOrder = explode(",", $csvData[$i]['grouped_sort_order']);

                        for ($j = 0; $j < count($groupedProductSku); $j++)
                        {
                            $link = $j+1;

                            $variants = true;

                            $associatedProducts = $this->productRepository->findOneByField(['sku' => strtolower(trim($groupedProductSku[$j]))]);

                            if (isset($associatedProducts) && !empty($associatedProducts)) {
                                if (isset($associatedProducts->parent_id)) {
                                    $groupedLink['link_'.$link] = [
                                        "associated_product_id" => $associatedProducts->id,
                                        "qty" => $groupedQuantity[$j],
                                        "sort_order" => $groupedSortOrder[$j],
                                    ];
                                } else if ($associatedProducts->type == "simple") {
                                    $groupedLink['link_'.$link] = [
                                        "associated_product_id" => $associatedProducts->id,
                                        "qty" => $groupedQuantity[$j],
                                        "sort_order" => $groupedSortOrder[$j],
                                    ];
                                }
                            }
                        }

                        if(isset($groupedLink) && !empty($groupedLink)) {
                            $data['links'] = $groupedLink;
                        }
                    }

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

                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $groupedProduct);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($groupedProduct->id);

                        foreach ($errors as $key => $error)
                        {
                            if ($error[0] == "The url key has already been taken.") {
                                $errorToBeReturn[] = "The url key " . $data['url_key'] . " has already been taken";
                            } else {
                                $errorToBeReturn[] = str_replace(".", "", $error[0]). " for sku " . $data['sku'];
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

                    $this->productRepository->update($data, $groupedProduct->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $groupedProduct, $imageZipName);
                    }

                }
            }

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
            Log::error('grouped create product log: '. $e->getMessage());

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
    //                 $errorToBeReturn[] = str_replace(".", "", $error[0]) . " for record " . $loopCount + 1;
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
    //     } catch(\EXception $e) {}
    // }
}