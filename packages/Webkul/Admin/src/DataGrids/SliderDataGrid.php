<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Ui\DataGrid\DataGrid;

class SliderDataGrid extends DataGrid
{
    /**
     * Set index columns, ex: id.
     *
     * @var int
     */
    protected $index = 'id';

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale = 'all';

    /**
     * Channel.
     *
     * @var string
     */
    protected $channel = 'all';

    /**
     * Contains the keys for which extra filters to render.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'channels',
        'locales',
    ];

    /**
     * Create datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /* locale */
        $this->locale = core()->getRequestedLocaleCode();

        /* channel */
        $this->channel = core()->getRequestedChannelCode();

        /* finding channel code */
        if ($this->channel !== 'all') {
            $this->channel = Channel::where('code', $this->channel)->first();

            $this->channel = $this->channel ? $this->channel->code : 'all';
        }
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $dbPrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('sliders as sl')
            ->select('sl.id', 'sl.title', 'sl.locale', 'ct.channel_id', 'ct.name', 'ch.code')
            ->leftJoin('channels as ch', 'sl.channel_id', '=', 'ch.id')
            ->leftJoin('channel_translations as ct', 'ch.id', '=', 'ct.channel_id')
            ->where('ct.locale', app()->getLocale());

        if ($this->locale !== 'all') {
            $queryBuilder->whereRaw("find_in_set(?, {$dbPrefix}sl.locale)", [$this->locale]);
        }

        if ($this->channel !== 'all') {
            $queryBuilder->where('ch.code', $this->channel);
        }

        $this->addFilter('id', 'sl.id');
        $this->addFilter('title', 'sl.title');
        $this->addFilter('locale', 'sl.locale');
        $this->addFilter('channel_name', 'ct.name');
        $this->addFilter('code', 'ch.code');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.channel-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'locale',
            'label'      => trans('admin::app.datagrid.locale'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.sliders.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.sliders.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.sliders.mass_delete'),
            'method' => 'POST',
        ]);
    }
}
