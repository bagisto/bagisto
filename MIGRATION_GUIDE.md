# Migration Guide:

#### Note: Only for git users or updating by zip file from the release section from repository

* Better way to update Bagisto is to overwrite the code by manually downloading the zip file and extract it on the main project directory.

    or
* Delete or rename the old installation directory and install the new one using the installation process in the readme of Bagisto.


* Follow these commands below if you're doing overwrite else use your old .env file before deleting/backing up old bagisto release and installing the new release of Bagisto.

## 1. Migration from v0.1.0 to v0.1.1

**Run commands below:**

> **php artisan migrate**

> **php artisan vendor:publish**


## 2. Migration from v0.1.1 to v0.1.2

**Run commands below:**

> **php artisan migrate**

> **php artisan vendor:publish**


## 3. Migration from v0.1.2 to v0.1.3

**Run commands below:**

> **composer install**

> **php artisan migrate**

> **php artisan vendor:publish**
