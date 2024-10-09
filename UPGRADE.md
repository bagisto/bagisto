# UPGRADE Guide

- [Upgrading To master From v2.2.1](#upgrade-master)

## High Impact Changes

- [Updating Dependencies](#updating-dependencies)

- [Application Structure](#application-structure)

## Medium Impact Changes

- [The `Webkul\DataGrid\DataGrid` class](#the-datagrid-class)

## Low Impact Changes

- [Removed Publishable Stuffs](#removed-publishable-stuffs)

- [Removal of Aliases and Singleton Facade Registry](#removal-of-aliases-and-singleton-facade-registry)

<a name="upgrade-master"></a>
## Upgrading To master From v2.2.1

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Updating Dependencies

**Impact Probability: High**

#### PHP 8.2.0 Required

We have upgraded to Laravel 11 and Laravel now requires PHP 8.2.0 or greater.

#### Composer Dependencies

We have handled most of the dependencies mentioned by Laravel, so there is no need for further action on your part.

#### NPM Depenencies

We have upgraded the NPM dependencies to Vite 5 and the Laravel Vite Plugin to version 1.0. This update will not affect your work unless you are working directly on the package. In that case, you need to rename the file postcss.config.js to postcss.config.cjs. Below are the changes required in your package file:

```diff
- module.exports = {
-  plugins: {
-    tailwindcss: {},
-    autoprefixer: {},
-  },
- }

+ module.exports = ({ env }) => ({
+     plugins: [require("tailwindcss")(), require("autoprefixer")()],
+ });
```

### Application Structure

**Impact Probability: High**

With Laravel 11, a new default application structure has been introduced, resulting in a leaner setup with fewer default files. This update reduces the number of service providers, middleware, and configuration files in the framework.

Since Bagisto is built on top of Laravel, we have also updated Bagisto to Laravel 11 and adopted the same streamlined approach to maintain compatibility. For more detailed information, please refer to the [Laravel documentation](https://laravel.com/docs/11.x). 

### Removed Publishable Stuffs

**Impact Probability: Low**

We have removed all the publishable registries from the service provider, and we are no longer publishing any files. Previously, configuration files were often published manually. However, with this update, most of the configuration files have been moved directly into the default config folder, simplifying the structure and eliminating the need for manual publishing.

For instance, if you look at the **Core** package, there were previously four configuration files located in the `Config` folder of the package:

```diff
- packages/Webkul/Core/src/Config/*.php
```

These configuration files have now been moved to the root `config` folder:

```diff
+ config/*.php
```

This change consolidates the configuration files into a central location, following Laravel 11's convention and eliminating the need for separate publishable registries.

### Removal of Aliases and Singleton Facade Registry

**Impact Probability: Low**

We have removed all aliases and the singleton facade registry. This change was made because facades are inherently singletons, making the additional registry unnecessary.

#### Using Helper Methods Instead of Aliases

We now strongly recommend using our helper methods instead of relying on aliases. This approach provides better IDE support, type hinting, and makes the code more explicit and easier to understand.

Instead of:

```diff
- app('core')->getAllChannels()
```

Use:

```diff
+ core()->getAllChannels(); // Recommended

+ Core::getAllChannels();
```

This change applies to all our helper methods, not just `core()`. Always prefer the helper method if one is available.

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
