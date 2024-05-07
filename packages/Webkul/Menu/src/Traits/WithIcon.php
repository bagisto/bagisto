<?php

namespace Webkul\Menu\Traits;

use Illuminate\Contracts\View\View;
// use MoonShine\Components\Icon;

trait WithIcon
{
    /**
     * Icon.
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Icon.
     */
    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get Icon.
     */
    public function getIcon(
        int $size = 8,
        string $color = '',
        string $class = ''
    ): View|string 
    {
        if ($this->iconValue() === '') {
            return '';
        }


        return 'Test Icon';
        // return Icon::make(
        //     $this->iconValue(),
        //     $size,
        //     $color
        // )->customAttributes(['class' => $class])->render();
    }

    /**
     * Icon value.
     *
     * @return string
     */
    public function iconValue(): string
    {
        return $this->icon ?? '';
    }
}
