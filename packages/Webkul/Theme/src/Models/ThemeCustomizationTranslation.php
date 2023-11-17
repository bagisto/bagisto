<?php

namespace Webkul\Theme\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Theme\Contracts\ThemeCustomizationTranslation as ThemeCustomizationTranslationContract;

class ThemeCustomizationTranslation extends Model implements ThemeCustomizationTranslationContract
{
    use HasFactory;

    /**
     * Timestamp false of the model
     *
     * @var bool
     */
    public $timestamps = false;

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
     * Services Content.
     *
     * @var string
     */
    public const SERVICES_CONTENT = 'services_content';

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
        'name',
        'options',
    ];
}
