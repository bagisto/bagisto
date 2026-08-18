<?php

namespace Webkul\Admin\DataGrids\Appearance;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SectionDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $whereInLocales = core()->getRequestedLocaleCode() === 'all'
            ? core()->getAllLocales()->pluck('code')->toArray()
            : [core()->getRequestedLocaleCode()];

        $queryBuilder = DB::table('sections')
            ->distinct()
            ->leftJoin('section_translations', function ($join) use ($whereInLocales) {
                $join->on('sections.id', '=', 'section_translations.section_id')
                    ->whereIn('section_translations.locale', $whereInLocales);
            })
            ->leftJoin('channel_translations', function ($join) use ($whereInLocales) {
                $join->on('sections.channel_id', '=', 'channel_translations.channel_id')
                    ->whereIn('channel_translations.locale', $whereInLocales);
            })
            ->select(
                'sections.id',
                'sections.type',
                'sections.sort_order',
                'sections.status',
                'sections.name as section_name',
                'sections.theme_code',
                'sections.channel_id',
                'channel_translations.name as channel_name'
            );

        $this->addFilter('id', 'sections.id');
        $this->addFilter('type', 'sections.type');
        $this->addFilter('section_name', 'sections.name');
        $this->addFilter('sort_order', 'sections.sort_order');
        $this->addFilter('status', 'sections.status');
        $this->addFilter('channel_name', 'channel_translations.name');
        $this->addFilter('theme_code', 'sections.theme_code');

        /**
         * Opened from a theme card, so only that theme's sections are listed. The
         * datagrid component forwards the page query onto its own request, which is
         * what makes the scope survive the ajax reload.
         */
        if ($theme = self::requestedTheme()) {
            $queryBuilder->where('sections.theme_code', $theme);
        }

        return $queryBuilder;
    }

    /**
     * The theme this listing is scoped to, or null when every section is listed.
     */
    public static function requestedTheme(): ?string
    {
        $theme = request('theme');

        return $theme && array_key_exists($theme, config('themes.shop', []))
            ? $theme
            : null;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $themes = config('themes.shop');

        $this->addColumn([
            'index' => 'channel_name',
            'label' => trans('admin::app.appearance.sections.index.datagrid.channel_name'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'theme_code',
            'label' => trans('admin::app.appearance.sections.index.datagrid.theme'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect($themes = config('themes.shop'))
                ->map(fn ($theme, $code) => ['label' => $theme['name'], 'value' => $code])
                ->values()
                ->toArray(),
            'closure' => function ($row) use ($themes) {
                $name = collect($themes)->first(fn ($theme, $code) => $code === $row->theme_code)['name'] ?? 'N/A';

                $activeTheme = core()->getAllChannels()->firstWhere('id', $row->channel_id)?->theme;

                if ($activeTheme === $row->theme_code) {
                    return $name;
                }

                return $name.' <span class="label-pending">'.trans('admin::app.appearance.themes.index.not-in-use').'</span>';
            },
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => trans('admin::app.appearance.sections.index.datagrid.type'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'section_name',
            'label' => trans('admin::app.appearance.sections.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.appearance.sections.index.datagrid.sort-order'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.appearance.sections.index.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.appearance.sections.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.appearance.sections.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
            'closure' => function ($value) {
                if ($value->status) {
                    return '<p class="label-active">'.trans('admin::app.appearance.sections.index.datagrid.active').'</p>';
                }

                return '<p class="label-pending">'.trans('admin::app.appearance.sections.index.datagrid.inactive').'</p>';
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
        if (bouncer()->hasPermission('appearance.sections.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.appearance.sections.index.datagrid.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.appearance.sections.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('appearance.sections.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.appearance.sections.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.appearance.sections.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('appearance.sections.edit')) {
            $this->addMassAction([
                'title' => trans('admin::app.appearance.sections.index.datagrid.change-status'),
                'url' => route('admin.appearance.sections.mass_update'),
                'method' => 'POST',
                'options' => [
                    [
                        'label' => trans('admin::app.appearance.sections.index.datagrid.active'),
                        'value' => 1,
                    ], [
                        'label' => trans('admin::app.appearance.sections.index.datagrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('appearance.sections.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.appearance.sections.index.datagrid.delete'),
                'url' => route('admin.appearance.sections.mass_delete'),
                'method' => 'POST',
            ]);
        }
    }
}
