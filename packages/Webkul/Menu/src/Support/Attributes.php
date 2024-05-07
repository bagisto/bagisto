<?php

namespace Webkul\Menu\Support;

use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionException;

/**
 * @template AttributeClass
 */
final class Attributes
{
    protected ?string $currentMethod = null;

    protected ?string $currentProperty = null;

    protected ?string $currentAttributeProperty = null;

    public function __construct(
        protected object $class,
        protected ?string $currentAttribute = null
    ) {
    }

    public static function for(object $class, ?string $currentAttribute = null): self
    {
        return new Attributes($class, $currentAttribute);
    }

    public function method(string $method): self
    {
        $this->currentMethod = $method;

        return $this;
    }

    public function property(string $property): self
    {
        $this->currentProperty = $property;

        return $this;
    }

    /**
     * @template T
     * @param  class-string<T>  $attribute
     * @return self<T>
     */
    public function attribute(string $attribute): self
    {
        return new Attributes($this->class, $attribute);
    }

    public function attributeProperty(string $property): self
    {
        $this->currentAttributeProperty = $property;

        return $this;
    }

    /**
     * @return AttributeClass
     * @throws ReflectionException
     */
    public function get(): mixed
    {
        $reflection = new ReflectionClass($this->class);

        if (! is_null($this->currentMethod)) {
            $reflection = $reflection->getMethod($this->currentMethod);
        }

        if (! is_null($this->currentProperty)) {
            $reflection = $reflection->getProperty($this->currentProperty);
        }

        if (! is_null($this->currentAttribute)) {
            /* @var \ReflectionAttribute $attributes */
            $attributes = Arr::first($reflection->getAttributes($this->currentAttribute));
        } else {
            $attributes = $reflection->getAttributes();
        }

        if (! is_null($this->currentAttributeProperty)) {
            return $attributes?->newInstance()?->{$this->currentAttributeProperty};
        }

        return $attributes;
    }
}
