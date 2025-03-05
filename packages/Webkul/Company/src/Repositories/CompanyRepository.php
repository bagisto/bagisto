<?php

namespace Webkul\Company\Repositories;

use Webkul\Company\Enums\TypeAttribute;
use Webkul\Company\Models\Company;
use Illuminate\Support\Arr;
use Webkul\Company\Repositories\Repository as BaseRepository;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends BaseRepository
{

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Company::class;
    }


    public function getListCompany(array $filter = [])
    {

        $companiesQuery = $this->model->newQuery();

        $attributeName = Arr::get($filter, 'attribute_name');
        $attributeValue = Arr::get($filter, 'attribute_value');

        if ($attributeName && $attributeValue) {
            $companiesQuery = match ($attributeName) {
                TypeAttribute::COMPANY_TAX_CODE->value => $companiesQuery->where('ma_so_dn', $attributeValue),
                TypeAttribute::COMPANY_NAME->value => $companiesQuery->where('ten_vn', 'like', '%' . $attributeValue . '%'),
                default => $companiesQuery
            };
        }


        if ($attributeValue && !$attributeName) {
            $companiesQuery->where('ma_so_thue', $attributeValue);
        }

        return $companiesQuery->paginate(10);
    }

    public function detailCompany(string $id)
    {
        $company =  $this->model->newQuery()->where('id', $id)->first();
        return json_decode(json_encode($company), true);
    }

}
