# Introduction:

Enhance your product pages with our Shipping and Supplier Info Extension for Bagisto. This extension offers customers comprehensive details about shipping and suppliers, promoting transparency and fostering trust in their purchasing decisions.

# Unlock Growth with Bagisto's Feature-Packed Supplier Info Extension!

* **Comprehensive Seller Information Display:** Show detailed seller information on product pages, including packer details, manufacturer details, importer details, and country of origin.
* **Product Code Visibility:** Display product-specific codes for easy identification and reference by customers.
* **Admin Panel Integration:** Admins can easily add and manage seller details directly from the admin panel.
* **Enhanced Transparency for Buyers:** Provide customers with in-depth information about the product’s detail, including the country of origin and other supplier-related details, fostering trust.
* **Seamless Frontend Integration:** Automatically display seller information on product pages without the need for additional configuration. User-friendly design ensures that the information is clearly visible and accessible to customers during their shopping experience.
* **Localization Support:** Compatible with multiple languages, ensuring that seller information is displayed accurately in the customer’s preferred language.

# Requirements:
* Bagisto: v2.2.2
* PHP: 8.1 or higher
* Composer 2.6.3 or higher

# Installation :
Unzip the respective extension zip and then merge "packages" folder into project root directory

* Goto config/app.php file and add following line under 'providers'

```
Webkul\SupplierInfo\Providers\SupplierInfoServiceProvider::class
```

* Goto composer.json file and add following line under 'psr-4'

```
"Webkul\\SupplierInfo\\": "packages/Webkul/SupplierInfo/src"
```
* Run these below commands to complete the setup:

```
composer dump-autoload
```
```
php artisan migrate
```
```
php artisan optimize:clear
```

* Run the below command to seed the Attribute details :

```
php artisan db:seed --class=Webkul\\SupplierInfo\\Database\\Seeders\\Attribute\\DatabaseSeeder
```

That's it, now just execute the project on your specified domain.
