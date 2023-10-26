<?php

namespace Webkul\DataGrid;

use Webkul\DataGrid\Enums\ColumnTypeEnum;
use Webkul\DataGrid\Enums\FormInputTypeEnum;
use Webkul\DataGrid\Enums\RangeOptionEnum;

class Column
{
    /**
     * Fully qualified database column name.
     */
    public $databaseColumnName;

    /**
     * Form input type.
     */
    protected ?string $formInputType = null;

    /**
     * Form options.
     */
    protected ?array $formOptions = null;

    /**
     * Create a column instance.
     */
    public function __construct(
        public string $index,
        public string $label,
        public string $type,
        public ?array $options = null,
        public bool $searchable = false,
        public bool $filterable = false,
        public bool $sortable = false,
        public mixed $closure = null,
    ) {
        $this->init();
    }

    /**
     * Initialize all necessary settings for the columns.
     */
    public function init(): void
    {
        $this->setDatabaseColumnName();

        switch ($this->type) {
            case ColumnTypeEnum::BOOLEAN->value:
                $this->setFormOptions($this->getBooleanOptions());

                break;

            case ColumnTypeEnum::DROPDOWN->value:
                $this->setFormOptions($this->options);

                break;

            case ColumnTypeEnum::DATE_RANGE->value:
                $this->setFormInputType(FormInputTypeEnum::DATE->value);

                $this->setFormOptions($this->getRangeOptions());

                break;

            case ColumnTypeEnum::DATE_TIME_RANGE->value:
                $this->setFormInputType(FormInputTypeEnum::DATE_TIME->value);

                $this->setFormOptions($this->getRangeOptions('Y-m-d H:i:s'));

                break;
        }
    }

    /**
     * Define the database column name. Initially, it will match the index. However, after adding an alias,
     * the column name may change.
     */
    public function setDatabaseColumnName(mixed $databaseColumnName = null): void
    {
        $this->databaseColumnName = $databaseColumnName ?: $this->index;
    }

    /**
     * Get the database column name.
     */
    public function getDatabaseColumnName(): mixed
    {
        return $this->databaseColumnName;
    }

    /**
     * Set form input type.
     */
    public function setFormInputType(string $formInputType): void
    {
        $this->formInputType = $formInputType;
    }

    /**
     * Get form input type.
     */
    public function getFormInputType(): ?string
    {
        return $this->formInputType;
    }

    /**
     * Set form options.
     */
    public function setFormOptions(array $formOptions): void
    {
        $this->formOptions = $formOptions;
    }

    /**
     * Get form options.
     */
    public function getFormOptions(): ?array
    {
        return $this->formOptions;
    }

    /**
     * Get boolean options.
     */
    public function getBooleanOptions(): array
    {
        return [
            [
                'label' => trans('admin::app.components.datagrid.filters.boolean-options.true'),
                'value' => 1,
            ],
            [
                'label' => trans('admin::app.components.datagrid.filters.boolean-options.false'),
                'value' => 0,
            ],
        ];
    }

    /**
     * Get range options.
     */
    public function getRangeOptions(string $format = 'Y-m-d'): array
    {
        return [
            [
                'name'  => RangeOptionEnum::TODAY->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.today'),
                'from'  => now()->today()->format($format),
                'to'    => now()->today()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::YESTERDAY->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.yesterday'),
                'from'  => now()->yesterday()->format($format),
                'to'    => now()->yesterday()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::THIS_WEEK->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.this-week'),
                'from'  => now()->startOfWeek()->format($format),
                'to'    => now()->endOfWeek()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::THIS_MONTH->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.this-month'),
                'from'  => now()->startOfMonth()->format($format),
                'to'    => now()->endOfMonth()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::LAST_MONTH->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.last-month'),
                'from'  => now()->subMonth(1)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::LAST_THREE_MONTHS->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.last-three-months'),
                'from'  => now()->subMonth(3)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::LAST_SIX_MONTHS->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.last-six-months'),
                'from'  => now()->subMonth(6)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => RangeOptionEnum::THIS_YEAR->value,
                'label' => trans('admin::app.components.datagrid.filters.date-options.this-year'),
                'from'  => now()->startOfYear()->format($format),
                'to'    => now()->endOfYear()->format($format),
            ],
        ];
    }
}
