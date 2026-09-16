<?php

namespace Webkul\Core\Tests\Concerns;

use Illuminate\Http\UploadedFile;

trait CreatesUploads
{
    /**
     * A real upload holding the given contents, whose type is detected from those contents rather than from its
     * name, backed by a temporary file removed when the test ends.
     */
    public function uploadedFileWithContents(string $name, string $contents): UploadedFile
    {
        $handle = tmpfile();

        fwrite($handle, $contents);

        $this->beforeApplicationDestroyed(fn () => fclose($handle));

        return new UploadedFile(stream_get_meta_data($handle)['uri'], $name, null, null, true);
    }
}
