<?php

namespace Webkul\Theme\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Theme\Contracts\Section as SectionContract;
use Webkul\Theme\Database\Factories\SectionFactory;
use Webkul\Theme\Sections\SectionType;
use Webkul\Theme\SectionSchema;

class Section extends TranslatableModel implements SectionContract
{
    use HasFactory;

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
        'status' => 'boolean',
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
