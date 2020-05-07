<?php

namespace Webkul\Bulkupload\Repositories\Products;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Bulkupload\Repositories\DataFlowProfileRepository as AdminDataFlowProfileRepository;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Illuminate\Support\Facades\Validator;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;



/**
 * BulkProduct Repository
 *
 * @author    Prateek Sivastava
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class HelperRepository extends Repository
{

    protected $adminDataFlowProfileRepository;

    protected $productFlatRepository;
    protected $productRepository;

    public function __construct(
        AdminDataFlowProfileRepository $adminDataFlowProfileRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        ProductFlatRepository $productFlatRepository,
        ProductRepository $productRepository
    )
    {
        $this->adminDataFlowProfileRepository = $adminDataFlowProfileRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->productRepository = $productRepository;
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

    public function validateCSV($dataFlowProfileId, $records, $dataFlowProfileRecord, $product)
    {
        $messages = [];
        $rules = [];

        $profiler = $this->adminDataFlowProfileRepository->findOneByField('id', $dataFlowProfileId);

        if ($dataFlowProfileRecord) {
            foreach($records as $data) {
                $this->rules = array_merge($product->getTypeInstance()->getTypeValidationRules(), [
                    'sku'                => ['required', 'unique:products,sku,' . $product->id, new \Webkul\Core\Contracts\Validations\Slug],
                    // 'images.*'           => 'mimes:jpeg,jpg,bmp,png',
                    'special_price_from' => 'nullable|date',
                    'special_price_to'   => 'nullable|date|after_or_equal:special_price_from',
                    'special_price'      => ['nullable', new \Webkul\Core\Contracts\Validations\Decimal, 'lt:price'],
                ]);

                foreach ($product->getEditableAttributes() as $attribute) {
                    if ($attribute->code == 'sku' || $attribute->type == 'boolean') {
                        continue;
                    }

                    $validations = [];

                    if (! isset($this->rules[$attribute->code])) {
                        array_push($validations, $attribute->is_required ? 'required' : 'nullable');
                    } else {
                        $validations = $this->rules[$attribute->code];
                    }

                    if ($attribute->type == 'text' && $attribute->validation) {
                        array_push($validations,
                            $attribute->validation == 'decimal'
                            ? new \Webkul\Core\Contracts\Validations\Decimal
                            : $attribute->validation
                        );
                    }

                    if ($attribute->type == 'price') {
                        array_push($validations, new \Webkul\Core\Contracts\Validations\Decimal);
                    }

                    if ($attribute->is_unique) {
                        $this->id = $product;

                        array_push($validations, function ($field, $value, $fail) use ($attribute) {
                            $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                            if (! $this->productAttributeValueRepository->isValueUnique($this->id, $attribute->id, $column, request($attribute->code))) {
                                $fail('The :attribute has already been taken.');
                            }
                        });
                    }

                    $this->rules[$attribute->code] = $validations;

                    // str_replace(".", "", $this->rules);
                }

                $validationCheckForUpdateData = $this->productFlatRepository
                    ->findWhere(['sku' => $records['sku'], 'url_key' => $records['url_key']]);

                if (! isset($validationCheckForUpdateData) || empty($validationCheckForUpdateData)) {
                    $urlKeyUniqueness = "unique:product_flat,url_key";
                    array_push($this->rules["url_key"], $urlKeyUniqueness);
                }

                return $this->rules;
            }
        }
    }

    public function updateValidatedCSVRecords($validationFormRequest, $id)
    {
        $product = $this->product->update($validationFormRequest, $id);
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

    public function deleteProductIfNotValidated($id)
    {
        $this->productRepository->findOrFail($id)->delete();
    }

    public function createProductValidation($record, $loopCount)
    {
        try {
            $validateProduct = Validator::make($record, [
                'type' => 'required',
                'sku' => 'required',
                'attribute_family_name' => 'required'
            ]);

            if ($validateProduct->fails()) {
                $errors = $validateProduct->errors()->getMessages();

                foreach($errors as $key => $error)
                {
                    $recordCount = (int)$loopCount + (int)1;

                    $errorToBeReturn[] = str_replace(".", "", $error[0]) . " for record " . $recordCount;
                }

                request()->countOfStartedProfiles =  $loopCount + 1;

                $productsUploaded = $loopCount - request()->errorCount;

                if (request()->numberOfCSVRecord != 0) {
                    $remainDataInCSV = (int)request()->totalNumberOfCSVRecord - (int)request()->countOfStartedProfiles;
                } else {
                    $remainDataInCSV = 0;
                }

                $dataToBeReturn = array(
                    'remainDataInCSV' => $remainDataInCSV,
                    'productsUploaded' => $productsUploaded,
                    'countOfStartedProfiles' => request()->countOfStartedProfiles,
                    'error' => $errorToBeReturn,
                );

                return $dataToBeReturn;
            }

            return null;
        } catch(\EXception $e) {
        }
    }
}
