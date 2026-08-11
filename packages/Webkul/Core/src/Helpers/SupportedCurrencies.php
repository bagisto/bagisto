<?php

namespace Webkul\Core\Helpers;

class SupportedCurrencies
{
    /**
     * Every currency Bagisto ships, keyed by its ISO 4217 code.
     *
     * The single place a currency is added. `name` is the suffix of the translation key its
     * label is read from, so the installer's console and its screens both name it the same
     * way; `symbol` and `decimal` are what the currency is seeded with, the latter being the
     * number of minor units the currency actually has rather than an assumed two.
     */
    const ALL = [
        'AED' => ['name' => 'united-arab-emirates-dirham', 'symbol' => 'د.إ', 'decimal' => 2],
        'ARS' => ['name' => 'argentine-peso', 'symbol' => '$', 'decimal' => 2],
        'AUD' => ['name' => 'australian-dollar', 'symbol' => 'A$', 'decimal' => 2],
        'BDT' => ['name' => 'bangladeshi-taka', 'symbol' => '৳', 'decimal' => 2],
        'BHD' => ['name' => 'bahraini-dinar', 'symbol' => '.د.ب', 'decimal' => 3],
        'BRL' => ['name' => 'brazilian-real', 'symbol' => 'R$', 'decimal' => 2],
        'CAD' => ['name' => 'canadian-dollar', 'symbol' => 'C$', 'decimal' => 2],
        'CHF' => ['name' => 'swiss-franc', 'symbol' => 'CHF', 'decimal' => 2],
        'CLP' => ['name' => 'chilean-peso', 'symbol' => '$', 'decimal' => 0],
        'CNY' => ['name' => 'chinese-yuan', 'symbol' => '¥', 'decimal' => 2],
        'COP' => ['name' => 'colombian-peso', 'symbol' => '$', 'decimal' => 2],
        'CZK' => ['name' => 'czech-koruna', 'symbol' => 'Kč', 'decimal' => 2],
        'DKK' => ['name' => 'danish-krone', 'symbol' => 'kr', 'decimal' => 2],
        'DZD' => ['name' => 'algerian-dinar', 'symbol' => 'د.ج', 'decimal' => 2],
        'EGP' => ['name' => 'egyptian-pound', 'symbol' => 'E£', 'decimal' => 2],
        'EUR' => ['name' => 'euro', 'symbol' => '€', 'decimal' => 2],
        'FJD' => ['name' => 'fijian-dollar', 'symbol' => 'FJ$', 'decimal' => 2],
        'GBP' => ['name' => 'british-pound-sterling', 'symbol' => '£', 'decimal' => 2],
        'HKD' => ['name' => 'hong-kong-dollar', 'symbol' => 'HK$', 'decimal' => 2],
        'HUF' => ['name' => 'hungarian-forint', 'symbol' => 'Ft', 'decimal' => 2],
        'IDR' => ['name' => 'indonesian-rupiah', 'symbol' => 'Rp', 'decimal' => 2],
        'ILS' => ['name' => 'israeli-new-shekel', 'symbol' => '₪', 'decimal' => 2],
        'INR' => ['name' => 'indian-rupee', 'symbol' => '₹', 'decimal' => 2],
        'JOD' => ['name' => 'jordanian-dinar', 'symbol' => 'د.ا', 'decimal' => 3],
        'JPY' => ['name' => 'japanese-yen', 'symbol' => '¥', 'decimal' => 0],
        'KRW' => ['name' => 'south-korean-won', 'symbol' => '₩', 'decimal' => 0],
        'KWD' => ['name' => 'kuwaiti-dinar', 'symbol' => 'د.ك', 'decimal' => 3],
        'KZT' => ['name' => 'kazakhstani-tenge', 'symbol' => '₸', 'decimal' => 2],
        'LBP' => ['name' => 'lebanese-pound', 'symbol' => 'ل.ل', 'decimal' => 2],
        'LKR' => ['name' => 'sri-lankan-rupee', 'symbol' => '₨', 'decimal' => 2],
        'LYD' => ['name' => 'libyan-dinar', 'symbol' => 'ل.د', 'decimal' => 3],
        'MAD' => ['name' => 'moroccan-dirham', 'symbol' => 'د.م.', 'decimal' => 2],
        'MUR' => ['name' => 'mauritian-rupee', 'symbol' => '₨', 'decimal' => 2],
        'MXN' => ['name' => 'mexican-peso', 'symbol' => '$', 'decimal' => 2],
        'MYR' => ['name' => 'malaysian-ringgit', 'symbol' => 'RM', 'decimal' => 2],
        'NGN' => ['name' => 'nigerian-naira', 'symbol' => '₦', 'decimal' => 2],
        'NOK' => ['name' => 'norwegian-krone', 'symbol' => 'kr', 'decimal' => 2],
        'NPR' => ['name' => 'nepalese-rupee', 'symbol' => '₨', 'decimal' => 2],
        'NZD' => ['name' => 'new-zealand-dollar', 'symbol' => 'NZ$', 'decimal' => 2],
        'OMR' => ['name' => 'omani-rial', 'symbol' => '﷼', 'decimal' => 3],
        'PAB' => ['name' => 'panamanian-balboa', 'symbol' => 'B/.', 'decimal' => 2],
        'PEN' => ['name' => 'peruvian-nuevo-sol', 'symbol' => 'S/', 'decimal' => 2],
        'PHP' => ['name' => 'philippine-peso', 'symbol' => '₱', 'decimal' => 2],
        'PKR' => ['name' => 'pakistani-rupee', 'symbol' => '₨', 'decimal' => 2],
        'PLN' => ['name' => 'polish-zloty', 'symbol' => 'zł', 'decimal' => 2],
        'PYG' => ['name' => 'paraguayan-guarani', 'symbol' => '₲', 'decimal' => 0],
        'QAR' => ['name' => 'qatari-rial', 'symbol' => '﷼', 'decimal' => 2],
        'RON' => ['name' => 'romanian-leu', 'symbol' => 'lei', 'decimal' => 2],
        'RUB' => ['name' => 'russian-ruble', 'symbol' => '₽', 'decimal' => 2],
        'SAR' => ['name' => 'saudi-riyal', 'symbol' => '﷼', 'decimal' => 2],
        'SEK' => ['name' => 'swedish-krona', 'symbol' => 'kr', 'decimal' => 2],
        'SGD' => ['name' => 'singapore-dollar', 'symbol' => 'S$', 'decimal' => 2],
        'THB' => ['name' => 'thai-baht', 'symbol' => '฿', 'decimal' => 2],
        'TND' => ['name' => 'tunisian-dinar', 'symbol' => 'د.ت', 'decimal' => 3],
        'TRY' => ['name' => 'turkish-lira', 'symbol' => '₺', 'decimal' => 2],
        'TWD' => ['name' => 'new-taiwan-dollar', 'symbol' => 'NT$', 'decimal' => 2],
        'UAH' => ['name' => 'ukrainian-hryvnia', 'symbol' => '₴', 'decimal' => 2],
        'USD' => ['name' => 'united-states-dollar', 'symbol' => '$', 'decimal' => 2],
        'UZS' => ['name' => 'uzbekistani-som', 'symbol' => 'сўм', 'decimal' => 2],
        'VES' => ['name' => 'venezuelan-bolívar', 'symbol' => 'Bs.F', 'decimal' => 2],
        'VND' => ['name' => 'vietnamese-dong', 'symbol' => '₫', 'decimal' => 0],
        'XAF' => ['name' => 'cfa-franc-beac', 'symbol' => 'FCFA', 'decimal' => 0],
        'XOF' => ['name' => 'cfa-franc-bceao', 'symbol' => 'CFA', 'decimal' => 0],
        'ZAR' => ['name' => 'south-african-rand', 'symbol' => 'R', 'decimal' => 2],
        'ZMW' => ['name' => 'zambian-kwacha', 'symbol' => 'ZK', 'decimal' => 2],
    ];

    /**
     * Get the codes of every supported currency.
     *
     * @return string[]
     */
    public static function codes(): array
    {
        return array_keys(self::ALL);
    }

    /**
     * Get every supported currency as code => label, in the language being installed in.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_map(
            fn ($currency) => trans('installer::app.installer.index.environment-configuration.'.$currency['name']),
            self::ALL
        );
    }

    /**
     * Get the symbol a currency is written with.
     */
    public static function symbol(string $code): string
    {
        return self::ALL[$code]['symbol'] ?? '';
    }

    /**
     * Get the number of decimal places a currency is charged with.
     */
    public static function decimal(string $code): int
    {
        return self::ALL[$code]['decimal'] ?? 2;
    }
}
