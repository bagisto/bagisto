<?php

namespace Webkul\Theme;

use Illuminate\Support\Collection;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Exceptions\InvalidSectionType;
use Webkul\Theme\Sections\SectionType;

class SectionSchema
{
    /**
     * A single line of text.
     */
    public const TEXT = 'text';

    /**
     * A longer free text value.
     */
    public const TEXTAREA = 'textarea';

    /**
     * An uploaded image, stored as a path.
     */
    public const IMAGE = 'image';

    /**
     * Source code edited in a highlighted editor.
     */
    public const CODE = 'code';

    /**
     * A whole number, such as a position or a count.
     */
    public const NUMBER = 'number';

    /**
     * A repeating list of field groups, stored as a list.
     */
    public const REPEATER = 'repeater';

    /**
     * A free key and value map, used by the carousel filters.
     */
    public const FILTERS = 'filters';

    /**
     * Every section type a theme offers, keyed by code in the order the theme declares them, where
     * the first declaration of a code wins and the enum order stands in when nothing is declared.
     *
     * @return Collection<string, SectionType>
     */
    public function types(?string $themeCode = null): Collection
    {
        $declared = $themeCode
            ? config('themes.shop.'.$themeCode.'.customize.sections')
            : null;

        return collect($declared ?? SectionTypeEnum::cases())
            ->map(fn ($entry) => $this->resolve($entry))
            ->filter()
            ->unique(fn (SectionType $type) => $type->getCode())
            ->keyBy(fn (SectionType $type) => $type->getCode());
    }

    /**
     * The type a theme handles a section with, falling back to the core type of that code.
     */
    public function type(?string $themeCode, ?string $code): ?SectionType
    {
        if (blank($code)) {
            return null;
        }

        if ($type = $this->types($themeCode)->get($code)) {
            return $type;
        }

        $core = SectionTypeEnum::tryFrom($code);

        return $core ? $this->resolve($core) : null;
    }

    /**
     * Field schema for every type a theme offers, keyed by the stored type.
     */
    public function all(?string $themeCode = null): array
    {
        return $this->types($themeCode)
            ->map(fn (SectionType $type) => $type->getFields())
            ->all();
    }

    /**
     * Field schema for one section type, or an empty schema for an unknown one.
     */
    public function for(string $type, ?string $themeCode = null): array
    {
        return $this->type($themeCode, $type)?->getFields() ?? [];
    }

    /**
     * Keys a section type edits at the top level of its options.
     */
    public function keysFor(string $type, ?string $themeCode = null): array
    {
        return array_column($this->for($type, $themeCode), 'key');
    }

    /**
     * Resolve a declared core enum case or value, or section type class, reporting and skipping
     * anything else so one bad entry cannot take the editor down.
     */
    protected function resolve(mixed $entry): ?SectionType
    {
        $core = $entry instanceof SectionTypeEnum
            ? $entry
            : SectionTypeEnum::tryFrom(is_string($entry) ? $entry : '');

        $class = $core?->getClassName() ?? $entry;

        if (
            ! is_string($class)
            || ! is_subclass_of($class, SectionType::class)
        ) {
            report(new InvalidSectionType(is_string($entry) ? $entry : get_debug_type($entry)));

            return null;
        }

        return app($class);
    }
}
