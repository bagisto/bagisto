<?php

namespace Webkul\Core\Concerns;

use Webkul\Core\Contracts\Currency;
use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Repositories\CurrencyRepository;

trait CurrencyFormatter
{
    /**
     * Format currency.
     */
    public function formatCurrency(?float $price, Currency $currency): string
    {
        if ($currency->currency_position) {
            return $this->useCustomCurrencyFormatter($price, $currency);
        }

        return $this->useDefaultCurrencyFormatter($price, $currency);
    }

    /**
     * Use the locale formatter, keeping its own symbol when the custom symbol is the same one.
     */
    public function useDefaultCurrencyFormatter(?float $price, Currency $currency): string
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        if ($currency->symbol) {
            if ($this->localeCurrencySymbol($currency->code) == $currency->symbol) {
                return $formatter->formatCurrency($price, $currency->code);
            }

            $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $currency->symbol);

            return $formatter->format($price);
        }

        return $formatter->formatCurrency($price, $currency->code);
    }

    /**
     * Use custom formatter.
     */
    public function useCustomCurrencyFormatter(?float $price, Currency $currency): string
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, '');

        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $currency->decimal ?? 2);

        $formattedCurrency = preg_replace('/^\s+|\s+$/u', '', $formatter->format($price));

        $separators = [];

        if (! empty($currency->group_separator)) {
            $separators[$formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL)] = $currency->group_separator;
        }

        if (
            $currency->decimal > 0
            && ! empty($currency->decimal_separator)
        ) {
            $separators[$formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL)] = $currency->decimal_separator;
        }

        if (! empty($separators)) {
            $formattedCurrency = strtr($formattedCurrency, $separators);
        }

        $symbol = ! empty($currency->symbol)
            ? $currency->symbol
            : $currency->code;

        return match ($currency->currency_position) {
            CurrencyPositionEnum::LEFT->value => $symbol.$formattedCurrency,
            CurrencyPositionEnum::LEFT_WITH_SPACE->value => $symbol.' '.$formattedCurrency,
            CurrencyPositionEnum::RIGHT->value => $formattedCurrency.$symbol,
            CurrencyPositionEnum::RIGHT_WITH_SPACE->value => $formattedCurrency.' '.$symbol,
        };
    }

    /**
     * Return the currency's custom symbol, falling back to the locale symbol for its code.
     *
     * @param  string|Currency  $currency
     */
    public function currencySymbol($currency): string
    {
        $code = $currency instanceof Currency ? $currency->code : $currency;

        if (! $currency instanceof Currency) {
            $currency = app(CurrencyRepository::class)->findOneByField('code', $code);
        }

        if ($currency?->symbol) {
            return $currency->symbol;
        }

        return $this->localeCurrencySymbol($code);
    }

    /**
     * Return the symbol the current locale uses for the currency code.
     */
    protected function localeCurrencySymbol(string $code): string
    {
        $formatter = new \NumberFormatter(app()->getLocale().'@currency='.$code, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}
