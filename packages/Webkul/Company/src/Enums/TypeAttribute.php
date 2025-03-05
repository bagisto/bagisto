<?php

namespace Webkul\Company\Enums;

enum TypeAttribute: string
{
    case COMPANY_TAX_CODE = 'COMPANY_TAX_CODE';
    case PERSONAL_TAX_CODE = 'PERSONAL_TAX_CODE';
    case IDENTITY_CODE = 'IDENTITY_CODE';
    case MANAGER_NAME = 'MANAGER_NAME';
    case COMPANY_NAME = 'COMPANY_NAME';

    public static  function selectFilterCompany()
    {
        return [
            self::COMPANY_TAX_CODE->value => config('app.locale') == 'vi' ? 'Mã số thuế công ty' : __('company::company.tax_code'),
            self::COMPANY_NAME->value => config('app.locale') == 'vi' ? 'Tên công ty' : __('company::company.company_name'),
        ];
    }
}
