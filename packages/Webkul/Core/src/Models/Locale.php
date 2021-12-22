<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Database\Factories\LocaleFactory;
use Webkul\Core\Contracts\Locale as LocaleContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locale extends Model implements LocaleContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'direction',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return LocaleFactory::new();
    }
}
