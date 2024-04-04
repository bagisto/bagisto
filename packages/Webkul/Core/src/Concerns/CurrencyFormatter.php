<?php

namespace Webkul\Core\Concerns;

use Webkul\Core\Contracts\Currency;
use Webkul\Core\Enums\CurrencyPositionEnum;

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
     * Use default formatter.
     */
    public function useDefaultCurrencyFormatter(?float $price, Currency $currency): string
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        if ($currency->symbol) {
            /**
             * If, somehow, the currency symbol mentioned matches with the user-defined symbol,
             * then we can simply use the 'formatCurrency' method.
             */
            if ($this->currencySymbol($currency) == $currency->symbol) {
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

        if (! empty($currency->group_separator)) {
            $formattedCurrency = str_replace(
                $formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL),
                $currency->group_separator,
                $formattedCurrency
            );
        }

        if (
            $currency->decimal > 0
            && ! empty($currency->decimal_separator)
        ) {
            $formattedCurrency = str_replace(
                $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
                $currency->decimal_separator,
                $formattedCurrency
            );
        }

        $symbol = ! empty($currency->symbol)
            ? $currency->symbol
            : $currency->code;

        return match ($currency->currency_position) {
            CurrencyPositionEnum::LEFT->value             => $symbol.$formattedCurrency,
            CurrencyPositionEnum::LEFT_WITH_SPACE->value  => $symbol.' '.$formattedCurrency,
            CurrencyPositionEnum::RIGHT->value            => $formattedCurrency.$symbol,
            CurrencyPositionEnum::RIGHT_WITH_SPACE->value => $formattedCurrency.' '.$symbol,
        };
    }

    /**
     * Return currency symbol from currency code.
     *
     * @param  string|\Webkul\Core\Contracts\Currency  $currency
     */
    public function currencySymbol($currency): string
    {
        $code = $currency instanceof \Webkul\Core\Contracts\Currency ? $currency->code : $currency;

        $formatter = new \NumberFormatter(app()->getLocale().'@currency='.$code, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}
