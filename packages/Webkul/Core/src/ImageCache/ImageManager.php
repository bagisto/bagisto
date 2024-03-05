<?php

namespace Webkul\Core\ImageCache;

use Intervention\Image\AbstractDriver;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\ImageManager as BaseImageManager;

class ImageManager extends BaseImageManager
{
    /**
     * Initiates an Image instance from different input types
     *
     * @param  mixed  $data
     * @return \Intervention\Image\Image
     */
    public function make($data)
    {
        $driver = $this->createDriver();

        if ((bool) filter_var($data, FILTER_VALIDATE_URL)) {
            return $this->initFromUrl($driver, $data);
        }

        return $driver->init($data);
    }

    /**
     * Init from given URL
     *
     * @param  mixed  $driver
     * @param  string  $url
     * @return \Intervention\Image\Image
     */
    public function initFromUrl($driver, $url)
    {
        $domain = config('app.url');

        $options = [
            'http' => [
                'method'           => 'GET',
                'protocol_version' => 1.1, // force use HTTP 1.1 for service mesh environment with envoy
                'header'           => "Accept-language: en\r\n".
                "Domain: $domain\r\n".
                "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36\r\n",
            ],
        ];

        $context = stream_context_create($options);

        if ($data = @file_get_contents($url, false, $context)) {
            return $driver->decoder->initFromBinary($data);
        }

        throw new NotReadableException(
            'Unable to init from given url ('.$url.').'
        );
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return \Intervention\Image\AbstractDriver
     */
    private function createDriver()
    {
        if (is_string($this->config['driver'])) {
            $driverName = ucfirst($this->config['driver']);
            $driverClass = sprintf('Intervention\\Image\\%s\\Driver', $driverName);

            if (class_exists($driverClass)) {
                return new $driverClass;
            }

            throw new NotSupportedException(
                "Driver ({$driverName}) could not be instantiated."
            );
        }

        if ($this->config['driver'] instanceof AbstractDriver) {
            return $this->config['driver'];
        }

        throw new NotSupportedException(
            'Unknown driver type.'
        );
    }
}
