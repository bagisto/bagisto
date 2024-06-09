<?php

namespace Webkul\DataGrid;

use Webkul\DataGrid\Enums\ColumnTypeEnum;

class Column
{
    /**
     * Column's index.
     */
    protected string $index;

    /**
     * Column's label.
     */
    protected string $label;

    /**
     * Column's type.
     */
    protected string $type;

    /**
     * Column's searchability.
     */
    protected bool $searchable = false;

    /**
     * Column's filterability.
     */
    protected bool $filterable = false;

    /**
     * Column's filterable type.
     */
    protected ?string $filterableType = null;

    /**
     * Column's filterable options.
     */
    protected array $filterableOptions = [];

    /**
     * Column's allow multiple values.
     */
    protected bool $allowMultipleValues = true;

    /**
     * Column's sortability.
     */
    protected bool $sortable = true;

    /**
     * Column's closure.
     */
    protected mixed $closure = null;

    /**
     * Fully qualified table's column name.
     */
    protected $columnName;

    /**
     * Create a column instance.
     */
    public function __construct(array $column)
    {
        $this->init($column);
    }

    /**
     * Initialize all necessary settings for the columns.
     */
    public function init(array $column): void
    {
        $this->setIndex($column['index']);

        $this->setLabel($column['label']);

        $this->setType($column['type']);

        $this->setSearchable($column['searchable'] ?? $this->getSearchable());

        $this->setFilterable($column['filterable'] ?? $this->getFilterable());

        $this->setFilterableType($column['filterable_type'] ?? $this->getFilterableType());

        $this->setFilterableOptions($column['filterable_options'] ?? $this->getFilterableOptions());

        $this->setAllowMultipleValues($column['allow_multiple_values'] ?? $this->getAllowMultipleValues());

        $this->setSortable($column['sortable'] ?? $this->getSortable());

        $this->setClosure($column['closure'] ?? $this->getClosure());

        $this->setColumnName($this->getIndex());
    }

    /**
     * Set index.
     */
    public function setIndex(string $index): void
    {
        $this->index = $index;
    }

    /**
     * Get index.
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * Set label.
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Get label.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set type.
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set searchable.
     */
    public function setSearchable(bool $searchable): void
    {
        $this->searchable = $searchable;
    }

    /**
     * Get searchable.
     */
    public function getSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Set filterable.
     */
    public function setFilterable(bool $filterable): void
    {
        $this->filterable = $filterable;
    }

    /**
     * Get filterable.
     */
    public function getFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * Set filterable type.
     */
    public function setFilterableType(?string $filterableType): void
    {
        $this->filterableType = $filterableType;
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
    public function setFilterableOptions(array $filterableOptions): void
    {
        $this->filterableOptions = $filterableOptions;
    }

    /**
     * Get filterable options.
     */
    public function getFilterableOptions(): array
    {
        return $this->filterableOptions;
    }

    /**
     * Set allow multiple values.
     */
    public function setAllowMultipleValues(bool $allowMultipleValues): void
    {
        $this->allowMultipleValues = $allowMultipleValues;
    }

    /**
     * Get allow multiple values.
     */
    public function getAllowMultipleValues(): bool
    {
        return $this->allowMultipleValues;
    }

    /**
     * Set sortable.
     */
    public function setSortable(?bool $sortable = null): void
    {
        $this->sortable = $sortable;
    }

    /**
     * Get sortable.
     */
    public function getSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * Set closure.
     */
    public function setClosure(mixed $closure): void
    {
        $this->closure = $closure;
    }

    /**
     * Get closure.
     */
    public function getClosure(): mixed
    {
        return $this->closure;
    }

    /**
     * Define the table's column name. Initially, it will match the index. However, after adding an alias,
     * the column name may change.
     */
    public function setColumnName(mixed $columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * Get the table's column name.
     */
    public function getColumnName(): mixed
    {
        return $this->columnName;
    }

    /**
     * To array.
     */
    public function toArray(): array
    {
        return [
            'index'                 => $this->getIndex(),
            'label'                 => $this->getLabel(),
            'type'                  => $this->getType(),
            'searchable'            => $this->getSearchable(),
            'filterable'            => $this->getFilterable(),
            'filterable_type'       => $this->getFilterableType(),
            'filterable_options'    => $this->getFilterableOptions(),
            'allow_multiple_values' => $this->getAllowMultipleValues(),
            'sortable'              => $this->getSortable(),
        ];
    }

    /**
     * Resolve the column type class.
     */
    public static function resolveType(array $column): self
    {
        $columnTypeClass = ColumnTypeEnum::getClassName($column['type']);

        return new $columnTypeClass($column);
    }
}
