<?php

namespace Webkul\ImageCache\Exceptions;

use League\Flysystem\FilesystemOperationFailed;

class UnreadableImage extends \Exception
{
    /**
     * Create an instance for an image a disk failed to read, or failed to say whether it holds.
     */
    public function __construct(
        protected string $disk,
        protected string $path,
        protected FilesystemOperationFailed $failure
    ) {
        $cause = $failure->getPrevious() ?? $failure;

        $reason = rtrim($cause->getMessage(), '.!? ') ?: class_basename($cause);

        parent::__construct("Image [{$path}] could not be read from disk [{$disk}]: {$reason}.", previous: $failure);
    }

    /**
     * What reports of this failure are throttled by: the disk and the operation that failed, never the path,
     * which a request can spell any number of ways.
     */
    public function throttleKey(): string
    {
        return implode('|', [
            static::class,
            $this->disk,
            $this->failure->operation(),
        ]);
    }

    /**
     * The details recorded beside the message, so a report says which image, disk and operation failed.
     */
    public function context(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'operation' => $this->failure->operation(),
        ];
    }
}
