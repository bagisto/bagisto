<?php

namespace Webkul\Theme\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Theme\Contracts\SectionTranslation as SectionTranslationContract;

class SectionTranslation extends Model implements SectionTranslationContract
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'theme_section_translations';

    /**
     * Timestamp false of the model
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Castable.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'draft_options' => 'array',
    ];

    /**
     * Add fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'options',
        'draft_options',
    ];
}
