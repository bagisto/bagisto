<?php

namespace Webkul\Menu\Traits;

use Closure;

trait HasCanSee
{
    /**
     * Can see callback.
     *
     * @var Closure|null
     */
    protected ?Closure $canSeeCallback = null;

    /**
     * Can See.
     *
     * @param Closure $callback
     * @return static
     */
    public function canSee(Closure $callback): static
    {
        $this->canSeeCallback = $callback;

        return $this;
    }

    /**
     * Is See.
     *
     * @param mixed $data
     * @return boolean
     */
    public function isSee(mixed $data): bool
    {
        return $this->canSeeCallback instanceof Closure
            ? value($this->canSeeCallback, $data, $this)
            : true;
    }
}
