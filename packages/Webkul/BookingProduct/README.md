# Booking-product

<p>Booking Product API facilitates seamless integration with the Booking Product functionality within your Bagisto system. It provides endpoints and methods to interact with and harness the capabilities of a comprehensive booking system, enabling smooth communication and utilization of booking-related features in your applications.</p>

### Requirements:

* **Bagisto**: v2.0

### Installation:

To install the Booking Product Extension, follow these steps:

##### 1. Unzip the respective extension zip and then merge "packages" and "storage" folders into project root directory.
##### 2. Open the composer.json file and add the following line under the 'psr-4' section:

~~~
"Webkul\\BookingProduct\\": "packages/Webkul/BookingProduct/src",
~~~

##### 3. In the config/app.php file, add the following line under the 'providers' section:

~~~
Webkul\BookingProduct\Providers\BookingProductServiceProvider::class,
~~~

##### 4. In the config/bagisto-vite.php file, add the following line under the 'viters' section:

~~~
'booking' => [
    'hot_file'                 => 'booking-vite.hot',
    'build_directory'          => 'themes/booking/build',
    'package_assets_directory' => 'src/Resources/assets',
],
~~~

##### 5. Run the following commands to complete the setup:

~~~
composer dump-autoload
~~~

~~~
php artisan migrate
~~~

~~~
php artisan optimize:clear
~~~

##### 6. Run the following commands under the path packages/Webkul/Marketplace to generate the assets build file.

~~~
npm i
~~~

~~~
npm run build
~~~

After following these steps, the Booking Product Extension should be successfully installed and ready for use in your Bagisto v2.0 project.