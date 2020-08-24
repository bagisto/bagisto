<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    /**
     * Apply the given theme by setting the value to the default (or given) channel.
     *
     * @param string $theme
     */
    public function applyTheme(string $theme, string $channel = 'default'): void
    {
        $channel = Channel::where('code', $channel)->first();

        if (! $channel) {
            throw new \Exception(
                "Given theme '$theme' could not applied because channel '$channel' could not be fetched from database"
            );
        }

        $channel->update(['theme' => $theme]);
    }
}
