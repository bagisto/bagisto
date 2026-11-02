<?php

namespace Webkul\ImageCache\Exceptions;

use Throwable;

class UnprocessableImage extends \Exception
{
    /**
     * Create an instance for a stored image of a type the image driver processes that still failed to process.
     */
    public function __construct(
        protected string $template,
        protected string $filename,
        protected ?string $themeCode,
        protected string $mime,
        Throwable $previous
    ) {
        $reason = rtrim($previous->getMessage(), '.!? ') ?: class_basename($previous);

        parent::__construct("Image [{$filename}] could not be processed through template [{$template}]: {$reason}.", previous: $previous);
    }

    /**
     * What reports of this failure are throttled by: the template, theme, type and kind of failure, never the
     * file, which a request can spell any number of ways and an upload can multiply.
     */
    public function throttleKey(): string
    {
        return implode('|', [
            static::class,
            $this->template,
            (string) $this->themeCode,
            $this->mime,
            get_class($this->getPrevious()),
        ]);
    }

    /**
     * The details recorded beside the message, so a report says which image, template and theme failed.
     */
    public function context(): array
    {
        return [
            'template' => $this->template,
            'filename' => $this->filename,
            'theme' => $this->themeCode,
            'mime' => $this->mime,
        ];
    }
}
