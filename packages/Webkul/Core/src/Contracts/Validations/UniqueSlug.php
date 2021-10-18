<?php

namespace Webkul\Core\Contracts\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Rule;

class UniqueSlug implements Rule
{
    /**
     * @var string[]
     */
    private $tables = [
        'product_flat' => 'url_key',
        'category_translations' => 'slug',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        if (Route::getRoutes()
                 ->match(app(Request::class)->create($value))
                 ->uri() !== '{fallbackPlaceholder}') {
            return false;
        }
        $parameters = func_get_arg(2);
        switch (count($parameters)) {
            case 1: //only locale after as rule params, Ex unique_slug:en
                foreach ($this->tables as $table => $column) {
                    if (DB::query()
                          ->from($table)
                          ->select(DB::raw(1))
                          ->where($column, $value)
                          ->where('locale', $parameters[0])
                          ->limit(1)
                          ->exists()) {
                        return false;
                    }
                }
                return true;
            case 2://tableName,slugColumn, Ex unique_slug,category_translations,slug
            case 3://tableName,slugColumn,ignoreId, Ex unique_slug:category_translations,slug,9
            case 4://tableName,slugColumn,ignoreId,ignoreIdKey Ex unique_slug:category_translations,slug,9,category_id
                $key = count($parameters) === 4 ? $parameters[3] : 'id';
                $locale = DB::query()
                            ->from($parameters[0])
                            ->select('locale')
                            ->where($key, $parameters[2])
                            ->first()->locale;
                foreach ($this->tables as $table => $column) {
                    $query = DB::query()
                               ->from($table)
                               ->select(DB::raw(1))
                               ->where($column, $value)
                               ->where('locale', $locale);
                    if ($table === $parameters[0] && isset($parameters[2])) {
                        $query->where($key, '<>', $parameters[2]);
                    }
                    if ($query->limit(1)
                              ->exists()) {
                        return false;
                    }
                }
                return true;
            default:
                throw new \Exception('Invalid params passed to unique_slug.');
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('core::validation.unique_slug');
    }
}
