<?php

namespace Webkul\ImageCache;

use Carbon\Carbon;
use Closure;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageCache
{
    /**
     * Cache lifetime in minutes.
     */
    public int $lifetime = 5;

    /**
     * History of name and arguments of calls performed on image.
     */
    public array $calls = [];

    /**
     * Additional properties included in checksum.
     */
    public array $properties = [];

    /**
     * Processed image instance.
     */
    public ?ImageInterface $image = null;

    /**
     * Intervention image manager instance.
     */
    public ImageManager $manager;

    /**
     * Illuminate cache repository instance.
     */
    public Repository $cache;

    /**
     * Driver configuration.
     */
    protected array $config = [];

    /**
     * Create a new ImageCache instance.
     */
    public function __construct(?ImageManager $manager = null, ?Cache $cache = null)
    {
        $this->manager = $manager ?: $this->createManager();

        if (is_null($cache)) {
            $app = function_exists('app') ? app() : null;

            if (is_a($app, 'Illuminate\Foundation\Application')) {
                $cache = $app->make('cache');
            }

            if (is_a($cache, 'Illuminate\Cache\CacheManager')) {
                $cacheDriver = config('imagecache.cache_driver');
                $this->cache = $cacheDriver ? $cache->driver($cacheDriver) : $cache;
            } else {
                $path = $this->config['cache']['path'] ?? __DIR__.'/../../storage/cache';

                $filesystem = new Filesystem;
                $storage = new FileStore($filesystem, $path);
                $this->cache = new Repository($storage);
            }
        } else {
            $this->cache = $cache;
        }
    }

    /**
     * Create an ImageManager instance based on configuration.
     */
    protected function createManager(): ImageManager
    {
        return image_manager();
    }

    /**
     * Magic method to capture action calls.
     */
    public function __call(string $name, array $arguments): self
    {
        $this->registerCall($name, $arguments);

        return $this;
    }

    /**
     * Create an image from the given data and add modified timestamp to checksum.
     */
    public function make(mixed $data): self
    {
        if ($this->isFile($data)) {
            $this->setProperty('modified', filemtime((string) $data));
        }

        $this->registerCall('read', [$data]);

        return $this;
    }

    /**
     * Alias for make method. Read is the Intervention Image v3 method name.
     */
    public function read(mixed $data): self
    {
        return $this->make($data);
    }

    /**
     * Check if the given data is a file path.
     */
    protected function isFile(mixed $value): bool
    {
        $value = strval(str_replace("\0", '', $value));

        return strlen($value) <= PHP_MAXPATHLEN && is_file($value);
    }

    /**
     * Set a custom property to be included in the checksum.
     */
    public function setProperty(mixed $key, mixed $value): self
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * Return the checksum of the current image state.
     */
    public function checksum(): string
    {
        $properties = serialize($this->properties);
        $calls = serialize($this->getSanitizedCalls());

        return md5($properties.$calls);
    }

    /**
     * Register a call for later execution.
     */
    protected function registerCall(string $name, array $arguments): void
    {
        $this->calls[] = [
            'name'      => $name,
            'arguments' => $arguments,
        ];
    }

    /**
     * Clear the history of calls.
     */
    protected function clearCalls(): void
    {
        $this->calls = [];
    }

    /**
     * Clear all currently set properties.
     */
    protected function clearProperties(): void
    {
        $this->properties = [];
    }

    /**
     * Return the unprocessed calls.
     */
    protected function getCalls(): array
    {
        return count($this->calls) ? $this->calls : [];
    }

    /**
     * Replace closures in arguments with their hashes.
     */
    protected function getSanitizedCalls(): array
    {
        $calls = $this->getCalls();

        foreach ($calls as $i => $call) {
            foreach ($call['arguments'] as $j => $argument) {
                if (is_a($argument, Closure::class)) {
                    $calls[$i]['arguments'][$j] = $this->getClosureHash($argument);
                }
            }
        }

        return $calls;
    }

    /**
     * Build a hash from the given closure.
     */
    protected function getClosureHash(Closure $closure): string
    {
        return (new HashableClosure($closure))->getHash();
    }

    /**
     * Process a single call on the current image.
     */
    protected function processCall(array $call): void
    {
        $name = $call['name'];
        $arguments = $call['arguments'];

        if ($name === 'read' && ! ($this->image instanceof ImageInterface)) {
            $this->image = call_user_func_array([$this->manager, 'read'], $arguments);

            return;
        }

        if ($this->image instanceof ImageInterface) {
            $result = call_user_func_array([$this->image, $name], $arguments);

            if ($result instanceof ImageInterface) {
                $this->image = $result;
            }
        }
    }

    /**
     * Process all saved image calls on the image object.
     */
    public function process(): ImageInterface|CachedImage|null
    {
        $this->image = null;

        foreach ($this->getCalls() as $call) {
            $this->processCall($call);
        }

        $checksum = $this->checksum();

        $this->clearCalls();
        $this->clearProperties();

        if ($this->image instanceof ImageInterface) {
            return new CachedImage($this->image, $checksum);
        }

        return $this->image;
    }

    /**
     * Get the image from cache or process and cache it.
     */
    public function get(?int $lifetime = null, bool $returnObj = false): mixed
    {
        $lifetime = is_null($lifetime) ? $this->lifetime : $lifetime;

        $key = $this->checksum();

        $cachedImageData = $this->cache->get($key);

        if ($cachedImageData) {
            if ($returnObj) {
                $image = $this->manager->read($cachedImageData);

                return new CachedImage($image, $key);
            }

            return $cachedImageData;
        }

        $image = $this->process();

        $encoded = $this->encodeImage($image);

        $this->cache->put($key, $encoded, Carbon::now()->addMinutes($lifetime));

        return $returnObj ? $image : $encoded;
    }

    /**
     * Encode the image to a string.
     */
    protected function encodeImage(mixed $image): string
    {
        if ($image instanceof CachedImage) {
            $image = $image->getImage();
        }

        if ($image instanceof ImageInterface) {
            try {
                return (string) $image->encodeByMediaType();
            } catch (EncoderException) {
                return (string) $image->toPng();
            }
        }

        return (string) $image;
    }
}
