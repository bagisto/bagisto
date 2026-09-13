<?php

namespace Webkul\DataGrid\Tests\Fixtures;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CartRuleFixtureDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'rule_id';

    /**
     * Create the grid scoped to the cart rules whose description carries the batch marker.
     */
    public function __construct(protected ?string $batch = null) {}

    /**
     * Prepare query builder. The id is aliased so the grid exercises a custom primary column
     * and the `addFilter` mapping back onto the real table column.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
            ->select(
                'cart_rules.id as rule_id',
                'cart_rules.name',
                'cart_rules.action_type',
                'cart_rules.discount_amount',
                'cart_rules.sort_order',
                'cart_rules.status',
                'cart_rules.starts_from',
                'cart_rules.created_at',
            );

        if ($this->batch) {
            $queryBuilder->where('cart_rules.description', $this->batch);
        }

        $this->addFilter('rule_id', 'cart_rules.id');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'rule_id',
            'label' => 'ID',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'action_type',
            'label' => 'Action Type',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Percentage', 'value' => 'by_percent'],
                ['label' => 'Fixed', 'value' => 'by_fixed'],
            ],
        ]);

        $this->addColumn([
            'index' => 'discount_amount',
            'label' => 'Discount',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => 'Priority',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => 'Status',
            'type' => 'boolean',
            'filterable' => true,
            'closure' => fn ($row) => $row->status ? 'active' : 'inactive',
        ]);

        $this->addColumn([
            'index' => 'starts_from',
            'label' => 'Starts From',
            'type' => 'date',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Created At',
            'type' => 'datetime',
            'filterable' => true,
            'sortable' => true,
            'exportable' => false,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        $this->addAction([
            'index' => 'edit',
            'icon' => 'icon-edit',
            'title' => 'Edit',
            'method' => 'GET',
            'url' => fn ($row) => '/rules/'.$row->rule_id.'/edit',
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => fn ($row) => 'Delete '.$row->name,
            'method' => 'DELETE',
            'url' => fn ($row) => '/rules/'.$row->rule_id,
            'condition' => fn ($row) => $row->status === 'inactive',
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'icon' => 'icon-delete',
            'title' => 'Delete',
            'method' => 'POST',
            'url' => '/rules/mass-delete',
        ]);

        $this->addMassAction([
            'title' => 'Update Status',
            'method' => 'POST',
            'url' => '/rules/mass-update',
            'options' => [
                ['label' => 'Active', 'value' => 1],
                ['label' => 'Inactive', 'value' => 0],
            ],
        ]);
    }
}
