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
class DownloadableProductRepository extends Repository
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

        $this->productRepository = $productRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->attributeFamily = $attributeFamily;

        $this->helperRepository = $helperRepository;

        $this->productController = $productController;

        $this->attributeOptionRepository = $attributeOptionRepository;
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

        try  {
            $dataFlowProfileRecord = $this->importNewProducts->findOneByField
            ('data_flow_profile_id', $requestData['data_flow_profile_id']);

            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            $downloadableLinks = $this->extractDownloadableFiles($dataFlowProfileRecord);

            if ($requestData['totalNumberOfCSVRecord'] < 1000) {
                $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/10);
            } else {
                $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/100);
            }

            $uptoProcessCSVRecords = (int)$requestData['countOfStartedProfiles'] + 10;
            $processRecords = (int)$requestData['countOfStartedProfiles'] + (int)$requestData['numberOfCSVRecord'];

            if ($requestData['numberOfCSVRecord'] > $processCSVRecords) {
                for ($i = $requestData['countOfStartedProfiles']; $i < $uptoProcessCSVRecords; $i++)
                {
                    $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

                    if (isset($createValidation)) {
                        return $createValidation;
                    }

                    $d_samples = array();
                    $sampleNameKey = array();
                    $linkNameKey = array();
                    $d_links = array();

                    if (isset($csvData[$i]['samples_title'])) {
                        $csvData[$i]['sample_sort_order'] = "";
                        $sampleTitles = explode(',', $csvData[$i]['samples_title']) ;
                        $sampleType = explode(',', $csvData[$i]['sample_type']) ;
                        $sampleFiles = explode(',', $csvData[$i]['sample_files']) ;
                        $sampleSortOrder = !empty($csvData[$i]['sample_sort_order']) ? explode(',', $csvData[$i]['sample_sort_order']) : 0;
                    }

                    //for downloadable link explode
                    if (isset($csvData[$i]['link_titles'])) {
                        $linkTitles = explode(',', $csvData[$i]['link_titles']);
                        $linkTypes = explode(',', $csvData[$i]['link_types']);

                        $linkFileNames = explode(',', $csvData[$i]['link_file_names']);
                        $linkPrices = !empty($csvData[$i]['link_prices']) ? explode(',', $csvData[$i]['link_prices']) : "";

                        $linkSampleTypes = !empty($csvData[$i]['link_sample_types']) ? explode(',', $csvData[$i]['link_sample_types']) : "file";

                        $linkSampleFileNames = !empty($csvData[$i]['link_sample_file_names']) ? explode(',', $csvData[$i]['link_sample_file_names']) : "";

                        $linkDownloads = !empty($csvData[$i]['link_downloads']) ? explode(',', $csvData[$i]['link_downloads']) : 0;

                        $linkSortOrders = !empty($csvData[$i]['link_sort_orders']) ? explode(',', $csvData[$i]['link_sort_orders']) : 0;
                    }

                    $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => $csvData[$i]['url_key']])->first();

                    $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                    $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                    if (! isset($productFlatData) && empty($productFlatData)) {
                        $data['type'] = $csvData[$i]['type'];
                        $data['attribute_family_id'] = $attributeFamilyData->id;
                        $data['sku'] = $csvData[$i]['sku'];

                        $downloadableProduct = $this->productRepository->create($data);
                    } else {
                        $downloadableProduct = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($downloadableProduct->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
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

                    //prepare downloadable sample data
                    for ($j = 0; $j < count($sampleTitles); $j++)
                    {
                        if (trim(strtolower($sampleType[$j])) == "file") {
                            if (isset($downloadableLinks['uploadSampleFilesZipName'])) {
                                if (trim(strtolower($sampleType[$j-1])) == "url") {
                                    $sampleFileName = $sampleFiles[$j-1];
                                } else {
                                    $sampleFileName = $sampleFiles[$j];
                                }

                                $files = $this->fileOrUrlUpload($dataFlowProfileRecord, $sampleType[$j], $sampleFileName, $downloadableProduct->id, $downloadableLinks, $sampleFile = true);

                                if (isset($files)) {
                                    $sample['sample_'.$j] = [
                                        core()->getCurrentLocale()->code => [
                                            "title" => $sampleTitles[$j],
                                        ],
                                        "type" => trim($sampleType[$j]),
                                        "file" => trim($files),
                                        "file_name" => $sampleFileName,
                                        "sort_order" => $sampleSortOrder[$j] ?? 0,
                                    ];

                                    array_push($sampleNameKey, 'sample_'.$j);
                                    array_push($d_samples, $sample['sample_'.$j]);
                                }
                            }
                        } else if (trim(strtolower($sampleType[$j])) == "url") {
                            $files = $this->fileOrUrlUpload($dataFlowProfileRecord, $sampleType[$j], $urlFiles[$j], $downloadableProduct->id, $downloadableLinks, $sampleFile = true);

                            if (isset($files)) {
                                $sample['sample_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $sampleTitles[$j],
                                    ],
                                    "type" => trim($sampleType[$j]),
                                    "url" => trim($urlFiles[$j]),
                                    "sort_order" => $sampleSortOrder[$j] ?? 0,
                                ];

                                array_push($sampleNameKey, 'sample_'.$j);
                                array_push($d_samples, $sample['sample_'.$j]);
                            }
                        }
                    }

                    $combinedArray = array_combine($sampleNameKey, $d_samples);

                    $data['downloadable_samples'] = $combinedArray;

                    //for downloadable links
                    for ($j = 0; $j < count($linkTitles); $j++)
                    {
                        if (trim(strtolower($linkTypes[$j])) == "file") {
                            if (trim(strtolower($linkSampleTypes[$j])) == "file") {
                                if (isset($downloadableLinks['uploadLinkSampleFilesZipName'])) {
                                    if (trim(strtolower($linkSampleTypes[$j-1])) == "url") {
                                        $linkSampleFile = $linkSampleFileNames[$j-1];
                                    } else {
                                        $linkSampleFile = $linkSampleFileNames[$j];
                                    }

                                    $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleFile, $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                                }
                            } else if (trim(strtolower($linkSampleTypes[$j])) == "url") {
                                $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleUrlNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                            }

                            if (isset($downloadableLinks['uploadLinkFilesZipName'])) {
                                if (trim(strtolower($linkSampleTypes[$j-1])) == "url") {
                                    $linkFileName = $linkFileNames[$j-1];
                                } else {
                                    $linkFileName = $linkFileNames[$j];
                                }

                                $fileLink = $this->linkFileOrUrlUpload($dataFlowProfileRecord, $linkTypes[$j], $linkFileName, $downloadableProduct->id, $downloadableLinks);
                            }

                                if (isset($fileLink)) {
                                $link['link_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $linkTitles[$j],
                                    ],
                                    "price" => $linkPrices[$j],
                                    "type" => trim($linkTypes[$j]),
                                    "file" => trim($fileLink),
                                    "file_name" => $linkFileName,
                                    "sample_type" => trim($linkSampleTypes[$j]),
                                    "downloads" => $linkDownloads[$j] ?? 0,
                                    "sort_order" => $linkSortOrders[$j] ?? 0,
                                ];

                                if (trim($linkSampleTypes[$j]) == "url") {
                                    $link['link_'.$j]['sample_url'] = trim($linkSampleUrlNames[$j]);
                                } else if (trim($linkSampleTypes[$j]) == "file") {
                                    $link['link_'.$j]['sample_file'] = trim($sampleFileLink);

                                    $link['link_'.$j]['sample_file_name'] = trim($linkSampleFile);
                                }

                                array_push($linkNameKey, 'link_'.$j);
                                array_push($d_links, $link['link_'.$j]);
                            }
                        } else if (trim(strtolower($linkTypes[$j])) == "url") {
                            if (trim(strtolower($linkSampleTypes[$j])) == "file") {
                                if (isset($downloadableLinks['uploadLinkSampleFilesZipName'])) {
                                    $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleFileNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                                }
                            } else if (trim(strtolower($linkSampleTypes[$j])) == "url") {
                                $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleUrlNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                            }

                            $fileLink = $this->linkFileOrUrlUpload($dataFlowProfileRecord, $linkTypes[$j], $linkUrlNames[$j], $downloadableProduct->id, $downloadableLinks);

                            if (isset($fileLink)) {
                                $link['link_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $linkTitles[$j],
                                    ],
                                    "price" => $linkPrices[$j],
                                    "type" => trim($linkTypes[$j]),
                                    "url" => trim($linkUrlNames[$j]) ?? "",
                                    "sample_type" => trim($linkSampleTypes[$j]),
                                    "downloads" => $linkDownloads[$j] ?? 0,
                                    "sort_order" => $linkSortOrders[$j] ?? 0,
                                ];

                                if (trim($linkSampleTypes[$j]) == "url") {
                                    $link['link_'.$j]['sample_url'] = trim($linkSampleUrlNames[$j]);
                                } else if (trim($linkSampleTypes[$j]) == "file") {
                                    $link['link_'.$j]['sample_file'] = trim($sampleFileLink);
                                    $link['link_'.$j]['sample_file_name'] = trim($linkSampleFileNames[$j]);
                                }

                                array_push($linkNameKey, 'link_'.$j);
                                array_push($d_links, $link['link_'.$j]);
                            }
                        }
                    }

                    $combinedLinksArray = array_combine($linkNameKey, $d_links);

                    $data['downloadable_links'] = $combinedLinksArray;

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

                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $downloadableProduct);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($downloadableProduct->id);

                        foreach($errors as $key => $error)
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

                    $this->productRepository->update($data, $downloadableProduct->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $downloadableProduct, $imageZipName);
                    }
                }
            } else if ($requestData['numberOfCSVRecord'] <= 10) {
                for ($i = $requestData['countOfStartedProfiles']; $i < $processRecords; $i++)
                {
                    $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

                    if (isset($createValidation)) {
                        return $createValidation;
                    }

                    $d_samples = array();
                    $sampleNameKey = array();
                    $linkNameKey = array();
                    $d_links = array();

                    if (isset($csvData[$i]['samples_title'])) {
                        $csvData[$i]['sample_sort_order'] = "";
                        $sampleTitles = explode(',', $csvData[$i]['samples_title']) ;
                        $sampleType = explode(',', $csvData[$i]['sample_type']) ;
                        $sampleFiles = explode(',', $csvData[$i]['sample_files']) ;
                        $urlFiles = explode(',', $csvData[$i]['sample_url']) ;
                        $sampleSortOrder = !empty($csvData[$i]['sample_sort_order']) ? explode(',', $csvData[$i]['sample_sort_order']) : 0;
                    }

                    //for downloadable link explode
                    if (isset($csvData[$i]['link_titles'])) {
                        $linkTitles = explode(',', $csvData[$i]['link_titles']);
                        $linkTypes = explode(',', $csvData[$i]['link_types']);

                        $linkFileNames = explode(',', $csvData[$i]['link_file_names']);

                        $linkPrices = !empty($csvData[$i]['link_prices']) ? explode(',', $csvData[$i]['link_prices']) : "";

                        $linkSampleTypes = !empty($csvData[$i]['link_sample_types']) ? explode(',', $csvData[$i]['link_sample_types']) : "file";

                        $linkSampleFileNames = !empty($csvData[$i]['link_sample_file_names']) ? explode(',', $csvData[$i]['link_sample_file_names']) : "";

                        $linkDownloads = !empty($csvData[$i]['link_downloads']) ? explode(',', $csvData[$i]['link_downloads']) : 0;

                        $linkSortOrders = !empty($csvData[$i]['link_sort_orders']) ? explode(',', $csvData[$i]['link_sort_orders']) : 0;

                        $linkSampleUrlNames = explode(',', $csvData[$i]['link_sample_url']);
                        $linkUrlNames = explode(',', $csvData[$i]['link_url']);
                    }

                    $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => $csvData[$i]['url_key']])->first();

                    $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                    $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                    if (! isset($productFlatData) && empty($productFlatData)) {
                        $data['type'] = $csvData[$i]['type'];
                        $data['attribute_family_id'] = $attributeFamilyData->id;
                        $data['sku'] = $csvData[$i]['sku'];

                        $downloadableProduct = $this->productRepository->create($data);
                    } else {
                        $downloadableProduct = $productData;
                    }

                    unset($data);
                    $data = array();
                    $attributeCode = array();
                    $attributeValue = array();

                    //default attributes
                    foreach ($downloadableProduct->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
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

                    //prepare downloadable sample data
                    for ($j = 0; $j < count($sampleTitles); $j++)
                    {
                        if (trim(strtolower($sampleType[$j])) == "file") {
                            if (isset($downloadableLinks['uploadSampleFilesZipName'])) {
                                if (trim(strtolower($sampleType[$j-1])) == "url") {
                                    $sampleFileName = $sampleFiles[$j-1];
                                } else {
                                    $sampleFileName = $sampleFiles[$j];
                                }

                                $files = $this->fileOrUrlUpload($dataFlowProfileRecord, $sampleType[$j], $sampleFileName, $downloadableProduct->id, $downloadableLinks, $sampleFile = true);

                                if (isset($files)) {
                                    $sample['sample_'.$j] = [
                                        core()->getCurrentLocale()->code => [
                                            "title" => $sampleTitles[$j],
                                        ],
                                        "type" => trim($sampleType[$j]),
                                        "file" => trim($files),
                                        "file_name" => $sampleFileName,
                                        "sort_order" => $sampleSortOrder[$j] ?? 0,
                                    ];

                                    array_push($sampleNameKey, 'sample_'.$j);
                                    array_push($d_samples, $sample['sample_'.$j]);
                                }
                            }
                        } else if (trim(strtolower($sampleType[$j])) == "url") {
                            $files = $this->fileOrUrlUpload($dataFlowProfileRecord, $sampleType[$j], $urlFiles[$j], $downloadableProduct->id, $downloadableLinks, $sampleFile = true);

                            if (isset($files)) {
                                $sample['sample_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $sampleTitles[$j],
                                    ],
                                    "type" => trim($sampleType[$j]),
                                    "url" => trim($urlFiles[$j]),
                                    "sort_order" => $sampleSortOrder[$j] ?? 0,
                                ];

                                array_push($sampleNameKey, 'sample_'.$j);
                                array_push($d_samples, $sample['sample_'.$j]);
                            }
                        }
                    }

                    $combinedArray = array_combine($sampleNameKey, $d_samples);

                    $data['downloadable_samples'] = $combinedArray;

                    //for downloadable links
                    for ($j = 0; $j < count($linkTitles); $j++)
                    {
                        if (trim(strtolower($linkTypes[$j])) == "file") {
                            if (trim(strtolower($linkSampleTypes[$j])) == "file") {
                                if (isset($downloadableLinks['uploadLinkSampleFilesZipName'])) {
                                    if (trim(strtolower($linkSampleTypes[$j-1])) == "url") {
                                        $linkSampleFile = $linkSampleFileNames[$j-1];
                                    } else {
                                        $linkSampleFile = $linkSampleFileNames[$j];
                                    }

                                    $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleFile, $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                                }
                            } else if (trim(strtolower($linkSampleTypes[$j])) == "url") {
                                $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleUrlNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                            }

                            if (isset($downloadableLinks['uploadLinkFilesZipName'])) {
                                if (trim(strtolower($linkSampleTypes[$j-1])) == "url") {
                                    $linkFileName = $linkFileNames[$j-1];
                                } else {
                                    $linkFileName = $linkFileNames[$j];
                                }

                                $fileLink = $this->linkFileOrUrlUpload($dataFlowProfileRecord, $linkTypes[$j], $linkFileName, $downloadableProduct->id, $downloadableLinks);
                            }

                                if (isset($fileLink)) {
                                $link['link_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $linkTitles[$j],
                                    ],
                                    "price" => $linkPrices[$j],
                                    "type" => trim($linkTypes[$j]),
                                    "file" => trim($fileLink),
                                    "file_name" => $linkFileName,
                                    "sample_type" => trim($linkSampleTypes[$j]),
                                    "downloads" => $linkDownloads[$j] ?? 0,
                                    "sort_order" => $linkSortOrders[$j] ?? 0,
                                ];

                                if (trim($linkSampleTypes[$j]) == "url") {
                                    $link['link_'.$j]['sample_url'] = trim($linkSampleUrlNames[$j]);
                                } else if (trim($linkSampleTypes[$j]) == "file") {
                                    $link['link_'.$j]['sample_file'] = trim($sampleFileLink);

                                    $link['link_'.$j]['sample_file_name'] = trim($linkSampleFile);
                                }

                                array_push($linkNameKey, 'link_'.$j);
                                array_push($d_links, $link['link_'.$j]);
                            }
                        } else if (trim(strtolower($linkTypes[$j])) == "url") {
                            if (trim(strtolower($linkSampleTypes[$j])) == "file") {
                                if (isset($downloadableLinks['uploadLinkSampleFilesZipName'])) {
                                    $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleFileNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                                }
                            } else if (trim(strtolower($linkSampleTypes[$j])) == "url") {
                                $sampleFileLink = $this->fileOrUrlUpload($dataFlowProfileRecord, $linkSampleTypes[$j], $linkSampleUrlNames[$j], $downloadableProduct->id, $downloadableLinks, $sampleLinkfile = false);
                            }

                            $fileLink = $this->linkFileOrUrlUpload($dataFlowProfileRecord, $linkTypes[$j], $linkUrlNames[$j], $downloadableProduct->id, $downloadableLinks);

                            if (isset($fileLink)) {
                                $link['link_'.$j] = [
                                    core()->getCurrentLocale()->code => [
                                        "title" => $linkTitles[$j],
                                    ],
                                    "price" => $linkPrices[$j],
                                    "type" => trim($linkTypes[$j]),
                                    "url" => trim($linkUrlNames[$j]) ?? "",
                                    "sample_type" => trim($linkSampleTypes[$j]),
                                    "downloads" => $linkDownloads[$j] ?? 0,
                                    "sort_order" => $linkSortOrders[$j] ?? 0,
                                ];

                                if (trim($linkSampleTypes[$j]) == "url") {
                                    $link['link_'.$j]['sample_url'] = trim($linkSampleUrlNames[$j]);
                                } else if (trim($linkSampleTypes[$j]) == "file") {
                                    $link['link_'.$j]['sample_file'] = trim($sampleFileLink);
                                    $link['link_'.$j]['sample_file_name'] = trim($linkSampleFileNames[$j]);
                                }

                                array_push($linkNameKey, 'link_'.$j);
                                array_push($d_links, $link['link_'.$j]);
                            }
                        }
                    }

                    $combinedLinksArray = array_combine($linkNameKey, $d_links);

                    $data['downloadable_links'] = $combinedLinksArray;

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

                    $validationRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $downloadableProduct);

                    $csvValidator = Validator::make($data, $validationRules);

                    if ($csvValidator->fails()) {
                        $errors = $csvValidator->errors()->getMessages();

                        $this->helperRepository->deleteProductIfNotValidated($downloadableProduct->id);

                        foreach($errors as $key => $error)
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

                    $this->productRepository->update($data, $downloadableProduct->id);

                    if (isset($imageZipName)) {
                        $this->bulkUploadImages->bulkuploadImages($data, $downloadableProduct, $imageZipName);
                    }
                }
            }

            if ($requestData['numberOfCSVRecord'] > 10) {
                $remainDataInCSV = (int)$requestData['numberOfCSVRecord'] - (int)$processCSVRecords;
            } else {
                $remainDataInCSV = 0;

                if ($requestData['errorCount'] > 0) {
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
            Log::error('downloadable create product log: '. $e->getMessage());

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

    //sample file uploads (file set to storage folder)
    public function fileOrUrlUpload($dataFlowProfileRecord, $type, $file, $id, $downloadableLinks, $flag)
    {
        try {
            if (trim($type) == "file") {
                if ($flag) {
                    $files = "imported-products/extracted-images/admin/sample-files/".$dataFlowProfileRecord->id.'/'. $downloadableLinks['uploadSampleFilesZipName']['dirname'].'/'.trim(basename($file));

                    $destination = "product/".$id.'/'.trim(basename($file));

                    Storage::copy($files, $destination);

                    return $destination;
                } else {
                    $files = "imported-products/extracted-images/admin/link-sample-files/".$dataFlowProfileRecord->id.'/'. $downloadableLinks['uploadLinkSampleFilesZipName']['dirname'].'/'.trim(basename($file));

                    $destination = "product/".$id.'/'.trim(basename($file));

                    Storage::copy($files, $destination);

                    return $destination;
                }
            } else {
                if ($flag) {
                    $imagePath = storage_path('app/public/imported-products/extracted-images/admin/sample-files/'.$dataFlowProfileRecord->id);

                    if (!file_exists($imagePath)) {
                        mkdir($imagePath, 0777, true);
                    }

                    $imageFile = $imagePath.'/'.basename($file);

                    file_put_contents($imageFile, file_get_contents(trim($file)));

                    $files = "imported-products/extracted-images/admin/sample-files/".$dataFlowProfileRecord->id.'/'.basename($file);

                    $destination = "product/".$id.'/'.basename($file);
                    Storage::copy($files, $destination);

                    return $destination;
                } else {
                    $imagePath = storage_path('app/public/imported-products/extracted-images/admin/link-sample-files/'.$dataFlowProfileRecord->id);

                    if (!file_exists($imagePath)) {
                        mkdir($imagePath, 0777, true);
                    }

                    $imageFile = $imagePath.'/'.basename($file);

                    file_put_contents($imageFile, file_get_contents(trim($file)));

                    $files = "imported-products/extracted-images/admin/link-sample-files/".$dataFlowProfileRecord->id.'/'.basename($file);

                    $destination = "product/".$id.'/'.basename($file);
                    Storage::copy($files, $destination);

                    return $destination;



                    // $urlPath = public_path('storage/imported-products/extracted-images/admin/link-sample-files/'.$dataFlowProfileRecord->id);

                    // if (!file_exists($urlPath)) {
                    //     mkdir($urlPath, 0777, true);
                    // }

                    // $imageFile = $urlPath.'/'.basename(trim($file));

                    // file_put_contents($imageFile, file_get_contents(trim($file)));

                    // $destination = "product/".$id.'/'.basename(trim($file));

                    // Storage::copy(trim($imageFile), $destination);

                    // return $destination;
                }
            }
        } catch(\Exception $e) {
            Log::error('downloadable fileOrUrlUpload log: '. $e->getMessage());
        }
    }

    //link file uploads (file set to storage folder)
    public function linkFileOrUrlUpload($dataFlowProfileRecord, $type, $file, $id, $downloadableLinks)
    {
        try {
            if (trim($type) == "file") {
                $files = "imported-products/extracted-images/admin/link-files/".$dataFlowProfileRecord->id.'/'. $downloadableLinks['uploadLinkFilesZipName']['dirname'].'/'.trim(basename($file));

                // $destination = "product/".$id.'/'.trim(basename($file));
                $destination = "product_downloadable_links/".$id.'/'.basename($file);


                Storage::copy($files, $destination);

                return $destination;
            } else {
                $imagePath = storage_path('app/public/imported-products/extracted-images/admin/link-files/'.$dataFlowProfileRecord->id);

                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0777, true);
                }

                $imageFile = $imagePath.'/'.basename($file);

                file_put_contents($imageFile, file_get_contents(trim($file)));

                $files = "imported-products/extracted-images/admin/link-files/".$dataFlowProfileRecord->id.'/'.basename($file);

                $destination = "product_downloadable_links/".$id.'/'.basename($file);

                Storage::copy($files, $destination);

                return $destination;
            }
        } catch(\Exception $e) {
            Log::error('downloadable linkFileOrUrlUpload log: '. $e->getMessage());
        }
    }

    //unzip zip files and store in storage folder
    public function extractDownloadableFiles($record)
    {
        if (isset($record->upload_link_files) && ($record->upload_link_files != "") ) {
            $uploadLinkFilesZip = new \ZipArchive();

            $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/link-files/'.$record->id.'/');

            if ($uploadLinkFilesZip->open(storage_path('app/public/'.$record->upload_link_files))) {
                for ($i = 0; $i < $uploadLinkFilesZip->numFiles; $i++) {
                    $filename = $uploadLinkFilesZip->getNameIndex($i);
                    $uploadLinkFilesZipName = pathinfo($filename);
                }

                $uploadLinkFilesZip->extractTo($extractedPath);
                $uploadLinkFilesZip->close();
            }
        } else {
            $uploadLinkFilesZipName = null;
        }

        if (isset($record->upload_sample_files) && ($record->upload_sample_files != "") ) {
            $uploadSampleFilesZip = new \ZipArchive();

            $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/sample-files/'.$record->id.'/');

            if ($uploadSampleFilesZip->open(storage_path('app/public/'.$record->upload_sample_files))) {
                for ($i = 0; $i < $uploadSampleFilesZip->numFiles; $i++) {
                    $filename = $uploadSampleFilesZip->getNameIndex($i);
                    $uploadSampleFilesZipName = pathinfo($filename);
                }

                $uploadSampleFilesZip->extractTo($extractedPath);
                $uploadSampleFilesZip->close();
            }
        } else {
            $uploadSampleFilesZipName = null;
        }

        if (isset($record->upload_link_sample_files) && ($record->upload_link_sample_files != "") ) {
            $uploadLinkSampleFilesZip = new \ZipArchive();

            $extractedPath = storage_path('app/public/imported-products/extracted-images/admin/link-sample-files/'.$record->id.'/');

            if ($uploadLinkSampleFilesZip->open(storage_path('app/public/'.$record->upload_link_sample_files))) {
                for ($i = 0; $i < $uploadLinkSampleFilesZip->numFiles; $i++) {
                    $filename = $uploadLinkSampleFilesZip->getNameIndex($i);
                    $uploadLinkSampleFilesZipName = pathinfo($filename);
                }

                $uploadLinkSampleFilesZip->extractTo($extractedPath);
                $uploadLinkSampleFilesZip->close();
            }
        } else {
            $uploadLinkSampleFilesZipName = null;
        }

        return [
            'uploadLinkSampleFilesZipName' => $uploadLinkSampleFilesZipName, 'uploadSampleFilesZipName' => $uploadSampleFilesZipName,
            'uploadLinkFilesZipName' => $uploadLinkFilesZipName
        ];
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