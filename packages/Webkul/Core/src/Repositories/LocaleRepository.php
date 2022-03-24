<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

class LocaleRepository extends Repository
{
    use CacheableRepository;

    /**
     * Storage path for locale images.
     *
     * @var string
     */
    protected $storageDir = 'settings/locale-images';

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Core\Contracts\Locale::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('core.locale.create.before');

        $locale = parent::create($attributes);

        $this->uploadImage($attributes, $locale);

        Event::dispatch('core.locale.create.after', $locale);

        return $locale;
    }

    /**
     * Update.
     *
     * @param  array  $attributes
     * @param  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('core.locale.update.before', $id);

        $locale = parent::update($attributes, $id);

        $this->uploadImage($attributes, $locale);

        Event::dispatch('core.locale.update.after', $locale);

        return $locale;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('core.locale.delete.before', $id);

        parent::delete($id);

        Event::dispatch('core.locale.delete.after', $id);
    }

    /**
     * Upload image.
     *
     * @param  array  $attributes
     * @param  \Webkul\Core\Models\Locale  $locale
     * @return void
     */
    public function uploadImage($attributes, $locale)
    {
        if (isset($attributes['locale_image'])) {
            foreach ($attributes['locale_image'] as $imageId => $image) {
                $file = 'locale_image' . '.' . $imageId;
                $dir = $this->storageDir . '/' . $locale->id;

                if ($locale->locale_image) {
                    Storage::delete($locale->locale_image);
                }

                if (request()->hasFile($file)) {
                    $locale->locale_image = request()->file($file)->store($dir);
                    $locale->save();
                }
            }
        } else {
            if ($locale->locale_image) {
                Storage::delete($locale->locale_image);
            }

            $locale->locale_image = null;
            $locale->save();
        }
    }
}
