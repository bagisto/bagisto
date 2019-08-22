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

## Topics
1. [Introduction](#introduction)
2. [Documentation](#documentation)
3. [Requirements](#requirements)
4. [Installation & Configuration](#installation-and-configuration)
5. [License](#license)
6. [Miscellaneous](#miscellaneous)

### Introduction

[Bagisto](https://www.bagisto.com) is a hand tailored E-Commerce framework designed on some of the hottest opensource technologies
such as [Laravel](https://laravel.com) a [PHP](https://secure.php.net/) framework, [Vue.js](https://vuejs.org)
a progressive Javascript framework.

**Bagisto is viable attempt to cut down your time, cost and workforce for building online stores or migrating from physical stores
to the ever demanding online world. Your business whether small or huge it suits all and very simple to set it up.**

**Read our documentation: [Bagisto Docs](https://devdocs.bagisto.com/)**

**We are also having a forum for any type of your concern, feature request discussions. Please visit: [Bagisto Forums](https://forums.bagisto.com/)**

# Visit our live [Demo](https://demo.bagisto.com)

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
* Price rules (Discount) inbuilt.
* Check out [click here](https://bagisto.com/features/).

**For Developers**:
Dev guys can take advantage of two of the hottest frameworks used in this project Laravel and Vue.js, both of these frameworks have been used in Bagisto.
Bagisto is using power of both of these frameworks and making best out of it out of the box.

### Documentation

#### Bagisto Documentation [https://devdocs.bagisto.com](https://devdocs.bagisto.com)

### Requirements

* **OS**: Ubuntu 16.04 LTS or higher / Windows 7 or Higher (WAMP / XAMP).
* **SERVER**: Apache 2 or NGINX.
* **RAM**: 3 GB or higher.
* **PHP**: 7.1.17 or higher.
* **Processor**: Clock Cycle 1 Ghz or higher.
* **For MySQL users**: 5.7.23 or higher.
* **For MariaDB users**: 10.2.7 or Higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

### Installation and Configuration

**1. Try our new GUI installer to install Bagisto:**

##### a. Download zip from the link below:

[Download](https://github.com/bagisto/bagisto/archive/v0.1.6.zip)

##### b. Extract the contents of zip and execute the project in browser:

~~~
http(s)://localhost/bagisto/public
~~~

or

~~~
http(s)://example.com/public
~~~

**2. Try our old fashioned way to install Bagisto:**

##### Execute these commands below as in their order

~~~
1. composer create-project bagisto/bagisto
~~~

**Now configure your database:**

If the above command was completed successfully, then you'll find directory **bagisto** and all of the code will be inside it.

Find file **.env** inside **bagisto** directory and set the environment variables listed below:

* **APP_URL**
* **DB_CONNECTION**
* **DB_HOST**
* **DB_PORT**
* **DB_DATABASE**
* **DB_USERNAME**
* **DB_PASSWORD**

Although, mailer environment variables are also required to be set up as **Bagisto** requires emails to send to customers and admins for various functionalities that are built in.

~~~
2. php artisan migrate
~~~

~~~
3. php artisan db:seed
~~~

~~~
4. php artisan vendor:publish
-> Press 0 and then press enter to publish all assets and configurations.
~~~

~~~
5. php artisan storage:link
~~~

~~~
6. composer dump-autoload
~~~


**To execute Bagisto**:

##### On server:

Warning: Before going full on production mode we recommend you to install developer dependencies.
In order to do that, run the command below:

> composer install --no-dev

~~~
Open the specified entry point in your hosts file in browser or make entry in hosts file if not done.
~~~

##### On local:

~~~
php artisan serve
~~~


**How to log in as admin:**

> *http(s)://example.com/admin/login*

~~~
email:admin@example.com
password:admin123
~~~

**How to log in as customer:**

*You can directly register as customer and then login.*

> *http(s)://example.com/customer/register*


### License
Bagisto is a truly opensource E-Commerce framework which will always be free under the [MIT License](https://github.com/bagisto/bagisto/blob/master/LICENSE).


### Miscellaneous

#### Contributors

This project is on [Open Collective](https://opencollective.com/bagisto) and it exists thanks to this people who contribute.

<a href="https://github.com/bagisto/bagisto/graphs/contributors"><img src="https://opencollective.com/bagisto/contributors.svg?width=890&button=false"/></a>

#### Backers

Thank you to all our backers! 🙏

<a href="https://opencollective.com/bagisto#contributors" target="_blank"><img src="https://opencollective.com/bagisto/backers.svg?width=890"></a>

#### Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website.

<a href="https://opencollective.com/bagisto/contribute/sponsor-7372/checkout" target="_blank"><img src="https://images.opencollective.com/static/images/become_sponsor.svg"></a>
