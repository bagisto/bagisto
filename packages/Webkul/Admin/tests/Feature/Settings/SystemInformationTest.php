<?php

use Webkul\Admin\Helpers\SystemInformation;
use Webkul\Core\Enums\SupportedFilesystemEnum;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Repositories\CoreConfigRepository;

/**
 * Choose the store's disk in the configuration, and set the disk actually in effect.
 */
function chooseStoreDisk(SupportedFilesystemEnum $chosen, ?SupportedFilesystemEnum $inEffect = null): void
{
    test()->setConfig(SupportedFilesystemEnum::CONFIG_KEY, $chosen->value);

    CacheGeneration::bump(CoreConfigRepository::class);

    config(['filesystems.default' => ($inEffect ?? $chosen)->value]);
}

/**
 * The storage card of the system information page.
 */
function storageInformation(): array
{
    foreach (app(SystemInformation::class)->columns() as $column) {
        foreach ($column as $card) {
            if ($card['heading'] === trans(SystemInformation::LANG.'sections.storage')) {
                return $card;
            }
        }
    }

    return [];
}

/**
 * The wording a system information row is read under.
 */
function aboutLabel(string $key): string
{
    return trans(SystemInformation::LANG.$key);
}

// ============================================================================
// Storage
// ============================================================================

it('should report whether the public storage link is in place', function () {
    chooseStoreDisk(SupportedFilesystemEnum::LOCAL);

    $entries = storageInformation()['entries'];

    expect($entries)->toHaveKey(aboutLabel('labels.link'))
        ->and($entries[aboutLabel('labels.link')])->toBe(file_exists(public_path('storage')));
});

it('should report local storage by name without a status', function () {
    chooseStoreDisk(SupportedFilesystemEnum::LOCAL);

    $card = storageInformation();

    expect($card['entries'][aboutLabel('labels.driver')])->toBe(trans(SupportedFilesystemEnum::LOCAL->title()))
        ->and($card['entries'])->not->toHaveKey(aboutLabel('labels.status'))
        ->and($card['health'])->toBe([]);
});

it('should report a remote disk as available while it is the disk in effect', function () {
    chooseStoreDisk(SupportedFilesystemEnum::S3);

    $card = storageInformation();

    expect($card['entries'][aboutLabel('labels.driver')])->toBe(trans(SupportedFilesystemEnum::S3->title()))
        ->and($card['entries'][aboutLabel('labels.status')])->toBe(aboutLabel('statuses.available'))
        ->and($card['health'][aboutLabel('labels.status')])->toBe('good');
});

it('should report a remote disk as not available while another disk is in effect', function () {
    chooseStoreDisk(SupportedFilesystemEnum::R2, SupportedFilesystemEnum::LOCAL);

    $card = storageInformation();

    expect($card['entries'][aboutLabel('labels.driver')])->toBe(trans(SupportedFilesystemEnum::R2->title()))
        ->and($card['entries'][aboutLabel('labels.status')])->toBe(aboutLabel('values.not-available'))
        ->and($card['health'][aboutLabel('labels.status')])->toBe('bad');
});
