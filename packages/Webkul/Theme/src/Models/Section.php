<?php

namespace Webkul\Theme\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Admin\Database\Factories\SectionFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Theme\Contracts\Section as SectionContract;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Sections\SectionType;
use Webkul\Theme\SectionSchema;

class Section extends TranslatableModel implements SectionContract
{
    use HasFactory;

    /**
     * Image carousel type.
     *
     * @deprecated Use SectionTypeEnum::IMAGE_CAROUSEL instead.
     */
    public const IMAGE_CAROUSEL = SectionTypeEnum::IMAGE_CAROUSEL->value;

    /**
     * Product carousel type.
     *
     * @deprecated Use SectionTypeEnum::PRODUCT_CAROUSEL instead.
     */
    public const PRODUCT_CAROUSEL = SectionTypeEnum::PRODUCT_CAROUSEL->value;

    /**
     * Category carousel type.
     *
     * @deprecated Use SectionTypeEnum::CATEGORY_CAROUSEL instead.
     */
    public const CATEGORY_CAROUSEL = SectionTypeEnum::CATEGORY_CAROUSEL->value;

    /**
     * Footer links type.
     *
     * @deprecated Use SectionTypeEnum::FOOTER_LINKS instead.
     */
    public const FOOTER_LINKS = SectionTypeEnum::FOOTER_LINKS->value;

    /**
     * Static content type.
     *
     * @deprecated Use SectionTypeEnum::STATIC_CONTENT instead.
     */
    public const STATIC_CONTENT = SectionTypeEnum::STATIC_CONTENT->value;

    /**
     * Services content type.
     *
     * @deprecated Use SectionTypeEnum::SERVICES_CONTENT instead.
     */
    public const SERVICES_CONTENT = SectionTypeEnum::SERVICES_CONTENT->value;

    /**
     * Every core section type.
     *
     * @deprecated Use SectionSchema::types() for the types a theme offers.
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
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'options',
        'draft_options',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'theme_sections';

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
     * Get the section type the section's theme handles it with.
     */
    public function getTypeInstance(): ?SectionType
    {
        return app(SectionSchema::class)->type($this->theme_code, $this->type);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SectionFactory::new();
    }
}
