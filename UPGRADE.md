# UPGRADE Guide

- [Upgrading To master From v2.2.1](#upgrade-master)

## High Impact Changes


## Medium Impact Changes

- [The `Webkul\DataGrid\DataGrid` class](#the-datagrid-class)

## Low Impact Changes

<a name="upgrade-master"></a>
## Upgrading To master From v2.2.1

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

<a name="datagrid"></a>
### DataGrid

<a name="the-datagrid-class"></a>
#### The `Webkul\DataGrid\DataGrid` Class

**Impact Probability: Medium**

1. Moved the `DataGridExport` class to the DataGrid package and enhanced the new exporter class with the `WithQuery` interface instead of `WithView`. This change reduces the need for temporary file creation.

2. We have removed the `exportFile` properties and all its associated method i.e. `setExportFile` and `getExportFile`,

```diff
- protected mixed $exportFile = null;
- public function setExportFile($format = 'csv')
- public function getExportFile()
```

3. We have removed two methods: `processPaginatedRequest` and `processExportRequest`.

```diff
- $this->processPaginatedRequest();
- $this->processExportRequest();
```

4. Removed all the events from the setter methods to avoid duplicate dispatching.
