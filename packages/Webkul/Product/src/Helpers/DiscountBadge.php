<?php

namespace Webkul\Product\Helpers;

/**
 * Helper para calcular el porcentaje de descuento del badge "-X% OFF"
 * mostrado en las tarjetas de producto del storefront (HU-11).
 *
 * Reglas de negocio:
 * - Se calcula a partir del precio regular vs. el precio final (special price,
 *   reglas de catálogo, etc.) que ya resuelve el motor de precios de Bagisto.
 * - Solo se muestra si el descuento supera un umbral mínimo (por defecto 5%).
 * - Retorna null cuando no corresponde mostrar el badge.
 */
class DiscountBadge
{
    /**
     * Umbral mínimo de descuento (en %) para mostrar el badge.
     */
    public const MIN_DISCOUNT_THRESHOLD = 5;

    /**
     * Calcula el porcentaje de descuento entre el precio regular y el precio final.
     */
    public static function calculatePercentage(
        float|int|string|null $regularPrice,
        float|int|string|null $finalPrice,
        int $minThreshold = self::MIN_DISCOUNT_THRESHOLD
    ): ?int {
        $regularPrice = (float) $regularPrice;

        $finalPrice = (float) $finalPrice;

        if (
            $regularPrice <= 0
            || $finalPrice < 0
            || $finalPrice >= $regularPrice
        ) {
            return null;
        }

        $percentage = (int) round((1 - $finalPrice / $regularPrice) * 100);

        if (
            $percentage < $minThreshold
            || $percentage > 100
        ) {
            return null;
        }

        return $percentage;
    }

    /**
     * Calcula el porcentaje a partir del arreglo de precios de Bagisto
     * retornado por AbstractType::getProductPrices().
     *
     * @param  array<string, mixed>  $prices
     */
    public static function fromPrices(array $prices, int $minThreshold = self::MIN_DISCOUNT_THRESHOLD): ?int
    {
        if (! isset($prices['regular']['price'], $prices['final']['price'])) {
            return null;
        }

        return static::calculatePercentage(
            $prices['regular']['price'],
            $prices['final']['price'],
            $minThreshold
        );
    }
}
