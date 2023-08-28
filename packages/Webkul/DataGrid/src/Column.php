<?php

namespace Webkul\DataGrid;

use Webkul\DataGrid\Enums\ColumnTypeEnum;

class Column
{
    /**
     * Fully qualified database column name.
     */
    public $databaseColumnName;

    /**
     * Form input type.
     *
     * @var string
     */
    public ?string $formInputType = null;

    /**
     * Form options.
     */
    public ?array $formOptions = null;

    /**
     * Create a column instance.
     */
    public function __construct(
        public string $index,
        public string $label,
        public string $type,
        public bool $searchable = false,
        public bool $filterable = false,
        public bool $sortable = false,
        public mixed $closure = null,
    ) {
        $this->setDatabaseColumnName();
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
     * Set column type.
     */
    public function setColumnType(): void
    {
        switch ($this->type) {
            case ColumnTypeEnum::DATE_RANGE->value:
                $this->formInputType = 'date';

                $this->formOptions = $this->getDateOptions();

                break;

            case ColumnTypeEnum::DATE_TIME_RANGE->value:
                $this->formInputType = 'datetime-local';

                $this->formOptions = $this->getDateOptions('Y-m-d H:i:s');

                break;
        }
    }

    /**
     * Get date options.
     */
    public function getDateOptions(string $format = 'Y-m-d'): array
    {
        return [
            [
                'name'  => 'today',
                'label' => 'Today',
                'from'  => now()->today()->format($format),
                'to'    => now()->today()->format($format),
            ],
            [
                'name'  => 'yesterday',
                'label' => 'Yesterday',
                'from'  => now()->yesterday()->format($format),
                'to'    => now()->yesterday()->format($format),
            ],
            [
                'name'  => 'this_week',
                'label' => 'This Week',
                'from'  => now()->startOfWeek()->format($format),
                'to'    => now()->endOfWeek()->format($format),
            ],
            [
                'name'  => 'this_month',
                'label' => 'This Month',
                'from'  => now()->startOfMonth()->format($format),
                'to'    => now()->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_month',
                'label' => 'Last Month',
                'from'  => now()->subMonth(1)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_three_months',
                'label' => 'Last 3 Months',
                'from'  => now()->subMonth(3)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_six_months',
                'label' => 'Last 6 Months',
                'from'  => now()->subMonth(6)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'this_year',
                'label' => 'This Year',
                'from'  => now()->startOfYear()->format($format),
                'to'    => now()->endOfYear()->format($format),
            ],
        ];
    }
}
