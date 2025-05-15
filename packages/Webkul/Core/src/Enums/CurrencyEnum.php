<?php

namespace Webkul\Core\Enums;

enum CurrencyEnum: string
{
    case AED = 'AED';
    case ARS = 'ARS';
    case AUD = 'AUD';
    case BDT = 'BDT';
    case BHD = 'BHD';
    case BRL = 'BRL';
    case CAD = 'CAD';
    case CHF = 'CHF';
    case CLP = 'CLP';
    case CNY = 'CNY';
    case COP = 'COP';
    case CZK = 'CZK';
    case DKK = 'DKK';
    case DZD = 'DZD';
    case EGP = 'EGP';
    case EUR = 'EUR';
    case FJD = 'FJD';
    case GBP = 'GBP';
    case HKD = 'HKD';
    case HUF = 'HUF';
    case IDR = 'IDR';
    case ILS = 'ILS';
    case INR = 'INR';
    case JOD = 'JOD';
    case JPY = 'JPY';
    case KRW = 'KRW';
    case KWD = 'KWD';
    case KZT = 'KZT';
    case LBP = 'LBP';
    case LKR = 'LKR';
    case LYD = 'LYD';
    case MAD = 'MAD';
    case MUR = 'MUR';
    case MXN = 'MXN';
    case MYR = 'MYR';
    case NGN = 'NGN';
    case NOK = 'NOK';
    case NPR = 'NPR';
    case NZD = 'NZD';
    case OMR = 'OMR';
    case PAB = 'PAB';
    case PEN = 'PEN';
    case PHP = 'PHP';
    case PKR = 'PKR';
    case PLN = 'PLN';
    case PYG = 'PYG';
    case QAR = 'QAR';
    case RON = 'RON';
    case RUB = 'RUB';
    case SAR = 'SAR';
    case SEK = 'SEK';
    case SGD = 'SGD';
    case THB = 'THB';
    case TND = 'TND';
    case TRY = 'TRY';
    case TWD = 'TWD';
    case UAH = 'UAH';
    case USD = 'USD';
    case UZS = 'UZS';
    case VEF = 'VEF';
    case VND = 'VND';
    case XAF = 'XAF';
    case XOF = 'XOF';
    case ZAR = 'ZAR';
    case ZMW = 'ZMW';

    /**
     * Get the display name for the currency.
     */
    public function getName(): string
    {
        return match ($this) {
            self::AED => 'United Arab Emirates Dirham',
            self::ARS => 'Argentine Peso',
            self::AUD => 'Australian Dollar',
            self::BDT => 'Bangladeshi Taka',
            self::BHD => 'Bahraini Dinar',
            self::BRL => 'Brazilian Real',
            self::CAD => 'Canadian Dollar',
            self::CHF => 'Swiss Franc',
            self::CLP => 'Chilean Peso',
            self::CNY => 'Chinese Yuan',
            self::COP => 'Colombian Peso',
            self::CZK => 'Czech Koruna',
            self::DKK => 'Danish Krone',
            self::DZD => 'Algerian Dinar',
            self::EGP => 'Egyptian Pound',
            self::EUR => 'Euro',
            self::FJD => 'Fijian Dollar',
            self::GBP => 'British Pound Sterling',
            self::HKD => 'Hong Kong Dollar',
            self::HUF => 'Hungarian Forint',
            self::IDR => 'Indonesian Rupiah',
            self::ILS => 'Israeli New Shekel',
            self::INR => 'Indian Rupee',
            self::JOD => 'Jordanian Dinar',
            self::JPY => 'Japanese Yen',
            self::KRW => 'South Korean Won',
            self::KWD => 'Kuwaiti Dinar',
            self::KZT => 'Kazakhstani Tenge',
            self::LBP => 'Lebanese Pound',
            self::LKR => 'Sri Lankan Rupee',
            self::LYD => 'Libyan Dinar',
            self::MAD => 'Moroccan Dirham',
            self::MUR => 'Mauritian Rupee',
            self::MXN => 'Mexican Peso',
            self::MYR => 'Malaysian Ringgit',
            self::NGN => 'Nigerian Naira',
            self::NOK => 'Norwegian Krone',
            self::NPR => 'Nepalese Rupee',
            self::NZD => 'New Zealand Dollar',
            self::OMR => 'Omani Rial',
            self::PAB => 'Panamanian Balboa',
            self::PEN => 'Peruvian Nuevo Sol',
            self::PHP => 'Philippine Peso',
            self::PKR => 'Pakistani Rupee',
            self::PLN => 'Polish Zloty',
            self::PYG => 'Paraguayan Guarani',
            self::QAR => 'Qatari Rial',
            self::RON => 'Romanian Leu',
            self::RUB => 'Russian Ruble',
            self::SAR => 'Saudi Riyal',
            self::SEK => 'Swedish Krona',
            self::SGD => 'Singapore Dollar',
            self::THB => 'Thai Baht',
            self::TND => 'Tunisian Dinar',
            self::TRY => 'Turkish Lira',
            self::TWD => 'New Taiwan Dollar',
            self::UAH => 'Ukrainian Hryvnia',
            self::USD => 'United States Dollar',
            self::UZS => 'Uzbekistani Som',
            self::VEF => 'Venezuelan Bolívar',
            self::VND => 'Vietnamese Dong',
            self::XAF => 'CFA Franc BEAC',
            self::XOF => 'CFA Franc BCEAO',
            self::ZAR => 'South African Rand',
            self::ZMW => 'Zambian Kwacha',
        };
    }

