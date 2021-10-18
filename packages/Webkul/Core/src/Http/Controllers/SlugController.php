<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SlugController extends Controller
{
    /**
     * Tables to search slugs in
     * 'table'=>'column'
     *
     * @var string[]
     */
    private $tables = [
        'product_flat' => 'url_key',
        'category_translations' => 'slug',
    ];

    /**
     * Check if slug is unique, if not give it a suffix with a number which indicates its order
     *
     * @param  string|null  $slugArgs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateSlug(?string $slugArgs = null): JsonResponse
    {
        $slugArgsArr = $slugArgs !== null ? explode(':', $slugArgs) : null;
        $slug = request()->get('slug');
        if (preg_match("/^[a-zA-z\d]+(-\d+)*$/", $slug)) {
            $slug = Str::of($slug)
                       ->beforeLast('-') . '';
        }
        if (Route::getRoutes()
                 ->match(app(Request::class)->create($slug))
                 ->uri() !== '{fallbackPlaceholder}') {
            $finalSlug = $slug . '-1';
        } else {
            $sqlRegex = sprintf('^%s(-[:digit:]+)*$', $slug);

            $baseQuery = null;
            foreach ($this->tables as $table => $column) {
                $tableAs = Str::camel($table);
                $union = DB::query()
                           ->selectRaw($tableAs . '.' . $column . ' as slug')
                           ->from($table . ' as ' . $tableAs);
                if ($slugArgsArr !== null && $slugArgsArr[0] === $table) {
                    $union->where($slugArgsArr[2], '<>', $slugArgsArr[1]);
                }
                if ($baseQuery === null) {
                    $baseQuery = $union;
                } else {
                    $baseQuery->union($union);
                }
            }
            $query = DB::query()
                       ->selectRaw('st.slug')
                       ->from($baseQuery, 'st');
            $query->whereRaw(sprintf("st.slug REGEXP '%s'", $sqlRegex));
            $query->orderBy('st.slug', 'desc');
            $last = $query->limit(1)
                          ->first();
            if ($last === null) {
                $finalSlug = $slug;
            } else {
                [$cSlug, $index] = explode('-', $last->slug);
                if ($index !== null) {
                    $index++;
                } else {
                    $index = 1;
                }
                $finalSlug = $cSlug . '-' . $index;
            }
        }
        return response()->json([
            'slug' => $finalSlug,
        ]);
    }
}
