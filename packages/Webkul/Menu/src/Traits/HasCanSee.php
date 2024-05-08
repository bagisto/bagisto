<?php

namespace Webkul\Menu\Traits;

use Closure;

trait HasCanSee
{
    /**
     * Can see callback.
     */
    protected ?Closure $canSeeCallback = null;

    /**
     * Can See.
     */
    public function canSee(Closure $callback): static
    {
        $this->canSeeCallback = $callback;

        return $this;
    }

    /**
     * Is See.
     */
    public function isSee(mixed $data): bool
    {
        return $this->canSeeCallback instanceof Closure
            ? value($this->canSeeCallback, $data, $this)
            : true;
    }
}
