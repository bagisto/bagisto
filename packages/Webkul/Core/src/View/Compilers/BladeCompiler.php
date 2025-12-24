<?php

namespace Webkul\Core\View\Compilers;

use Illuminate\View\Compilers\BladeCompiler as BaseBladeCompiler;

class BladeCompiler extends BaseBladeCompiler
{
    /**
     * Append the file path to the compiled string.
     *
     * @param  string  $contents
     * @return string
     */
    protected function appendFilePath($contents)
    {
        $tokens = $this->getOpenAndClosingPhpTokens($contents);

        if (
            config('view.tracer')
            && strpos($this->getPath(), 'tracer/style.blade.php') === false
        ) {
            $finalPath = str_replace('/Providers/..', '', str_replace(base_path(), '', $this->getPath()));

            $escapedPath = htmlspecialchars($finalPath, ENT_QUOTES, 'UTF-8');

            $contents = preg_replace_callback(
                '/^(\s*)<([a-zA-Z][a-zA-Z0-9-]*)([\s>])/m',
                function ($matches) use ($escapedPath) {
                    return $matches[1].'<'.$matches[2].' data-blade-path="'.$escapedPath.'"'.$matches[3];
                },
                $contents,
                1
            );

            if (strpos($contents, 'data-blade-path=') === false) {
                $contents = '<span data-blade-path="'.$escapedPath.'">'.$contents.'</span>';
            }
        }

        if (
            $tokens->isNotEmpty()
            && $tokens->last() !== T_CLOSE_TAG
        ) {
            $contents .= ' ?>';
        }

        return $contents."<?php /**PATH {$this->getPath()} ENDPATH**/ ?>";
    }
}