    /**
     * Get all currencies as an array.
     */
    public static function all(): array
    {
        $currencies = [];

        foreach (self::cases() as $currency) {
            $currencies[$currency->value] = $currency->getName();
        }

        return $currencies;
    }

    /**
     * Get the currency symbol.
     */
    public function symbol(): string
    {
        return match ($this) {
            self::AED => 'د.إ',
            self::ARS => '$',
            self::AUD => 'A$',
            self::BDT => '৳',
            self::BHD => '.د.ب',
            self::BRL => 'R$',
            self::CAD => 'C$',
            self::CHF => 'CHF',
            self::CLP => '$',
            self::CNY => '¥',
            self::COP => '$',
            self::CZK => 'Kč',
            self::DKK => 'kr',
            self::DZD => 'د.ج',
            self::EGP => '£',
            self::EUR => '€',
            self::FJD => 'FJ$',
            self::GBP => '£',
            self::HKD => 'HK$',
            self::HUF => 'Ft',
            self::IDR => 'Rp',
            self::ILS => '₪',
            self::INR => '₹',
            self::JOD => 'د.ا',
            self::JPY => '¥',
            self::KRW => '₩',
            self::KWD => 'د.ك',
            self::KZT => '₸',
            self::LBP => 'ل.ل',
            self::LKR => '₨',
            self::LYD => 'ل.د',
            self::MAD => 'د.م.',
            self::MUR => '₨',
            self::MXN => '$',
            self::MYR => 'RM',
            self::NGN => '₦',
            self::NOK => 'kr',
            self::NPR => '₨',
            self::NZD => 'NZ$',
            self::OMR => 'ر.ع.',
            self::PAB => 'B/.',
            self::PEN => 'S/.',
            self::PHP => '₱',
            self::PKR => '₨',
            self::PLN => 'zł',
            self::PYG => '₲',
            self::QAR => 'ر.ق',
            self::RON => 'lei',
            self::RUB => '₽',
            self::SAR => 'ر.س',
            self::SEK => 'kr',
            self::SGD => 'S$',
            self::THB => '฿',
            self::TND => 'د.ت',
            self::TRY => '₺',
            self::TWD => 'NT$',
            self::UAH => '₴',
            self::USD => '$',
            self::UZS => 'лв',
            self::VEF => 'Bs.',
            self::VND => '₫',
            self::XAF => 'FCFA',
            self::XOF => 'CFA',
            self::ZAR => 'R',
            self::ZMW => 'ZK',
        };
    }

    /**
     * Get the currency code.
     */
    public function code(): string
    {
        return $this->value;
    }

    /**
     * Get the currency symbol for the given currency code.
     */
    public static function getSymbol(string $currencyCode): string
    {
        return self::from($currencyCode)->symbol();
    }
}
