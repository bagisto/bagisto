<?php

/**
 * Every depend condition a configuration field declares, as code => condition.
 */
function configurationDepends(): array
{
    $depends = [];

    foreach (config('core') as $item) {
        foreach ($item['fields'] ?? [] as $field) {
            if (empty($field['depends'])) {
                continue;
            }

            $depends[$item['key'].'.'.$field['name']] = $field['depends'];
        }
    }

    return $depends;
}

// ============================================================================
// Depend Conditions
// ============================================================================

it('should name the field a depend condition reads, and the value it expects', function () {
    $malformed = array_filter(
        configurationDepends(),
        fn (string $condition) => ! str_contains($condition, ':')
    );

    expect($malformed)->toBeEmpty();
});

it('should write every boolean depend condition as 0 or 1, never as true or false', function () {
    $spelled = array_filter(
        configurationDepends(),
        fn (string $condition) => (bool) array_intersect(
            explode(',', explode(':', $condition, 2)[1]),
            ['true', 'false']
        )
    );

    expect($spelled)->toBeEmpty();
});

// ============================================================================
// Boolean Defaults
// ============================================================================

it('should default every boolean field to the integer 0 or 1', function () {
    $wrong = [];

    foreach (config('core') as $item) {
        foreach ($item['fields'] ?? [] as $field) {
            if (
                ($field['type'] ?? null) !== 'boolean'
                || ! array_key_exists('default', $field)
            ) {
                continue;
            }

            if (! in_array($field['default'], [0, 1], true)) {
                $wrong[$item['key'].'.'.$field['name']] = $field['default'];
            }
        }
    }

    expect($wrong)->toBeEmpty();
});
