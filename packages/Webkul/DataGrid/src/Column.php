<?php

namespace Webkul\DataGrid;

use Webkul\DataGrid\Enums\ColumnTypeEnum;
use Webkul\DataGrid\Exceptions\InvalidColumnException;

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
    protected bool $sortable = false;

    /**
     * Column's visibility.
     */
    protected bool $visibility = true;

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

        $this->setSearchable($column['searchable'] ?? $this->searchable);

        $this->setFilterable($column['filterable'] ?? $this->filterable);

        $this->setFilterableType($column['filterable_type'] ?? $this->filterableType);

        $this->setFilterableOptions($column['filterable_options'] ?? $this->filterableOptions);

        $this->setAllowMultipleValues($column['allow_multiple_values'] ?? $this->allowMultipleValues);

        $this->setSortable($column['sortable'] ?? $this->sortable);

        $this->setVisibility($column['visibility'] ?? $this->visibility);

        $this->setClosure($column['closure'] ?? $this->closure);

        $this->setColumnName($this->index);
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
    public function setFilterableOptions(mixed $filterableOptions): void
    {
        if ($filterableOptions instanceof \Closure) {
            $filterableOptions = $filterableOptions();
        }

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
     * Set visibility.
     */
    public function setVisibility(bool $visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * Get visibility.
     */
    public function getVisibility(): bool
    {
        return $this->visibility;
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
            'index'                 => $this->index,
            'label'                 => $this->label,
            'type'                  => $this->type,
            'searchable'            => $this->searchable,
            'filterable'            => $this->filterable,
            'filterable_type'       => $this->filterableType,
            'filterable_options'    => $this->filterableOptions,
            'allow_multiple_values' => $this->allowMultipleValues,
            'sortable'              => $this->sortable,
            'visibility'            => $this->visibility,
        ];
    }

    /**
     * Validate the column.
     */
    public static function validate(array $column): void
    {
        if (empty($column['index'])) {
            throw new InvalidColumnException('The `index` key is required. Ensure that the `index` key is present in all calls to the `addColumn` method.');
        }

        if (empty($column['label'])) {
            throw new InvalidColumnException('The `label` key is required. Ensure that the `label` key is present in all calls to the `addColumn` method.');
        }

        if (empty($column['type'])) {
            throw new InvalidColumnException('The `type` key is required. Ensure that the `type` key is present in all calls to the `addColumn` method.');
        }
    }

    /**
     * Resolve the column type class.
     */
    public static function resolveType(array $column): self
    {
        self::validate($column);

        $columnTypeClass = ColumnTypeEnum::getClassName($column['type']);

        return new $columnTypeClass($column);
    }
}
