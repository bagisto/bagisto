<?php

namespace Webkul\RMA\Helpers;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Core\Helpers\StoredFile;

class Attachment
{
    /**
     * Create a new helper instance.
     */
    public function __construct(protected StoredFile $storedFile) {}

    /**
     * Send the file a message carries.
     */
    public function download(object $message): StreamedResponse
    {
        if (empty($message->attachment_path)) {
            abort(404);
        }

        return $this->storedFile->download($message->attachment_path, $message->attachment);
    }

    /**
     * Send a photo a return carries, for display in the page.
     */
    public function inline(string $path): StreamedResponse
    {
        return $this->storedFile->inline($path);
    }
}
