# Introduction:
Bagisto SAAS extension is eCommerce virtual mall, where multiple-vendor can sign-up and create their own e-commerce store with their personalized domain name. SAAS extension will help you in appealing sellers to get registered with your business and use common resources such as Payment gateways, Shipping methods, etc.

Get involved and control your mall by managing commissions directly from single place, control on customer signups, super cool super admin panel to manage sellers, activate/deactivate your sellers from super admin panel too and lots more.


# Feature of Laravel ecommerce SAAS Module

* Easy to Setup and manage SAAS based eCommerce solution.
* Scaling your business is now directly proportional to no. of sellers.
* Separate admin panel for each seller.
* Ability of seller to change domain at will.
* Easily manageable orders.
* Faster updates will bring lots of demanding features.

# Requirements
* Bagisto v0.1.6 or higher.

# Note
 1. Do not install bagisto till you configure this extension initially w/ it.
 2. Before proceeding this extension you need to copy files from zip
file to root location of your Bagisto installed inside it
 3. Do not try to seed the database with command 'php artisan db:seed'

# Installation
* Run the command from root in terminal 'composer create-project'
* Find '.env' file in root directory and change the APP_URL param to domain,
ex: www.example.com and do not forget to configure Mail and DB parameters inside .env file
* Run the following commands:

```
php artisan migrate (will take a while)
php artisan storage:link
php artisan vendor:publish
```
* Do couple of entries in root composer.json in psr-4 object:

```
'Webkul\SAASCustomizer\": "packages/Webkul/SAASCustomizer/src',
'Webkul\CustomerCustomizer\": "packages/Webkul/CustomerCustomizer/src'
```
* Another entry inside file('app/Http/Kernel.php',):
In this file you can find an array 'middlewareGroups' inside it there is
a key named 'web' inside it do an entry:

```
Webkul\SAASCustomizer\Http\Middleware\ValidatesDomain::class'
```
* Find a file auth.php present inside config folder from root and do the following entries:
```
'super-admin' => [
        'driver' => 'session',
        'provider' => 'superadmins'
]
```
> insert the above code in 'guards' array.

```
'superadmins' => [
    'driver' => 'eloquent',
    'model' => Webkul\SAASCustomizer\Models\SuperAdmin::class
]
```
> insert the above code in 'providers' array.

* Run the command from root in terminal 'composer dump-autoload'.
* In your terminal now type command 'php artisan SAAS:install', to generate the super admin credentials
* Access super admin panel using:
www.domain.com/super/login
* Run your SAAS based store.