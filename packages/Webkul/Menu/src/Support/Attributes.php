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
    /**
     * Current Method.
     */
    protected ?string $currentMethod = null;

    /**
     * Current Property.
     */
    protected ?string $currentProperty = null;

    /**
     * Current Attribute Property.
     */
    protected ?string $currentAttributeProperty = null;

    /**
     * Create the new instance of the class.
     *
     * @return void
     */
    public function __construct(
        protected object $class,
        protected ?string $currentAttribute = null
    ) {
    }

    /**
     * Attributes used for.
     */
    public static function for(object $class, ?string $currentAttribute = null): self
    {
        return new Attributes($class, $currentAttribute);
    }

    /**
     * Method of attributes.
     */
    public function method(string $method): self
    {
        $this->currentMethod = $method;

        return $this;
    }

    /**
     * Property of the attribute.
     */
    public function property(string $property): self
    {
        $this->currentProperty = $property;

        return $this;
    }

    /**
     * Get Attributes.
     */
    public function attribute(string $attribute): self
    {
        return new Attributes($this->class, $attribute);
    }

    /**
     * Attributes Property.
     */
    public function attributeProperty(string $property): self
    {
        $this->currentAttributeProperty = $property;

        return $this;
    }

    /**
     * Get the attribute.
     *
     * @return AttributeClass
     *
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
