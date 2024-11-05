# UPGRADE Guide

- [Upgrading To master From v2.2](#upgrading-to-master-from-v22)

## High Impact Changes

- [Updating Dependencies](#updating-dependencies)

- [Application Structure](#application-structure)

## Medium Impact Changes

- [Environment Keys Changes](#environment-keys-changes)

## Low Impact Changes

- [Removed Publishable Stuffs](#removed-publishable-stuffs)

- [Removal of Aliases and Singleton Facade Registry](#removal-of-aliases-and-singleton-facade-registry)

- [Schedule Commands Moved To Package](#schedule-commands-moved-to-package)

## Upgrading To master From v2.2

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

### Environment Keys Changes

**Impact Probability: Medium**

We have synchronized most of the new .env keys introduced in Laravel's latest release (version 11). Below, we have listed only the keys that have been newly added or had their names changed in this version.

```diff
- CACHE_DRIVER=file
+ CACHE_STORE=file

- BROADCAST_DRIVER=log
+ BROADCAST_CONNECTION=log

- QUEUE_DRIVER=sync
+ QUEUE_CONNECTION=database
```

Additionally, the following keys have been removed to prevent the `.env` file from becoming unnecessarily large. These keys are not required unless the related services are being used. In a fresh installation of Bagisto, these keys will not be present by default; you will need to add them manually if you plan to use the corresponding services.

```diff
- FIXER_API_KEY=
- EXCHANGE_RATES_API_KEY=
- EXCHANGE_RATES_API_ENDPOINT=

- PUSHER_APP_ID=
- PUSHER_APP_KEY=
- PUSHER_APP_SECRET=
- PUSHER_APP_CLUSTER=mt1

- MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
- MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

- FACEBOOK_CLIENT_ID=
- FACEBOOK_CLIENT_SECRET=
- FACEBOOK_CALLBACK_URL=https://yourhost.com/customer/social-login/facebook/callback

- TWITTER_CLIENT_ID=
- TWITTER_CLIENT_SECRET=
- TWITTER_CALLBACK_URL=https://yourhost.com/customer/social-login/twitter/callback

- GOOGLE_CLIENT_ID=
- GOOGLE_CLIENT_SECRET=
- GOOGLE_CALLBACK_URL=https://yourhost.com/customer/social-login/google/callback

- LINKEDIN_CLIENT_ID=
- LINKEDIN_CLIENT_SECRET=
- LINKEDIN_CALLBACK_URL=https://yourhost.com/customer/social-login/linkedin-openid/callback

- GITHUB_CLIENT_ID=
- GITHUB_CLIENT_SECRET=
- GITHUB_CALLBACK_URL=https://yourhost.com/customer/social-login/github/callback
```

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
```

OR

```diff
+ use Webkul\Core\Facades\Core;

+ Core::getAllChannels();
```

> [!NOTE]
> Did you noticed, in the newer version of Bagisto, we have replaced all alias references with full namespaces.

This change applies to all our helper methods, not just `core()`. Always prefer the helper method if one is available.

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

### Schedule Commands Moved To Package

**Impact Probability: Low**

All scheduled commands previously registered in the Kernel have now been moved to their respective packages. Since each command belongs to a specific package, it is more appropriate for the package itself to handle its own commands. Therefore, each individual package is now responsible for registering its respective commands.

```diff
- $schedule->command('invoice:cron')->dailyAt('3:00');
- $schedule->command('indexer:index --type=price')->dailyAt('00:01');
- $schedule->command('product:price-rule:index')->dailyAt('00:01');

+ // Core Package
+ $schedule->command('invoice:cron')->dailyAt('3:00');

+ // Product Package
+ $schedule->command('indexer:index --type=price')->dailyAt('00:01');

+ // CatalogRule Package
+ $schedule->command('product:price-rule:index')->dailyAt('00:01');
```

### Packages

Below are the specific changes we have implemented in each package:

#### Core

##### Unwanted Files Removed

The following files have been removed as they are no longer needed:

**Impact Probability: Low**

- Configs

```diff
- src/Config/concord.php
- src/Config/elasticsearch.php
- src/Config/repository.php
- src/Config/visitor.php
```

- Commands

```diff
- src/Console/Commands/BagistoPublish.php
- src/Console/Commands/DownChannelCommand.php
- src/Console/Commands/UpChannelCommand.php
```

These files have been deemed unnecessary in the current structure, streamlining the codebase and reducing clutter

##### Exception Handler

**Impact Probability: Low**

n Bagisto, we have overridden the default Laravel 11 exception handler. Since the Laravel 11 application skeleton is now empty, we need to override the core Laravel exception handler instead of using the handler within the app directory.

Additionally, the access modifiers for some of our methods have been updated.

```diff
- private function handleAuthenticationException(): void
+ protected function handleAuthenticationException(): void

- private function handleHttpException(): void
+ protected function handleHttpException(): void

- private function handleValidationException(): void
+ protected function handleValidationException(): void

- private function handleServerException(): void
+ protected function handleServerException(): void
```

##### Maintenance Mode Middleware

**Impact Probability: Low**

The `CheckForMaintenanceMode` class has been removed, and a new class, `PreventRequestsDuringMaintenance`, has been introduced. In Laravel, `PreventRequestsDuringMaintenance` middleware is applied at the global level. However, in Bagisto, we have removed the middleware from the global scope and applied it at the route level.

The reason for this change is that we need to display customized pages based on the current theme, and if the middleware is applied globally, the theme may not be accessible, resulting an error.

```diff
- CheckForMaintenanceMode.php

+ PreventRequestsDuringMaintenance.php
```

#### DataGrid

##### The `Webkul\DataGrid\DataGrid` Class

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
