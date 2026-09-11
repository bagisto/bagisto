<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class StaticContent extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::STATIC_CONTENT->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.static-content';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-cms';

    /**
     * Free markup with its own stylesheet.
     */
    public function getFields(): array
    {
        return [
            ['key' => 'html', 'type' => SectionSchema::CODE, 'language' => 'html', 'label' => $this->label('html')],
            ['key' => 'css', 'type' => SectionSchema::CODE, 'language' => 'css', 'label' => $this->label('css')],
        ];
    }

    /**
     * Clean the markup and styles, which are written into the page rather than escaped.
     */
    public function sanitize(array $options): array
    {
        if (array_key_exists('html', $options)) {
            $options['html'] = $this->sanitizeHtml($options['html']);
        }

        if (array_key_exists('css', $options)) {
            $options['css'] = $this->sanitizeCss($options['css']);
        }

        return $options;
    }
}
