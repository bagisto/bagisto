<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Core\Enums\SupportedFilesystemEnum;

class RemoteStorageCheck extends AbstractCheck
{
    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'remote-storage';
    }

    /**
     * Whether a remote disk is chosen for uploads but is not the one in effect.
     */
    public function failing(): bool
    {
        return $this->applies()
            && config('filesystems.default') !== $this->chosen()->value;
    }

    /**
     * Whether uploads are set to go to a remote disk.
     */
    public function applies(): bool
    {
        return ! is_null($this->chosen()->adapter());
    }

    /**
     * The storage chosen for uploads on the configuration screen.
     */
    protected function chosen(): SupportedFilesystemEnum
    {
        return SupportedFilesystemEnum::fromConfig(
            core()->getConfigData(SupportedFilesystemEnum::CONFIG_KEY)
        );
    }
}
