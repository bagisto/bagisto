<?php

namespace Webkul\Core\ImageCache;

use Config;
use Intervention\Image\ImageCacheController;

class Controller extends ImageCacheController
{
    /**
     * Get HTTP response of either original image file or
     * template applied file.
     *
     * @param  string $template
     * @param  string $filename
     * @return Illuminate\Http\Response
     */
    public function getResponse($template, $filename)
    {
        switch (strtolower($template)) {
            case 'original':
                return $this->getOriginal($filename);

            case 'download':
                return $this->getDownload($filename);

            default:
                return $this->getImage($template, $filename);
        }
    }

    /**
     * Get HTTP response of template applied image file
     *
     * @param  string $template
     * @param  string $filename
     * @return Illuminate\Http\Response
     */
    public function getImage($template, $filename)
    {
        $cacheTime = config('imagecache.lifetime');

        if ($template == 'logo') {
            $cacheTime = 10080;

            $path = 'https://updates.bagisto.com/bagisto.png';
        } else {
            $template = $this->getTemplate($template);
            $path = $this->getImagePath($filename);
        }

        // image manipulation based on callback
        $manager = new ImageManager(Config::get('image'));

        try {
            $content = $manager->cache(function ($image) use ($template, $path) {

                if ($template instanceof Closure) {
                    // build from closure callback template
                    $template($image->make($path));
                } elseif (is_object($template)) {
                    // build from filter template
                    $image->make($path)->filter($template);
                } else {
                    $image->make($path);
                }
            }, $cacheTime);
        } catch (\Exception $e) {
            if ($template == 'logo') {
                $content = '';
            } else {
                abort(404);
            }
        }

        return $this->buildResponse($content);
    }
}
