<?php

namespace Webkul\ImageCache;

use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * Wrapper class for cached images.
 * Provides proxy access to Intervention Image v3 methods.
 */
class CachedImage
{
    /**
     * The underlying Intervention Image instance.
     */
    protected ImageInterface $image;

    /**
     * The cache key or checksum for this image.
     */
    protected string $cacheKey;

    /**
     * Create a new CachedImage instance.
     */
    public function __construct(ImageInterface $image, string $cacheKey = '')
    {
        $this->image = $image;
        $this->cacheKey = $cacheKey;
    }

    /**
     * Get the underlying image instance.
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }

    /**
     * Get the cache key for this image.
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * Get the image width.
     */
    public function width(): int
    {
        return $this->image->width();
    }

    /**
     * Get the image height.
     */
    public function height(): int
    {
        return $this->image->height();
    }

    /**
     * Resize the image to the given dimensions.
     */
    public function resize(?int $width = null, ?int $height = null): self
    {
        $this->image = $this->image->resize($width, $height);

        return $this;
    }

    /**
     * Scale the image to the given dimensions.
     */
    public function scale(?int $width = null, ?int $height = null): self
    {
        $this->image = $this->image->scale($width, $height);

        return $this;
    }

    /**
     * Cover resize the image while maintaining aspect ratio.
     */
    public function cover(int $width, int $height, string $position = 'center'): self
    {
        $this->image = $this->image->cover($width, $height, $position);

        return $this;
    }

    /**
     * Contain resize the image within dimensions while maintaining aspect ratio.
     */
    public function contain(int $width, int $height, mixed $background = 'ffffff', string $position = 'center'): self
    {
        $this->image = $this->image->contain($width, $height, $background, $position);

        return $this;
    }

    /**
     * Crop the image to the given dimensions.
     */
    public function crop(int $width, int $height, int $x = 0, int $y = 0, mixed $background = 'ffffff', string $position = 'top-left'): self
    {
        $this->image = $this->image->crop($width, $height, $x, $y, $background, $position);

        return $this;
    }

    /**
     * Pad the image to the given dimensions.
     */
    public function pad(int $width, int $height, mixed $background = 'ffffff', string $position = 'center'): self
    {
        $this->image = $this->image->pad($width, $height, $background, $position);

        return $this;
    }

    /**
     * Rotate the image by the given angle.
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): self
    {
        $this->image = $this->image->rotate($angle, $background);

        return $this;
    }

    /**
     * Flip the image horizontally.
     */
    public function flip(): self
    {
        $this->image = $this->image->flip();

        return $this;
    }

    /**
     * Flip the image vertically.
     */
    public function flop(): self
    {
        $this->image = $this->image->flop();

        return $this;
    }

    /**
     * Adjust the image brightness.
     */
    public function brightness(int $level): self
    {
        $this->image = $this->image->brightness($level);

        return $this;
    }

    /**
     * Adjust the image contrast.
     */
    public function contrast(int $level): self
    {
        $this->image = $this->image->contrast($level);

        return $this;
    }

    /**
     * Apply gamma correction to the image.
     */
    public function gamma(float $gamma): self
    {
        $this->image = $this->image->gamma($gamma);

        return $this;
    }

    /**
     * Colorize the image with the given RGB values.
     */
    public function colorize(int $red, int $green, int $blue): self
    {
        $this->image = $this->image->colorize($red, $green, $blue);

        return $this;
    }

    /**
     * Convert the image to greyscale.
     */
    public function greyscale(): self
    {
        $this->image = $this->image->greyscale();

        return $this;
    }

    /**
     * Apply the sharpen effect to the image.
     */
    public function sharpen(int $amount = 10): self
    {
        $this->image = $this->image->sharpen($amount);

        return $this;
    }

    /**
     * Apply the blur effect to the image.
     */
    public function blur(int $amount = 1): self
    {
        $this->image = $this->image->blur($amount);

        return $this;
    }

    /**
     * Pixelate the image.
     */
    public function pixelate(int $size): self
    {
        $this->image = $this->image->pixelate($size);

        return $this;
    }

    /**
     * Invert the image colors.
     */
    public function invert(): self
    {
        $this->image = $this->image->invert();

        return $this;
    }

    /**
     * Encode the image by media type.
     */
    public function encodeByMediaType(?string $mediaType = null, int $quality = 75): EncodedImage
    {
        return $this->image->encodeByMediaType($mediaType, quality: $quality);
    }

    /**
     * Encode the image by file extension.
     */
    public function encodeByExtension(?string $extension = null, int $quality = 75): EncodedImage
    {
        return $this->image->encodeByExtension($extension, quality: $quality);
    }

    /**
     * Encode the image to JPEG format.
     */
    public function toJpeg(int $quality = 75): EncodedImage
    {
        return $this->image->toJpeg(quality: $quality);
    }

    /**
     * Encode the image to PNG format.
     */
    public function toPng(): EncodedImage
    {
        return $this->image->toPng();
    }

    /**
     * Encode the image to WebP format.
     */
    public function toWebp(int $quality = 75): EncodedImage
    {
        return $this->image->toWebp(quality: $quality);
    }

    /**
     * Encode the image to GIF format.
     */
    public function toGif(): EncodedImage
    {
        return $this->image->toGif();
    }

    /**
     * Save the image to the given path.
     */
    public function save(string $path, ?int $quality = null): EncodedImage
    {
        return $this->image->save($path, quality: $quality);
    }

    /**
     * Magic method to proxy calls to the underlying image.
     */
    public function __call(string $name, array $arguments): mixed
    {
        $result = call_user_func_array([$this->image, $name], $arguments);

        if ($result instanceof ImageInterface) {
            $this->image = $result;

            return $this;
        }

        return $result;
    }

    /**
     * Convert the image to an encoded string.
     */
    public function __toString(): string
    {
        return (string) $this->image->encodeByMediaType();
    }

    /**
     * Encode the image for backward compatibility.
     */
    public function encode(?string $format = null, int $quality = 90): EncodedImage
    {
        if ($format === null) {
            return $this->encodeByMediaType(null, $quality);
        }

        return $this->encodeByExtension($format, $quality);
    }

    /**
     * Get the encoded response for displaying the image in a browser.
     */
    public function response(?string $format = null, int $quality = 90): string
    {
        return (string) $this->encode($format, $quality);
    }

    /**
     * Get the Base64 encoded image string.
     */
    public function toBase64(?string $format = null, int $quality = 90): string
    {
        $encoded = $this->encode($format, $quality);

        return base64_encode((string) $encoded);
    }

    /**
     * Get the data URL for the image.
     */
    public function toDataUrl(?string $format = null, int $quality = 90): string
    {
        $encoded = $this->encode($format, $quality);
        $mediaType = $encoded->mediaType();
        $base64 = base64_encode((string) $encoded);

        return "data:{$mediaType};base64,{$base64}";
    }
}
