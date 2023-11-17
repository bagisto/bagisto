<?php

namespace Webkul\CMS\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\CMS\Models\PageTranslationProxy;
use Webkul\Core\Eloquent\Repository;

class PageRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\CMS\Contracts\Page';
    }

    /**
     * @return \Webkul\CMS\Contracts\Page
     */
    public function create(array $data)
    {
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

        return $page;
    }

    /**
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\CMS\Contracts\Page
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $page = $this->find($id);

        $locale = $data['locale'] ?? app()->getLocale();

        $data[$locale]['html_content'] = str_replace('=&gt;', '=>', $data[$locale]['html_content']);

        parent::update($data, $id, $attribute);

        $page->channels()->sync($data['channels']);

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
        $exists = PageTranslationProxy::modelClass()::where('cms_page_id', '<>', $id)
            ->where('url_key', $urlKey)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return ! $exists;
    }

    /**
     * Retrieve category from slug
     *
     * @param  string  $urlKey
     * @return \Webkul\CMS\Contracts\Page
     */
    public function findByUrlKey($urlKey)
    {
        return $this->model->whereTranslation('url_key', $urlKey)->first();
    }

    /**
     * Retrieve category from slug
     *
     * @param  string  $urlKey
     * @return \Webkul\CMS\Contracts\Page|\Exception
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
