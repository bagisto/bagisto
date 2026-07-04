<?php

use Webkul\Core\CoreConvention;

it('should return correct migrations folder', function () {
    $convention = new CoreConvention();

    expect($convention->migrationsFolder())->toBe('Database/Migrations');
});

it('should return correct manifest file', function () {
    $convention = new CoreConvention();

    expect($convention->manifestFile())->toBe('Resources/manifest.php');
});
