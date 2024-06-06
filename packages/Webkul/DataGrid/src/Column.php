<?php

namespace Webkul\DataGrid;

use Webkul\DataGrid\ColumnTypes\Aggregate;
use Webkul\DataGrid\ColumnTypes\Boolean;
use Webkul\DataGrid\ColumnTypes\Date;
use Webkul\DataGrid\ColumnTypes\Datetime;
use Webkul\DataGrid\ColumnTypes\Decimal;
use Webkul\DataGrid\ColumnTypes\Integer;
use Webkul\DataGrid\ColumnTypes\Text;
use Webkul\DataGrid\Enums\ColumnTypeEnum;

class Column
{
    /**
     * Fully qualified database column name.
     */
    protected $databaseColumnName;

    /**
     * Create a column instance.
     */
    public function __construct(
        public string $index,
        public string $label,
        public string $type,
        public bool $searchable = false,
        public bool $filterable = false,
        public ?string $filterableType = null,
        public array $filterableOptions = [],
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

        $this->setFilterableType();

        $this->setFilterableOptions();
    }

    /**
     * Define the database column name. Initially, it will match the index. However, after adding an alias,
     * the column name may change.
     */
    public function setDatabaseColumnName(?string $databaseColumnName = null): void
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
     * Set filterable type.
     */
    public function setFilterableType(?string $filterableType = null): void
    {
        $this->filterableType = $filterableType ?: $this->getFilterableType();
    }

    /**
     * Get filterable type.
     */
    public function getFilterableType(): ?string
    {
        return $this->filterableType;
    }

    /**
     * Set filterable options.
     */
    public function setFilterableOptions(?array $filterableOptions = null): void
    {
        $this->filterableOptions = $filterableOptions ?: $this->getFilterableOptions();
    }

    /**
     * Get filterable options.
     */
    public function getFilterableOptions(): array
    {
        return $this->filterableOptions;
    }

    /**
     * Resolve the column type.
     */
    public static function resolve(
        string $index,
        string $label,
        string $type,
        bool $searchable = false,
        bool $filterable = false,
        ?string $filterableType = null,
        array $filterableOptions = [],
        bool $sortable = false,
        mixed $closure = null,
    ) {
        if ($type === ColumnTypeEnum::STRING->value) {
            return new Text($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::INTEGER->value) {
            return new Integer($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::FLOAT->value) {
            return new Decimal($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::BOOLEAN->value) {
            return new Boolean($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::DATE->value) {
            return new Date($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::DATETIME->value) {
            return new Datetime($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        if ($type === ColumnTypeEnum::AGGREGATE->value) {
            return new Aggregate($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
        }

        return new Text($index, $label, $type, $searchable, $filterable, $filterableType, $filterableOptions, $sortable, $closure);
    }
}
