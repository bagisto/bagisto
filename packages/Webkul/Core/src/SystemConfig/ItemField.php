<?php

namespace Webkul\Core\SystemConfig;

use Illuminate\Support\Str;

class ItemField
{
    /**
     * Laravel to Vee Validation mappings.
     *
     * @var array
     */
    protected $veeValidateMappings = [
        'max' => 'max_value',
        'min' => 'min_value',
    ];

    /**
     * Create a new ItemField instance.
     */
    public function __construct(
        public string $item_key,
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
        public bool $is_visible = true,
    ) {
        $this->options = $this->getOptions();
    }

    /**
     * Get name of config item.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get info of config item.
     */
    public function getInfo(): ?string
    {
        return $this->info ?? '';
    }

    /**
     * Get title of config item.
     */
    public function getTitle(): ?string
    {
        return $this->title ?? '';
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
     * Get item key of config item.
     */
    public function getItemKey(): string
    {
        return $this->item_key;
    }

    /**
     * Get validation of config item.
     */
    public function getValidations(): ?string
    {
        if (empty($this->validation)) {
            return '';
        }

        foreach ($this->veeValidateMappings as $laravelRule => $veeValidateRule) {
            $this->validation = str_replace($laravelRule, $veeValidateRule, $this->validation);
        }

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
     * Get name field for forms in configuration page.
     */
    public function getNameKey(): string
    {
        return $this->item_key.'.'.$this->name;
    }

    /**
     * Check if the field is required.
     */
    public function isRequired(): string
    {
        return Str::contains($this->getValidations(), 'required') ? 'required' : '';
    }

    /**
     * Get options of config item.
     */
    public function getOptions(): array
    {
        if (is_array($this->options)) {
            return collect($this->options)->map(fn ($option) => [
                'title' => trans($option['title']),
                'value' => $option['value'],
            ])->toArray();
        }

        return collect($this->getFieldOptions($this->options))->map(fn ($option) => [
            'title' => trans($option['title']),
            'value' => $option['value'],
        ])->toArray();
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
            'validation'    => $this->getValidations(),
            'default'       => $this->getDefault(),
            'channel_based' => $this->getChannelBased(),
            'locale_based'  => $this->getLocaleBased(),
            'options'       => $this->getOptions(),
            'item_key'      => $this->getItemKey(),
        ];
    }

    /**
     * Get name field for forms in configuration page.
     *
     * @param  string  $key
     * @return string
     */
    public function getNameField($key = null)
    {
        if (! $key) {
            $key = $this->item_key.'.'.$this->name;
        }

        $nameField = '';

        foreach (explode('.', $key) as $key => $field) {
            $nameField .= $key === 0 ? $field : '['.$field.']';
        }

        return $nameField;
    }

    /**
     * Get depend the field name.
     */
    public function getDependFieldName(): string
    {
        if (empty($depends = $this->getDepends())) {
            return '';
        }

        $dependNameKey = $this->getItemKey().'.'.collect(explode(':', $depends))->first();

        return $this->getNameField($dependNameKey);
    }

    /**
     * Returns the select options for the field.
     */
    protected function getFieldOptions(string $options): array
    {
        [$class, $method] = Str::parseCallback($options);

        return app($class)->$method();
    }
}
