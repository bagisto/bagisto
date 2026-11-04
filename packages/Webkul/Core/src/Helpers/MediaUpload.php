<?php

namespace Webkul\Core\Helpers;

use Illuminate\Http\UploadedFile;

class MediaUpload
{
    /**
     * The media type of an uploaded image.
     */
    public const IMAGE = 'image';

    /**
     * The media type of an uploaded video.
     */
    public const VIDEO = 'video';

    /**
     * The media type of an uploaded file that is neither an image nor a video, such as a file attribute value.
     */
    public const FILE = 'file';

    /**
     * Extensions an admin may allow for each media type, all of them ones a stored file may carry.
     */
    public const SUPPORTED_EXTENSIONS = [
        self::IMAGE => ['avif', 'bmp', 'gif', 'jpeg', 'jpg', 'png', 'webp'],
        self::VIDEO => ['mov', 'mp4', 'webm'],
    ];

    /**
     * Extensions allowed for each media type until an admin chooses otherwise.
     */
    public const DEFAULT_EXTENSIONS = [
        self::IMAGE => ['bmp', 'jpeg', 'jpg', 'png', 'webp'],
        self::VIDEO => ['mov', 'mp4', 'webm'],
    ];

    /**
     * Largest upload for each media type in kilobytes until an admin chooses otherwise.
     */
    public const DEFAULT_MAX_SIZES = [
        self::IMAGE => 10240,
        self::VIDEO => 51200,
        self::FILE => 10240,
    ];

    /**
     * Get the extensions allowed for a media type, as configured and limited to the supported ones.
     */
    public function extensions(string $type): array
    {
        $configured = array_map(
            fn ($extension) => strtolower(trim($extension)),
            explode(',', (string) core()->getConfigData($this->configKey($type, 'allowed_extensions')))
        );

        $extensions = array_values(array_intersect(self::SUPPORTED_EXTENSIONS[$type], $configured));

        return $extensions ?: self::DEFAULT_EXTENSIONS[$type];
    }

    /**
     * Get the largest upload allowed for a media type in kilobytes, never above what the server accepts.
     */
    public function maxSize(string $type): int
    {
        $configured = (int) core()->getConfigData($this->configKey($type, 'max_size'));

        if ($configured < 1) {
            $configured = self::DEFAULT_MAX_SIZES[$type];
        }

        return min($configured, $this->serverMaxSize());
    }

    /**
     * Get the largest file the server accepts in kilobytes, from the PHP upload limits.
     */
    public function serverMaxSize(): int
    {
        return max(1, (int) floor(UploadedFile::getMaxFilesize() / 1024));
    }

    /**
     * Get the validation rules of a media upload, the configured extensions and size unless given.
     */
    public function rules(string $type, ?array $extensions = null, ?int $maxSize = null): array
    {
        $extensions = implode(',', $extensions ?? $this->extensions($type));

        return [
            'file',
            'extensions:'.$extensions,
            'mimes:'.$extensions,
            'max:'.($maxSize ?? $this->maxSize($type)),
        ];
    }

    /**
     * Get the validation rules of an uploaded image.
     */
    public function imageRules(array $extraExtensions = [], ?int $maxSize = null): array
    {
        return $this->rules(self::IMAGE, $this->withExtra(self::IMAGE, $extraExtensions), $maxSize);
    }

    /**
     * Get the validation rules of an uploaded video.
     */
    public function videoRules(?int $maxSize = null): array
    {
        return $this->rules(self::VIDEO, null, $maxSize);
    }

    /**
     * Get the validation rules of an uploaded file of any type, limited only in size.
     */
    public function fileRules(?int $maxSize = null): array
    {
        return [
            'file',
            'max:'.($maxSize ?? $this->maxSize(self::FILE)),
        ];
    }

    /**
     * Format a size in kilobytes the way a person reads it.
     */
    public function formatSize(int $kilobytes): string
    {
        return $kilobytes >= 1024
            ? round($kilobytes / 1024, 1).' MB'
            : $kilobytes.' KB';
    }

    /**
     * Get the options of the allowed extensions setting for a media type.
     */
    public static function extensionOptions(string $type): array
    {
        return array_map(fn ($extension) => [
            'title' => strtoupper($extension),
            'value' => $extension,
        ], self::SUPPORTED_EXTENSIONS[$type]);
    }

    /**
     * Get the configuration key of a media upload setting.
     */
    protected function configKey(string $type, string $field): string
    {
        return 'file_management.media.'.$type.'s.'.$field;
    }

    /**
     * Get the configured extensions of a media type with the extra ones a single field also accepts.
     */
    protected function withExtra(string $type, array $extraExtensions): array
    {
        return array_values(array_unique([...$this->extensions($type), ...$extraExtensions]));
    }
}
