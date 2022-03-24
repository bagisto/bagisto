<?php

namespace Webkul\CMS\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;
use Webkul\CMS\Models\CmsPageTranslationProxy;

class CmsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CMS\Contracts\CmsPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\CMS\Contracts\CmsPage
     */
    public function create(array $data)
    {
        Event::dispatch('cms.pages.create.before');

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }

            $data[$locale->code]['html_content'] = str_replace('=&gt;', '=>', $data[$locale->code]['html_content']);
        }

        $page = parent::create($data);

        $page->channels()->sync($data['channels']);

        Event::dispatch('cms.pages.create.after', $page);

        return $page;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\CMS\Contracts\CmsPage
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $page = $this->find($id);

        Event::dispatch('cms.pages.update.before', $id);

        $locale = isset($data['locale']) ? $data['locale'] : app()->getLocale();

        $data[$locale]['html_content'] = str_replace('=&gt;', '=>', $data[$locale]['html_content']);

        parent::update($data, $id, $attribute);

        $page->channels()->sync($data['channels']);

        Event::dispatch('cms.pages.update.after', $id);

        return $page;
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param  int  $id
     * @param  string  $urlKey
     * @return bool
     */
    public function isUrlKeyUnique($id, $urlKey)
    {
        $exists = CmsPageTranslationProxy::modelClass()::where('cms_page_id', '<>', $id)
            ->where('url_key', $urlKey)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    /**
     * Retrive category from slug
     *
     * @param  string  $urlKey
     * @return \Webkul\CMS\Contracts\CmsPage|\Exception
     */
    public function findByUrlKeyOrFail($urlKey)
    {
        $page = $this->model->whereTranslation('url_key', $urlKey)->first();

        if ($page) {
            return $page;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }
}