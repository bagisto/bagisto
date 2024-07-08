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

1. For consistency, we have added a new `getExportFile` method and deprecated the `downloadExportFile` method.

```diff
- $this->downloadExportFile();
+ $this->getExportFile();
```

2. We have removed two methods: `processPaginatedRequest` and `processExportRequest`.

```diff
- $this->processPaginatedRequest();
- $this->processExportRequest();
```

3. Removed all the events from the setter methods to avoid duplicate dispatching.
