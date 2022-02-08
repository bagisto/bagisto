<?php

namespace Helper;

use Actions\ProductAction;
use Actions\ProductActionContract;
use Codeception\Module\Laravel;

class Bagisto extends Laravel implements ProductActionContract
{
    use ProductAction;

    /**
     * Set all session with the given key and value in the array.
     *
     * @param  array  $keyValue
     * @return void
     */
    public function setSession(array $keyValue): void
    {
        session($keyValue);
    }

    /**
     * Flush the session data and regenerate the ID
     * A logged in user will be logged off.
     *
     * @return void
     */
    public function invalidateSession(): void
    {
        session()->invalidate();
    }
}
