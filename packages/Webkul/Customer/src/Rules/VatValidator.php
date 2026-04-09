<?php

namespace Webkul\Customer\Rules;

class VatValidator
{
    /**
     * Regular expression patterns per country code.
     *
     * @var array
     */
    protected static $pattern_expression = [
        'AE' => '\d{15}',
        'AL' => '[A-Z]\d{8}[A-Z]',
        'AR' => '\d{11}',
        'AT' => 'U[A-Z\d]{8}',
        'AU' => '\d{11}',
        'BE' => '(0\d{9}|\d{10})',
        'BG' => '\d{9,10}',
        'BR' => '\d{14}',
        'CH' => 'E\d{9}',
        'CL' => '\d{7,8}[\dK]',
        'CN' => '\d{15}|\d{18}|\d{20}',
        'CO' => '\d{9,10}',
        'CY' => '\d{8}[A-Z]',
        'CZ' => '\d{8,10}',
        'DE' => '\d{9}',
        'DK' => '(\d{2} ?){3}\d{2}',
        'EE' => '\d{9}',
        'EL' => '\d{9}',
        'ES' => '[A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8}',
        'FI' => '\d{8}',
        'FR' => '([A-Z]{2}|\d{2})\d{9}',
        'GB' => '\d{9}|\d{12}|(GD|HA)\d{3}',
        'HR' => '\d{11}',
        'HU' => '\d{8}',
        'ID' => '\d{15}|\d{16}',
        'IE' => '[A-Z\d]{8}|[A-Z\d]{9}',
        'IL' => '\d{9}',
        'IN' => '\d{2}[A-Z]{5}\d{4}[A-Z]\d[A-Z\d][A-Z]',
        'IS' => '\d{5,6}',
        'IT' => '\d{11}',
        'JP' => '\d{12}|\d{13}',
        'KR' => '\d{10}',
        'LT' => '(\d{9}|\d{12})',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'ME' => '\d{13}',
        'MT' => '\d{8}',
        'MX' => '[A-Z&Ñ]{3,4}\d{6}[A-Z\d]{3}',
        'MY' => '\d{12}',
        'NL' => '\d{9}B\d{2}',
        'NO' => '\d{9}',
        'NZ' => '\d{8,9}',
        'PH' => '\d{12}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'RS' => '\d{9}',
        'RU' => '\d{10}|\d{12}',
        'SA' => '\d{15}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}',
        'TH' => '\d{13}',
        'TR' => '\d{10}',
        'TW' => '\d{8}',
        'UA' => '\d{8}|\d{10}|\d{12}',
        'VN' => '\d{10}|\d{13}',
        'XI' => '\d{9}|\d{12}|(GD|HA)\d{3}',
        'ZA' => '\d{10}',
    ];

    /**
     * Validate a VAT number format.
     *
     * @param  ?string  $formCountry  country code from the input form - used as backup if the VAT number does not contain a country code
     */
    public function validate(string $vatNumber, ?string $formCountry = null): bool
    {
        $vatNumber = $this->vatCleaner($vatNumber);

        [$country, $number] = $this->splitVat($vatNumber);

        if (! isset(self::$pattern_expression[$country])) {
            if (! $formCountry) {
                return true;
            }

            if (! isset(self::$pattern_expression[$formCountry])) {
                return true;
            }

            $country = $formCountry;
            $number = $vatNumber;
        }

        return preg_match('/^'.self::$pattern_expression[$country].'$/', $number) > 0;
    }

    /**
     * Vat number cleaner.
     */
    private function vatCleaner(string $vatNumber): string
    {
        $vatNumberClean = preg_replace('/[^A-Z0-9]/i', '', $vatNumber);

        return strtoupper($vatNumberClean);
    }

    /**
     * Split the VAT number into country code and number.
     */
    private function splitVat(string $vatNumber): array
    {
        return [
            substr($vatNumber, 0, 2),
            substr($vatNumber, 2),
        ];
    }
}
