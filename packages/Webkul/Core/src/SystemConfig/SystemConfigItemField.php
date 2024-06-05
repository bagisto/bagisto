<?php

namespace Webkul\Core\SystemConfig;

class SystemConfigItemField
{
    /**
     * Create a new SystemConfigItemField instance.
     */
    public function __construct(
        public string $name,
        public string $title,
        public ?string $info,
        public string $type,
        public ?string $path,
        public ?string $validation,
        public ?string $depends,
        public ?string $default,
        public ?bool $channel_based,
        public ?bool $locale_based,
        public array|string $options,
    ) {
    }

    /**
     * Get name of config item.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get info of config item.
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * Get title of config item.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get type of config item.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get path of config item.
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Get validation of config item.
     */
    public function getValidation(): ?string
    {
        return $this->validation;
    }

    /**
     * Get depends of config item.
     */
    public function getDepends(): ?string
    {
        return $this->depends;
    }

    /**
     * Get default value of config item.
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }

    /**
     * Get channel based of config item.
     */
    public function getChannelBased(): ?bool
    {
        return $this->channel_based;
    }

    /**
     * Get locale based of config item.
     */
    public function getLocaleBased(): ?bool
    {
        return $this->locale_based;
    }

    /**
     * Get options of config item.
     */
    public function getOptions(): array|string
    {
        return $this->options;
    }

    /**
     * Convert the field to an array.
     */
    public function toArray()
    {
        return [
            'name'          => $this->getName(),
            'title'         => $this->getTitle(),
            'info'          => $this->getInfo(),
            'type'          => $this->getType(),
            'path'          => $this->getPath(),
            'depends'       => $this->getDepends(),
            'validation'    => $this->getValidation(),
            'default'       => $this->getDefault(),
            'channel_based' => $this->getChannelBased(),
            'locale_based'  => $this->getLocaleBased(),
            'options'       => $this->getOptions(),
        ];
    }
}
