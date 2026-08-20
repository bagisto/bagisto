<?php

namespace Webkul\Attribute\Repositories;

use Illuminate\Container\Container;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Attribute\Contracts\AttributeOption;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Helpers\MediaFileName;

class AttributeOptionRepository extends Repository
{
    /**
     * Directory the swatch images are stored in.
     */
    public const SWATCH_DIRECTORY = 'attribute_option';

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected MediaFileName $mediaFileName,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Attribute\Contracts\AttributeOption';
    }

    /**
     * @return AttributeOption
     */
    public function create(array $data)
    {
        $option = parent::create($data);

        $this->uploadSwatchImage($data, $option->id);

        $this->saveSwatchAltText($data, $option->id);

        return $option;
    }

    /**
     * @param  int  $id
     * @return AttributeOption
     */
    public function update(array $data, $id)
    {
        $option = parent::update($data, $id);

        $this->uploadSwatchImage($data, $id);

        $this->saveSwatchAltText($data, $id);

        return $option;
    }

    /**
     * @param  array  $data
     * @param  int  $optionId
     * @return void
     */
    public function uploadSwatchImage($data, $optionId)
    {
        $swatchValue = $data['swatch_value'] ?? null;

        if ($swatchValue instanceof UploadedFile) {
            $path = $this->mediaFileName->resolve(
                self::SWATCH_DIRECTORY,
                $data['swatch_file_name'] ?? null,
                $swatchValue->getClientOriginalExtension()
            );

            Storage::put($path, $swatchValue->get());

            parent::update(['swatch_value' => $path], $optionId);

            return;
        }

        $this->renameSwatchImage($data, $optionId);
    }

    /**
     * Rename the swatch image already stored for the option.
     *
     * Color and text swatches hold a plain value rather than a path, so only values
     * living in the swatch directory are ever renamed.
     *
     * @param  array  $data
     * @param  int  $optionId
     */
    protected function renameSwatchImage($data, $optionId): void
    {
        if (! array_key_exists('swatch_file_name', $data)) {
            return;
        }

        if (! $option = $this->find($optionId)) {
            return;
        }

        if (! Str::startsWith((string) $option->swatch_value, self::SWATCH_DIRECTORY.'/')) {
            return;
        }

        $renamed = $this->mediaFileName->rename($option->swatch_value, $data['swatch_file_name']);

        if ($renamed !== $option->swatch_value) {
            parent::update(['swatch_value' => $renamed], $optionId);
        }
    }

    /**
     * Save the alt text of the swatch image, for the requested locale.
     *
     * @param  array  $data
     * @param  int  $optionId
     */
    public function saveSwatchAltText($data, $optionId): void
    {
        if (! array_key_exists('swatch_alt', $data)) {
            return;
        }

        if (! $option = $this->find($optionId)) {
            return;
        }

        foreach (core()->getRequestedLocaleCodes() as $localeCode) {
            $option->translateOrNew($localeCode)->swatch_alt = $data['swatch_alt'];
        }

        $option->save();
    }
}
