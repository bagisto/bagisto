<?php

namespace Webkul\Customer\Rules;

/**
 * Class VatValidator
 *
 * This class is borrowed from:
 *
 * @see     https://raw.githubusercontent.com/danielebarbaro/laravel-vat-eu-validator
 *
 * @package Danielebarbaro\LaravelVatEuValidator
 * @author  Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 */
class VatValidator
{
    /**
     * Regular expression patterns per country code
     *
     * @var array
     * @link http://ec.europa.eu/taxation_customs/vies/faq.html?locale=en#item_11
     */
    protected static $pattern_expression = array(
        'AT' => 'U[A-Z\d]{8}',
        'AE' => '\d{15}',
        'BE' => '(0\d{9}|\d{10})',
        'BG' => '\d{9,10}',
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
        'IE' => '[A-Z\d]{8}|[A-Z\d]{9}',
        'IN' => 'V\d{11}',
        'IT' => '\d{11}',
        'LT' => '(\d{9}|\d{12})',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'MT' => '\d{8}',
        'NL' => '\d{9}B\d{2}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}',
        'JP' => '\d{12}|\d{13}',
    );

    /**
     * Validate a VAT number format.
     *
     * @param string $vatNumber
     *
     * @return boolean
     */
    public function validate(string $vatNumber): bool
    {
        $vatNumber = $this->vatCleaner($vatNumber);
        list($country, $number) = $this->splitVat($vatNumber);

        if (! isset(self::$pattern_expression[$country])) {
            return false;
        }
        
        return preg_match('/^' . self::$pattern_expression[$country] . '$/', $number) > 0;
    }

    /**
     * @param string $vatNumber
     *
     * @return string
     */
    private function vatCleaner(string $vatNumber): string
    {
        $vatNumber_no_spaces = trim($vatNumber);
        return strtoupper($vatNumber_no_spaces);
    }

    /**
     * @param string $vatNumber
     *
     * @return array
     */
    private function splitVat(string $vatNumber): array
    {
        return [
            substr($vatNumber, 0, 2),
            substr($vatNumber, 2),
        ];
    }
}
