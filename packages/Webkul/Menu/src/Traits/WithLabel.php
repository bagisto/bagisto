<?php

namespace Webkul\Menu\Traits;

use Closure;

trait WithLabel
{
    /**
     * Closure or string label.
     */
    protected Closure|string $label = '';

    /**
     * Translatable.
     */
    protected bool $translatable = false;

    /**
     * Translatable key.
     */
    protected string $translatableKey = '';

    /**
     * Label.
     */
    public function label(): string
    {
        $this->label = value($this->label, $this);

        if ($this->translatable) {
            return __(
                str($this->label)->when(
                    $this->translatableKey,
                    fn ($str) => $str->prepend($this->translatableKey.'.')
                )->value()
            );
        }

        return $this->label;
    }

    /**
     * Set Label.
     */
    public function setLabel(Closure|string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Translatable.
     */
    public function translatable(string $key = ''): static
    {
        $this->translatable = true;
        $this->translatableKey = $key;

        return $this;
    }
}
