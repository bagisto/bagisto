<?php

namespace Webkul\Core;

use Konekt\Concord\Conventions\ConcordDefault;

class CoreConvention extends ConcordDefault
{
    /**
     * Migration folder.
     *
     * @return string
     */
    public function migrationsFolder(): string
    {
        return 'Database/Migrations';
    }

    /**
     * Manifest file.
     *
     * @return string
     */
    public function manifestFile(): string
    {
        return 'Resources/manifest.php';
    }
}
