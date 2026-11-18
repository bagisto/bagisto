<?php

use Webkul\Admin\Http\Controllers\NotificationController;
use Webkul\Core\Console\Commands\ExchangeRateUpdate;
use Webkul\Core\Core;
use Webkul\Notification\Models\Notification;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Shop\Http\Controllers\API\AddressController;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\API\CoreController;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;

dataset('methods a v2.5.1 module may override without a return type', [
    [AddressRequest::class, 'authorize'],
    [AddressRequest::class, 'rules'],
    [AddressRequest::class, 'attributes'],
    [Core::class, 'isCountryRequired'],
    [Core::class, 'isStateRequired'],
    [Core::class, 'isPostCodeRequired'],
    [ExchangeRateUpdate::class, 'handle'],
    [CoreController::class, 'getCountries'],
    [CoreController::class, 'getStates'],
    [CartController::class, 'crossSellProducts'],
    [NotificationController::class, 'index'],
    [Notification::class, 'order'],
]);

dataset('members a v2.5.1 module calls', [
    [Notification::class, 'order'],
    [NotificationRepository::class, 'getParamsData'],
    [NotificationRepository::class, 'getAll'],
    [NotificationController::class, 'getNotifications'],
    [NotificationController::class, 'viewedNotifications'],
    [NotificationController::class, 'readAllNotifications'],
]);

dataset('constructors a v2.5.1 subclass may call with fewer arguments', [
    [AddressController::class, 1],
    [NotificationController::class, 1],
]);

// ============================================================================
// Signatures Since v2.5.1
// ============================================================================

it('should declare no return type a module override could conflict with', function (string $class, string $method) {
    expect((new ReflectionMethod($class, $method))->hasReturnType())->toBeFalse();
})->with('methods a v2.5.1 module may override without a return type');

it('should keep the members a module written for v2.5.1 calls', function (string $class, string $method) {
    expect(method_exists($class, $method))->toBeTrue();
})->with('members a v2.5.1 module calls');

it('should let a subclass pass only the arguments the constructor took before', function (string $class, int $required) {
    expect((new ReflectionMethod($class, '__construct'))->getNumberOfRequiredParameters())->toBe($required);
})->with('constructors a v2.5.1 subclass may call with fewer arguments');

it('should keep the repository constructor notifications took in v2.5.1', function () {
    expect((new ReflectionMethod(NotificationRepository::class, '__construct'))->getDeclaringClass()->getName())
        ->not->toBe(NotificationRepository::class);
});
