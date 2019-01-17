<p align="center">
<a href="http://www.bagisto.com"><img src="https://bagisto.com/wp-content/themes/bagisto/images/logo.png" alt="Total Downloads"></a>
</p>

<p align="center">
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/license.svg" alt="License"></a>
<a href="#backers"><img src="https://opencollective.com/bagisto/backers/badge.svg" alt="Backers on Open Collective"></a>
<a href="#sponsors"><img src="https://opencollective.com/bagisto/sponsors/badge.svg" alt="Sponsors on Open Collective"></a>
</p>

# Topics
1. ### [Introduction](##1-introduction-)
2. ### [Requirements](##requirements)
3. ### [Configuration](##configuration)
4. ### [Installation](##installation)
5. ### [License](##licence)
6. ### [Miscellaneous](##miscellaneous)

### 1. Introduction <a name="#1-introduction-"></a>:

[Bagisto](https://www.bagisto.com) is a hand tailored E-Commerce framework designed on some of the hottest opensource technologies
such as [Laravel](https://laravel.com) a [PHP](https://secure.php.net/) framework, [Vue.js](https://vuejs.org)
a progressive Javascript framework.

**Bagisto is viable attempt to cut down your time, cost and workforce for building online stores or migrating from physical stores
to the ever demanding online world. Your business whether small or huge it suits all and very simple to set it up.**

**We are also having a forum for any type of your concern, feature request discussions. Please visit: [Bagisto Forums](https://forums.bagisto.com/)**

It packs in lots of demanding features that allows your business to scale in no time:

* Multiple Channels, Locale, Currencies.
* Built-in Access Control Layer.
* Beautiful and Responsive Storefront.
* Descriptive and Simple Admin Panel.
* Admin Dashboard.
* Custom Attributes.
* Built on Modular Approach.
* Support for Multiple Store Themes.
* Multistore Inventory System.
* Orders Management System.
* Customer Cart, Wishlist, Product Reviews.
* Simple and Configurable Products.
* Check out [more....](https://bagisto.com/features/).

**For Developers**:
Dev guys can take advantage of two of the hottest frameworks used in this project Laravel and Vue.js, both of these frameworks have been used in Bagisto.
Bagisto is using power of both of these frameworks and making best out of it out of the box.

### 2. Requirements <a name="#requirements"></a>:

* **OS**: Ubuntu 16.04 LTS or higher.
* **SERVER**: Apache 2 or NGINX
* **RAM**: 2 GB or higer.
* **PHP**: 7.1.17 or higher.
* **Processor**: Clock Cycle 1Ghz or higher.
* **Mysql**: 5.7.23 or higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

### 3. Configuration <a name="#configuration"></a>:

**Run this Command** to download the project on to your local machine or server:

> Note: If you have downloaded zip file then ignore this.

~~~
composer create-project bagisto/bagisto
~~~

if the above command's process was successful, you will find directory **bagisto** and all of the code will be inside it.

After it set your **.env** variable, especially the ones below:

* **APP_URL**
* **DB_CONNECTION**
* **DB_HOST**
* **DB_PORT**
* **DB_DATABASE**
* **DB_USERNAME**
* **DB_PASSWORD**

Although you have to set the mailer variables also for full functioning of your store for sending emails at various events by
default.


### 4. Installation <a name="#installation"></a>:

**Run these Commands Below**

> Run this command, in case installing from the zip else skip this command (no need to run this command if you are creating project through composer):
~~~
composer install
~~~

> Continue run these command below:
~~~
php artisan migrate
~~~
~~~
php artisan db:seed
~~~
~~~
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
~~~
```
php artisan storage:link
```

```
composer dump-autoload
```

> That's it, now just execute the project on your specified domain entry point pointing to public folder inside installation directory.

> **Note: you can fetch your admin panel by follow below url:**
~~~
http(s)://example.com/admin/login
~~~
~~~
email:admin@example.com
password:admin123
~~~

### 5. License <a name="#license"></a>:
Bagisto is a truly opensource E-Commerce framework which will always be free under the [MIT License](https://github.com/bagisto/bagisto/blob/master/LICENSE).


### 6. Miscellaneous <a name="#miscellaneous"></a>:

## Contributors

This project exists thanks to all the people who contribute.
<a href="https://github.com/bagisto/bagisto/graphs/contributors"><img src="https://opencollective.com/bagisto/contributors.svg?width=890&button=false" /></a>


## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/bagisto#backer)]

<a href="https://opencollective.com/bagisto#backers" target="_blank"><img src="https://opencollective.com/bagisto/backers.svg?width=890"></a>


## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/bagisto#sponsor)]

<a href="https://opencollective.com/bagisto/sponsor/0/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/1/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/2/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/3/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/4/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/5/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/6/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/7/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/8/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/bagisto/sponsor/9/website" target="_blank"><img src="https://opencollective.com/bagisto/sponsor/9/avatar.svg"></a>


### Coming Soon:
-> API support for core packages and numerous fixes.
