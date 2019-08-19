# Laravel Webfont v0.1

# Introduction:
Bagisto webfont is dynamic implementation of google web fonts. It allows you to choose fonts from google fonts directly from your own Bagisto instance. Choose as many google fonts at once and customize your font for storefront or backend or both within few steps.

# Feature of Bagisto webfont module

* Allows you to activate and deactivate module.
* Allow a default font in case you have chosen none.
* Separate control for using font in admin panel or storefront.

# Requirements
* Bagisto v0.1.7 or higher.

# Note:
Before proceeding you need google fonts API key. You can get your own key by following instructions after opening the link below:

> https://developers.google.com/fonts/docs/developer_api

# Installation
* Extract contents of the zip file in the root of your Bagisto instance.
* Do entry in composer.json in psr-4 object:

```
"Webkul\\Webfont\\": "packages/Webkul/Webfont/src"
```

* Do entry in config/app.php, inside providers array preferably at the end of it:

```
Webkul\Webfont\Providers\WebfontServiceProvider::class
```

* Do entry in config\concord.php file:

```
\Webkul\Webfont\Providers\ModuleServiceProvider::class
```

* Run command below:
1. composer dump-autoload
> Proceed if there are no errors encountered

2. php artisan migrate

3. php artisan route:cache

* You can now find the module configuration in admin panel under Configuration > General > Design.

* In the configuration page, there is a field for API key which will require to enter your own Google Fonts Developer API key.

* Find the module under CMS > Webfont.

> Congrats, you are all set to use Google fonts from your Bagisto instance.
