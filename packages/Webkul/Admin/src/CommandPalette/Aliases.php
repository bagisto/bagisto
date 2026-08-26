<?php

namespace Webkul\Admin\CommandPalette;

class Aliases
{
    /**
     * Where the declared aliases are read from.
     */
    const CONFIG_KEY = 'command_palette.aliases';

    /**
     * Extra terms declared for a menu or configuration key.
     *
     * Lets an operator find a page by the word they use for it rather than the word the
     * codebase uses - "currency" for Currencies, "mail" for the email settings.
     *
     * Read whole rather than by dotted path, because the keys themselves contain dots.
     *
     * @return string[]
     */
    public function for(?string $key): array
    {
        if (! $key) {
            return [];
        }

        return config(self::CONFIG_KEY, [])[$key] ?? [];
    }
}
