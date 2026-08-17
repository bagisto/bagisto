<?php

namespace Webkul\Core\Helpers;

class InstalledPackages
{
    /**
     * Where Bagisto's packages live, relative to the project root. Every directory
     * directly inside it is one package.
     *
     * @var string
     */
    public const PACKAGES_PATH = 'packages/Webkul';

    /**
     * Identifiers of every package present in this installation.
     *
     * @return list<string>
     */
    public function all(): array
    {
        $packages = [];

        foreach ($this->directories() as $directory) {
            $packages[] = $this->identify($directory);
        }

        sort($packages);

        return $packages;
    }

    /**
     * The package directory names.
     *
     * @return list<string>
     */
    protected function directories(): array
    {
        $path = $this->path();

        if (! is_dir($path)) {
            return [];
        }

        return array_values(array_filter(
            scandir($path) ?: [],
            fn ($entry) => ! str_starts_with($entry, '.') && is_dir($path.'/'.$entry)
        ));
    }

    /**
     * How a package is reported.
     *
     * Its own composer name is preferred, because that is what a module is
     * published and talked about as. Not every package carries a composer.json
     * though — several of Bagisto's own do not — so the directory name stands in
     * for those, which is at least stable and unique within the directory.
     */
    protected function identify(string $directory): string
    {
        $manifest = $this->path().'/'.$directory.'/composer.json';

        if (! is_file($manifest)) {
            return $directory;
        }

        $name = json_decode(file_get_contents($manifest), true)['name'] ?? null;

        return is_string($name) && trim($name) !== ''
            ? trim($name)
            : $directory;
    }

    /**
     * Absolute path to the packages directory.
     */
    protected function path(): string
    {
        return base_path(self::PACKAGES_PATH);
    }
}
