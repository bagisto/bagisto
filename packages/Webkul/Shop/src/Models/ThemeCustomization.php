<?php

namespace Webkul\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Shop\Contracts\ThemeCustomization as ThemeCustomizationContract;


class ThemeCustomization extends Model implements ThemeCustomizationContract
{
    use HasFactory;

    /**
     * Image carousel precision.
     *
     * @var string
     */
    public const IMAGE_CAROUSEL = 'image_carousel';

    /**
     * Product carousel precision.
     *
     * @var string
     */
    public const PRODUCT_CAROUSEL = 'product_carousel';

    /**
     * Category carousel precision.
     *
     * @var string
     */
    public const CATEGORY_CAROUSEL = 'category_carousel';

    /**
     * Footer links precision.
     *
     * @var string
     */
    public const FOOTER_LINKS = 'footer_links';

    /**
     * Static precision.
     *
     * @var string
     */
    public const STATIC_CONTENT = 'static_content';

    /**
     * Castable.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Add fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'options',
        'sort_order',
        'status',
    ];
}
