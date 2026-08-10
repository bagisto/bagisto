<?php

namespace Webkul\Core\Helpers;

class SupportedLocales
{
    /**
     * Every locale Bagisto ships, keyed by its code.
     *
     * The single place a locale is added. `name` is the suffix of the translation key its
     * label is read from, so the installer's console and its screens both name it the same
     * way; `direction` is what the locale is seeded with.
     */
    const ALL = [
        'ar' => ['name' => 'arabic', 'direction' => 'rtl'],
        'bn' => ['name' => 'bengali', 'direction' => 'ltr'],
        'ca' => ['name' => 'catalan', 'direction' => 'ltr'],
        'de' => ['name' => 'german', 'direction' => 'ltr'],
        'en' => ['name' => 'english', 'direction' => 'ltr'],
        'es' => ['name' => 'spanish', 'direction' => 'ltr'],
        'fa' => ['name' => 'persian', 'direction' => 'rtl'],
        'fr' => ['name' => 'french', 'direction' => 'ltr'],
        'he' => ['name' => 'hebrew', 'direction' => 'rtl'],
        'hi_IN' => ['name' => 'hindi', 'direction' => 'ltr'],
        'id' => ['name' => 'indonesian', 'direction' => 'ltr'],
        'it' => ['name' => 'italian', 'direction' => 'ltr'],
        'ja' => ['name' => 'japanese', 'direction' => 'ltr'],
        'nl' => ['name' => 'dutch', 'direction' => 'ltr'],
        'pl' => ['name' => 'polish', 'direction' => 'ltr'],
        'pt_BR' => ['name' => 'portuguese', 'direction' => 'ltr'],
        'ro' => ['name' => 'romanian', 'direction' => 'ltr'],
        'ru' => ['name' => 'russian', 'direction' => 'ltr'],
        'sin' => ['name' => 'sinhala', 'direction' => 'ltr'],
        'tr' => ['name' => 'turkish', 'direction' => 'ltr'],
        'uk' => ['name' => 'ukrainian', 'direction' => 'ltr'],
        'zh_CN' => ['name' => 'chinese', 'direction' => 'ltr'],
    ];

    /**
     * Get the codes of every supported locale.
     *
     * @return string[]
     */
    public static function codes(): array
    {
        return array_keys(self::ALL);
    }

    /**
     * Get every supported locale as code => label, in the language being installed in.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_map(
            fn ($locale) => trans('installer::app.installer.index.'.$locale['name']),
            self::ALL
        );
    }

    /**
     * Get the writing direction of a locale.
     */
    public static function direction(string $code): string
    {
        return self::ALL[$code]['direction'] ?? 'ltr';
    }
}
