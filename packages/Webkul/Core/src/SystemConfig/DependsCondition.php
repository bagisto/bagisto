<?php

namespace Webkul\Core\SystemConfig;

class DependsCondition
{
    /**
     * The spellings a boolean condition may be written with, against what a form posts for them.
     */
    public const BOOLEAN_ALIASES = [
        'true' => '1',
        'false' => '0',
    ];

    /**
     * Create a condition from the depend a configuration field declares.
     */
    public function __construct(protected ?string $condition) {}

    /**
     * The name of the sibling field the condition reads.
     */
    public function fieldName(): string
    {
        return explode(':', (string) $this->condition, 2)[0];
    }

    /**
     * Whether the condition permits the field it guards, which a save carrying no value for the
     * sibling leaves unjudged so a partial save still writes and validates what it does carry.
     */
    public function permits(bool $siblingSubmitted, mixed $siblingValue = null): bool
    {
        if (
            ! $this->exists()
            || ! $siblingSubmitted
        ) {
            return true;
        }

        return $this->isMetBy($siblingValue);
    }

    /**
     * Whether the field declares a condition at all.
     */
    protected function exists(): bool
    {
        return ! empty($this->condition);
    }

    /**
     * Whether the sibling's value satisfies the condition.
     */
    protected function isMetBy(mixed $value): bool
    {
        $expected = array_pad(explode(':', (string) $this->condition, 2), 2, '')[1];

        return in_array(
            $this->comparable($value),
            array_map(fn ($candidate) => $this->comparable($candidate), explode(',', $expected)),
            true
        );
    }

    /**
     * The comparable form of a value, so a boolean spelled true matches the 1 a form posts for it.
     */
    protected function comparable(mixed $value): string
    {
        return self::BOOLEAN_ALIASES[(string) $value] ?? (string) $value;
    }
}
