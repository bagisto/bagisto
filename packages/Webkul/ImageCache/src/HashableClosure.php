<?php

namespace Webkul\ImageCache;

use Closure;
use Exception;
use ReflectionFunction;

/**
 * Creates a hash from a closure for caching purposes.
 *
 * This allows closures to be used in cache checksums.
 */
class HashableClosure
{
    /**
     * The closure to hash.
     */
    protected Closure $closure;

    /**
     * Create a new HashableClosure instance.
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * Get a unique hash for the closure.
     */
    public function getHash(): string
    {
        $reflection = new ReflectionFunction($this->closure);

        $filename = $reflection->getFileName();

        $startLine = $reflection->getStartLine();

        $endLine = $reflection->getEndLine();

        if ($filename && file_exists($filename)) {
            $lines = file($filename);

            $code = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
        } else {
            $code = '';
        }

        $staticVars = $reflection->getStaticVariables();

        $staticHash = $this->hashStaticVariables($staticVars);

        $hashData = [
            'file'   => $filename,
            'start'  => $startLine,
            'end'    => $endLine,
            'code'   => $code,
            'static' => $staticHash,
        ];

        return md5(serialize($hashData));
    }

    /**
     * Hash the static variables of a closure.
     */
    protected function hashStaticVariables(array $vars): string
    {
        $processed = [];

        foreach ($vars as $name => $value) {
            if ($value instanceof Closure) {
                $processed[$name] = (new self($value))->getHash();
            } elseif (is_object($value)) {
                $processed[$name] = get_class($value).':'.spl_object_id($value);
            } elseif (is_resource($value)) {
                $processed[$name] = 'resource:'.get_resource_type($value);
            } else {
                try {
                    $processed[$name] = serialize($value);
                } catch (Exception) {
                    $processed[$name] = gettype($value);
                }
            }
        }

        return md5(serialize($processed));
    }

    /**
     * Get the underlying closure.
     */
    public function getClosure(): Closure
    {
        return $this->closure;
    }

    /**
     * Convert to string and return the hash.
     */
    public function __toString(): string
    {
        return $this->getHash();
    }
}
