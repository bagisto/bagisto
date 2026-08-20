<?php

namespace Webkul\Theme\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Admin\Database\Factories\SectionFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Theme\Contracts\Section as SectionContract;

class Section extends TranslatableModel implements SectionContract
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
     * Services Content.
     *
     * @var string
     */
    public const SERVICES_CONTENT = 'services_content';

    /**
     * Every type a section may take.
     *
     * @var array
     */
    public const TYPES = [
        self::IMAGE_CAROUSEL,
        self::PRODUCT_CAROUSEL,
        self::CATEGORY_CAROUSEL,
        self::FOOTER_LINKS,
        self::STATIC_CONTENT,
        self::SERVICES_CONTENT,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'theme_sections';

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'options',
        'draft_options',
    ];

    /**
     * With the translations given attributes.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Cast options field to array.
     *
     * @var array
     */
    protected $casts = [
        'draft_status' => 'boolean',
        'options' => 'array',
        'draft_options' => 'array',
    ];

    /**
     * Add fillable properties.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'options',
        'sort_order',
        'status',
        'draft_status',
        'draft_sort_order',
        'channel_id',
        'theme_code',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SectionFactory::new();
    }
}
