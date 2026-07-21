<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Admin\Http\Controllers\Controller;

/**
 * Exportación del catálogo completo de productos a CSV desde el backoffice (HU-04).
 *
 * A diferencia del export genérico del DataGrid (que exporta la página actual),
 * este controlador exporta el catálogo COMPLETO usando streaming por bloques,
 * por lo que soporta catálogos grandes sin agotar la memoria.
 */
class ProductCatalogExportController extends Controller
{
    /**
     * Cabeceras del archivo CSV.
     *
     * @var array<int, string>
     */
    protected array $headers = [
        'ID',
        'SKU',
        'Nombre',
        'Tipo',
        'Estado',
        'Precio',
        'Stock',
        'Familia de atributos',
        'Categorías',
        'URL Key',
        'Canal',
        'Creado',
    ];

    /**
     * Descarga el catálogo completo como CSV (streaming por bloques de 500).
     */
    public function export(): StreamedResponse
    {
        $fileName = 'catalogo-productos-'.date('Y-m-d-His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            /**
             * BOM UTF-8 para que Excel reconozca tildes y caracteres especiales.
             */
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, $this->headers, ',', '"', '\\');

            $this->prepareQueryBuilder()->chunk(500, function ($products) use ($handle) {
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->product_id,
                        $product->sku,
                        $product->name,
                        $product->type,
                        $product->status ? 'Activo' : 'Inactivo',
                        $product->price,
                        $product->quantity ?? 0,
                        $product->attribute_family,
                        $product->categories,
                        $product->url_key,
                        $product->channel,
                        $product->created_at,
                    ], ',', '"', '\\');
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Query del catálogo completo desde la tabla plana `product_flat`.
     */
    protected function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();

        $locale = app()->getLocale();

        return DB::table('product_flat')
            ->leftJoin('attribute_families as af', 'product_flat.attribute_family_id', '=', 'af.id')
            ->select(
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'product_flat.url_key',
                'product_flat.channel',
                'product_flat.created_at',
                'af.name as attribute_family',
            )
            ->addSelect(DB::raw(
                '(SELECT SUM(qty) FROM '.$tablePrefix.'product_inventories WHERE '.$tablePrefix.'product_inventories.product_id = '.$tablePrefix.'product_flat.product_id) as quantity'
            ))
            ->addSelect(DB::raw(
                "(SELECT GROUP_CONCAT(ct.name SEPARATOR ' | ') FROM ".$tablePrefix.'product_categories pc JOIN '.$tablePrefix."category_translations ct ON ct.category_id = pc.category_id AND ct.locale = '".$locale."' WHERE pc.product_id = ".$tablePrefix.'product_flat.product_id) as categories'
            ))
            ->where('product_flat.locale', $locale)
            ->orderBy('product_flat.product_id');
    }
}
