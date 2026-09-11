<?php

namespace Webkul\Theme\Sections;

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\SectionSchema;

class ServicesContent extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = SectionTypeEnum::SERVICES_CONTENT->value;

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'admin::app.appearance.sections.create.type.services-content';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-store';

    /**
     * Whether the layout draws the section on every page.
     */
    protected bool $layout = true;

    /**
     * The icon and copy for each service promise.
     */
    public function getFields(): array
    {
        return [
            [
                'key' => 'services',
                'type' => SectionSchema::REPEATER,
                'label' => $this->label('services-content.services'),
                'add_label' => $this->label('services-content.add-btn'),
                'fields' => [
                    ['key' => 'service_icon', 'type' => SectionSchema::TEXT, 'label' => $this->label('services-content.service-icon')],
                    ['key' => 'title', 'type' => SectionSchema::TEXT, 'label' => $this->label('services-content.title')],
                    ['key' => 'description', 'type' => SectionSchema::TEXTAREA, 'label' => $this->label('services-content.description')],
                ],
            ],
        ];
    }
}
