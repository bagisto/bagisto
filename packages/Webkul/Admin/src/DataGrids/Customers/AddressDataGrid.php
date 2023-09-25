<?php

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\DataGrid\DataGrid;

class AddressDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    public $primaryColumn = 'address_id';

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct(protected CustomerRepository $customerRepository)
    {
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $customer = $this->customerRepository->find(request('id'));

        $queryBuilder = DB::table('addresses as ca')
            ->leftJoin('countries', 'ca.country', '=', 'countries.code')
            ->leftJoin('customers as c', 'ca.customer_id', '=', 'c.id')
            ->addSelect(
                'ca.id as address_id',
                'ca.company_name',
                'ca.address1',
                'ca.country',
                DB::raw('' . DB::getTablePrefix() . 'countries.name as country_name'),
                'ca.state',
                'ca.city',
                'ca.postcode',
                'ca.phone',
                'ca.default_address'
            )
            ->where('ca.address_type', CustomerAddress::ADDRESS_TYPE)
            ->where('c.id', $customer->id);

        $queryBuilder = $queryBuilder->leftJoin('country_states', function ($qb) {
            $qb->on('ca.state', 'country_states.code')
                ->on('countries.id', 'country_states.country_id');
        });

        $queryBuilder->groupBy('ca.id')
            ->addSelect(DB::raw(DB::getTablePrefix() . 'country_states.default_name as state_name'));

        $this->addFilter('company_name', 'ca.company_name');
        $this->addFilter('address1', 'ca.address1');
        $this->addFilter('postcode', 'ca.postcode');
        $this->addFilter('city', 'ca.city');
        // $this->addFilter('state_name', DB::raw(DB::getTablePrefix() . 'country_states.default_name'));
        // $this->addFilter('country_name', DB::raw(DB::getTablePrefix() . 'countries.name'));
        $this->addFilter('default_address', 'ca.default_address');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'address_id',
            'label'      => trans('admin::app.customers.addresses.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'company_name',
            'label'      => trans('admin::app.customers.addresses.company-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'address1',
            'label'      => trans('admin::app.customers.addresses.address-1'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'postcode',
            'label'      => trans('admin::app.customers.addresses.postcode'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'city',
            'label'      => trans('admin::app.customers.addresses.city'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'state_name',
            'label'      => trans('admin::app.customers.addresses.state-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'country_name',
            'label'      => trans('admin::app.customers.addresses.country-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'default_address',
            'label'      => trans('admin::app.customers.addresses.default-address'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'closure'    => function ($row) {
                if ($row->default_address) {
                    return '<span class="badge badge-md badge-success"">' . trans('admin::app.customers.addresses.yes') . '</span>';
                }

                return trans('admin::app.customers.addresses.dash');
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
        if (bouncer()->hasPermission('customers.addresses.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.customers.addresses.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.customers.customers.addresses.edit', $row->address_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('customers.addresses.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.customers.addresses.datagrid.delete'),
                'method' => 'POST',
                'url'    => function ($row) {
                    return route('admin.customers.customers.addresses.delete', $row->address_id);
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
        $this->addMassAction([
            'title'  => trans('admin::app.customers.addresses.delete'),
            'url'    => route('admin.customers.customers.addresses.mass_delete', request('id')),
            'method' => 'POST',
        ]);
    }
}
