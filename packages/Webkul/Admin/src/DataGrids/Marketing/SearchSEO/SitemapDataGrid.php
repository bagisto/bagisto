<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->addSelect(
                'sitemaps.id',
                'sitemaps.file_name',
                'sitemaps.path',
                'sitemaps.path as url'
            )
            ->addSelect(DB::raw('GROUP_CONCAT(DISTINCT channels.code) as channel'))
            ->addSelect(DB::raw('GROUP_CONCAT(DISTINCT channels.id) as channel_ids'))
            ->leftJoin('sitemap_channels', 'sitemaps.id', '=', 'sitemap_channels.sitemap_id')
            ->leftJoin('channels', 'sitemap_channels.channel_id', '=', 'channels.id')
            ->groupBy('sitemaps.id');

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
            'closure' => function ($row) {
                return Storage::disk('public')->url(clean_path($row->path.'/'.$row->file_name));
            },
        ]);

        $this->addColumn([
            'index' => 'channel_ids',
            'label' => 'Channel IDs',
            'type' => 'string',
            'visibility' => false,
            'closure' => function ($row) {
                if (empty($row->channel_ids)) {
                    return [];
                }

                return array_map('intval', explode(',', $row->channel_ids));
            },
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
                'route' => 'admin.marketing.search_seo.sitemaps.update',
                'url' => function ($row) {
                    return route('admin.marketing.search_seo.sitemaps.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.search_seo.sitemaps.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.marketing.search_seo.sitemaps.delete', $row->id);
                },
            ]);
        }
    }
}
