<?php

use Webkul\FPC\Concerns\ForgetsPages;

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->forgetter = new class
    {
        use ForgetsPages;

        /**
         * Expose the trait's scope list for a channel to the test.
         */
        public function scopes($channel): array
        {
            return $this->channelScopes($channel);
        }

        /**
         * Expose the trait's home path to the test.
         */
        public function home(): string
        {
            return $this->homePath();
        }

        /**
         * Expose the trait's forget to the test.
         */
        public function forget(array $paths): void
        {
            $this->forgetPages($paths);
        }
    };
});

it('names the home page as the path every listing change has to drop', function () {
    // Act & Assert
    expect($this->forgetter->home())->toBe('/');
});

it('builds a cache scope for every locale and currency combination of a channel', function () {
    // Arrange
    $secondScope = $this->addSecondScope();

    // Act
    $scopes = $this->forgetter->scopes(core()->getCurrentChannel()->fresh());

    // Assert
    expect($scopes)->toContain($this->currentScope());

    expect($scopes)->toContain($secondScope);
});

it('forgets a path in every scope, not only the one the admin is browsing', function () {
    // Arrange
    $secondScope = $this->addSecondScope();

    $browsed = $this->cachePage('/summer-sale');

    $other = $this->cachePage('/summer-sale', $secondScope);

    // Act
    $this->forgetter->forget(['/summer-sale']);

    // Assert
    $this->assertPageNotCached($browsed);

    $this->assertPageNotCached($other, 'A page cached under a second locale or currency survived.');
});

it('forgets a path on the host of a channel served on its own domain', function () {
    // Arrange
    $otherHostScope = $this->addChannelOnHost('shop-two.test');

    $onOtherHost = $this->cachePage('/summer-sale', $otherHostScope, 'shop-two.test');

    $onThisHost = $this->cachePage('/summer-sale', $otherHostScope);

    // Act
    $this->forgetter->forget(['/summer-sale']);

    // Assert
    $this->assertPageNotCached($onOtherHost, 'A channel on its own domain kept the page, since the host is part of the key.');

    $this->assertPageNotCached($onThisHost);
});

it('leaves pages under other paths alone', function () {
    // Arrange
    $target = $this->cachePage('/summer-sale');

    $bystander = $this->cachePage('/winter-sale');

    // Act
    $this->forgetter->forget(['/summer-sale']);

    // Assert
    $this->assertPageNotCached($target);

    $this->assertPageCached($bystander);
});

it('drops empty entries and still forgets the real ones', function () {
    // Arrange
    $target = $this->cachePage('/summer-sale');

    // Act
    $this->forgetter->forget(['/summer-sale', null, '', '/summer-sale']);

    // Assert
    $this->assertPageNotCached($target);
});

it('touches nothing when there is no path to forget', function () {
    // Arrange
    $bystander = $this->cachePage('/summer-sale');

    // Act
    $this->forgetter->forget([]);

    $this->forgetter->forget([null, '']);

    // Assert
    $this->assertPageCached($bystander);
});
