<?php

namespace Webkul\CMS\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\CMS\Contracts\Page;
use Webkul\CMS\Models\PageTranslationProxy;
use Webkul\Core\Eloquent\Repository;

class PageRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Page::class;
    }

    /**
     * Create a cms page and attach it to the given channels.
     *
     * @return Page
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
     * Update a cms page and the channels it belongs to.
     *
     * @param  int  $id
     * @return Page
     */
    public function update(array $data, $id)
    {
        $page = $this->find($id);

        $locale = $data['locale'] ?? app()->getLocale();

        $data[$locale]['html_content'] = str_replace('=&gt;', '=>', $data[$locale]['html_content']);

        $page = parent::update($data, $id);

        $page->channels()->sync($data['channels']);

        return $page;
    }

    /**
     * Check that no other page, on any channel, uses the url key.
     *
     * @deprecated Use isUrlKeyUniqueInChannels().
     *
     * @param  int|null  $id
     * @param  string  $urlKey
     * @return bool
     */
    public function isUrlKeyUnique($id, $urlKey)
    {
        return $this->isUrlKeyUniqueInChannels($id, $urlKey, []);
    }

    /**
     * Check that no other page uses the url key, among the given channels when any are passed.
     *
     * @param  array<int, int|string>  $channelIds
     */
    public function isUrlKeyUniqueInChannels(?int $id, string $urlKey, array $channelIds): bool
    {
        return ! PageTranslationProxy::modelClass()::query()
            ->when($id, fn ($query) => $query->where('cms_page_id', '<>', $id))
            ->where('url_key', $urlKey)
            ->when($channelIds, fn ($query) => $query->whereIn(
                'cms_page_id',
                fn ($channelQuery) => $channelQuery->select('cms_page_id')
                    ->from('cms_page_channels')
                    ->whereIn('channel_id', $channelIds)
            ))
            ->exists();
    }

    /**
     * Retrieve category from slug.
     *
     * @param  string  $urlKey
     * @return Page
     */
    public function findByUrlKey($urlKey)
    {
        return $this->model->whereTranslation('url_key', $urlKey)->first();
    }

    /**
     * Retrieve category from slug.
     *
     * @param  string  $urlKey
     * @return Page|\Exception
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
