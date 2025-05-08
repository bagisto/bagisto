<?php

namespace Webkul\Core\Enums;

enum LocaleEnum: string
{
    case ARABIC               = 'ar';
    case BENGALI              = 'bn';
    case CATALAN              = 'ca';
    case GERMAN               = 'de';
    case ENGLISH              = 'en';
    case SPANISH              = 'es';
    case PERSIAN              = 'fa';
    case FRENCH               = 'fr';
    case HEBREW               = 'he';
    case HINDI                = 'hi_IN';
    case ITALIAN              = 'it';
    case JAPANESE             = 'ja';
    case DUTCH                = 'nl';
    case POLISH               = 'pl';
    case BRAZILIAN_PORTUGUESE = 'pt_BR';
    case RUSSIAN              = 'ru';
    case SINHALA              = 'sin';
    case TURKISH              = 'tr';
    case UKRAINIAN            = 'uk';
    case CHINESE              = 'zh_CN';

    /**
     * Get the display name for the locale.
     */
    public function getName(): string
    {
        return match ($this) {
            self::ARABIC               => 'Arabic',
            self::BENGALI              => 'Bengali',
            self::CATALAN              => 'Catalan',
            self::GERMAN               => 'German',
            self::ENGLISH              => 'English',
            self::SPANISH              => 'Spanish',
            self::PERSIAN              => 'Persian',
            self::FRENCH               => 'French',
            self::HEBREW               => 'Hebrew',
            self::HINDI                => 'Hindi',
            self::ITALIAN              => 'Italian',
            self::JAPANESE             => 'Japanese',
            self::DUTCH                => 'Dutch',
            self::POLISH               => 'Polish',
            self::BRAZILIAN_PORTUGUESE => 'Brazilian Portuguese',
            self::RUSSIAN              => 'Russian',
            self::SINHALA              => 'Sinhala',
            self::TURKISH              => 'Turkish',
            self::UKRAINIAN            => 'Ukrainian',
            self::CHINESE              => 'Chinese',
        };
    }

    /**
     * Get all locales as an associative array.
     *
     * @return array 
     */
    public static function all(): array
    {
        $locales = [];

        foreach (self::cases() as $locale) {
            $locales[$locale->value] = $locale->getName();
        }

        return $locales;
    }
}
