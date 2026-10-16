<?php

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputDefinition;
use Webkul\Installer\Console\Commands\Installer;

/**
 * The installer's own definition, read without running a step of it.
 */
function installerDefinition(): InputDefinition
{
    return Artisan::all()['bagisto:install']->getDefinition();
}

// ============================================================================
// Installing Into Production
// ============================================================================

it('should take a force option, so an unattended install into production is possible', function () {
    expect(installerDefinition()->hasOption('force'))->toBeTrue();
});

it('should ask before installing, which is what the force option answers', function () {
    expect(class_uses_recursive(Installer::class))->toContain(ConfirmableTrait::class);
});

it('should report a refused install to the shell rather than exiting as though it had run', function () {
    $method = new ReflectionMethod(Installer::class, 'handle');

    expect((string) $method->getReturnType())->toBe('int');
});

// ============================================================================
// Carrying The Confirmation Down
// ============================================================================

it('should force every confirmable command it calls, so none of them asks a second time', function (string $command) {
    $handle = file_get_contents((new ReflectionClass(Installer::class))->getFileName());

    expect($handle)->toContain("\$this->call('{$command}', ['--force' => true])");
})->with(['key:generate', 'db:wipe', 'migrate:fresh']);
