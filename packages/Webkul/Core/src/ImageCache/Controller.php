<?php

namespace Webkul\Core\ImageCache;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Shop\CacheFilters\CacheFilterInterface;

class Controller extends \Illuminate\Routing\Controller
{
    /**
     * Logo
     *
     * @var string
     */
    const LOGO = 'https://www.fairykids.bg/themes/shop/default/build/assets/logo-8ef453f8.svg';

    /**
     * Get HTTP response of either original image file or
     * template applied file.
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function getResponse(string $template, string $filename)
    {
        if (! Storage::exists($filename)) {
            foreach (['jpg', 'jpeg', 'png', 'webp', 'tiff'] as $ext) {
                if (Storage::exists(Str::beforeLast($filename, '.').'.'.$ext)) {
                    $filename = Str::beforeLast($filename, '.').'.'.$ext;
                    break;
                }
            }
            if (! Storage::exists($filename)) {
                return abort(404);
            }
        }

        return match ($template) {
            'original' => $this->getOriginal($filename),
            'download' => $this->getDownload($filename),
            default    => $this->getImage($template, $filename),
        };
    }

    /**
     * Get HTTP response of template applied image file
     */
    public function getImage(string $template, string $filename): RedirectResponse
    {
        $cachePath = config('imagecache.route').'/'.$template.'/'.Str::beforeLast($filename, '.').'.webp';
        // Check if the image is already cached on storage level and just return it.
        if (! Storage::exists($cachePath)) {
            $image = $this->renderImage($template, $filename);
            Storage::put($cachePath, $image->toWebp(), 'public');
        }

        return redirect()->away(Storage::url($cachePath), 301);
    }

    private function renderImage($template, $filename): Image
    {
        /** @var CacheFilterInterface $template */
        $template = $this->getTemplate($template);

        if (! $template) {
            abort(404);
        }

        /** @var Image $image */
        $image = app('image')->read(
            $this->getImageContent($filename)
        );

        return $template->handle($image, $this->getImagePreset());
    }

    private function getImagePreset(): ?string
    {
        return match (true) {
            Str::contains(request()->path(), '/product')             => 'product',
            Str::contains(request()->path(), '/category')            => 'category',
            Str::contains(request()->path(), '/attribute_option')    => 'attribute_option',
            default                                                  => null,
        };
    }

    protected function getTemplate($template)
    {
        $template = config("imagecache.templates.{$template}");

        return match (true) {
            class_exists($template) => new $template,
            default                 => null,
        };
    }

    /**
     * Returns full image path from given filename
     *
     * @param  string  $filename
     * @return string
     */
    protected function getImageContent($filename): string
    {
        if (Storage::exists($filename)) {
            return Storage::read($filename);
        }

        // find file
        foreach (config('imagecache.paths') as $path) {
            // don't allow '..' in filenames
            $image_path = $path.'/'.str_replace('..', '', $filename);
            if (file_exists($image_path) && is_file($image_path)) {
                // file found
                return @file_get_contents($image_path);
            }
        }

        // file not found
        abort(404);
    }

    public function getOriginal($filename): RedirectResponse
    {
        abort_if(! Storage::exists($filename), 404);

        return redirect()->away(Storage::url(Storage::path($filename)));
    }

    public function getDownload($filename): StreamedResponse
    {
        abort_if(! Storage::exists($filename), 404);

        return response()->streamDownload(function () use ($filename) {
            echo Storage::get($filename);
        }, $filename);
    }
}
