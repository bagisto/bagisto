<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class ImageCarousel extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::IMAGE_CAROUSEL->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.image-carousel';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-image';

    /**
     * Slides, each with an image, a heading and where it links to.
     */
    public function getFields(): array
    {
        return [
            [
                'key' => 'images',
                'type' => SectionSchema::REPEATER,
                'label' => $this->label('slider'),
                'add_label' => $this->label('slider-add-btn'),
                'fields' => [
                    ['key' => 'image', 'type' => SectionSchema::IMAGE, 'label' => $this->label('slider-image')],
                    ['key' => 'title', 'type' => SectionSchema::TEXT, 'label' => $this->label('image-title')],
                    ['key' => 'link', 'type' => SectionSchema::TEXT, 'label' => $this->label('link')],
                ],
            ],
        ];
    }

    /**
     * Clear the link of any slide whose scheme could run script.
     */
    public function sanitize(array $options): array
    {
        if (! is_array($options['images'] ?? null)) {
            return $options;
        }

        foreach ($options['images'] as $index => $image) {
            if (
                is_array($image)
                && array_key_exists('link', $image)
            ) {
                $options['images'][$index]['link'] = $this->sanitizeUrl($image['link']);
            }
        }

        return $options;
    }
}
