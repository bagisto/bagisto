<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SitemapDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sitemaps')
            ->select(
                'sitemaps.id',
                'sitemaps.file_name',
                'sitemaps.path'
            )
            ->addSelect(DB::raw('GROUP_CONCAT(DISTINCT '.DB::getTablePrefix().'channels.code) as channel'))
            ->addSelect(DB::raw('GROUP_CONCAT(DISTINCT CONCAT('.DB::getTablePrefix().'channels.id, "::", '.DB::getTablePrefix().'channels.code, "::", '.DB::getTablePrefix().'channels.hostname) SEPARATOR "||") as channel_hostnames'))
            ->leftJoin('sitemap_channels', 'sitemaps.id', '=', 'sitemap_channels.sitemap_id')
            ->leftJoin('channels', 'sitemap_channels.channel_id', '=', 'channels.id')
            ->groupBy('sitemaps.id', 'sitemaps.file_name', 'sitemaps.path');

        $this->addFilter('id', 'sitemaps.id');
        $this->addFilter('channel', 'sitemap_channels.channel_id');

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'channel',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.channel'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect(core()->getAllChannels())
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'file_name',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.file-name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'path',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.path'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'url',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.link-for-google'),
            'type' => 'string',
            'closure' => fn ($row) => $this->buildChannelUrls($row),
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketing.search_seo.sitemaps.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.marketing.search_seo.sitemaps.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('marketing.search_seo.sitemaps.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.marketing.search_seo.sitemaps.delete', $row->id),
            ]);
        }
    }

    /**
     * Build a list of sitemap index URLs, one per channel.
     *
     * @param  object  $row
     * @return array
     */
    protected function buildChannelUrls($row)
    {
        $stem = pathinfo($row->file_name, PATHINFO_FILENAME);

        $extension = pathinfo($row->file_name, PATHINFO_EXTENSION);

        return collect(explode('||', (string) $row->channel_hostnames))
            ->filter()
            ->map(function ($triple) use ($row, $stem, $extension) {
                [$channelId, $channelCode, $hostname] = array_pad(explode('::', $triple, 3), 3, null);

                $hostname = $this->normalizeHostname($hostname);

                if ($hostname === null || $channelCode === null) {
                    return null;
                }

                $file = clean_path(
                    'sitemaps/'.$channelCode
                    .'/'.$row->path
                    .'/'.$stem.'-'.$row->id.'-'.$channelId.'.'.$extension
                );

                return $hostname.'/storage/'.$file;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Normalize a hostname into a fully-qualified base URL, or null if empty.
     *
     * @param  string|null  $hostname
     * @return string|null
     */
    protected function normalizeHostname($hostname)
    {
        $hostname = rtrim(trim((string) $hostname), '/');

        if ($hostname === '') {
            return null;
        }

        if (! preg_match('#^https?://#i', $hostname)) {
            $hostname = 'https://'.$hostname;
        }

        return $hostname;
    }
}
