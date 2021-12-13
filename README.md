<p align="center">
    <a href="http://www.bagisto.com"><img src="https://bagisto.com/wp-content/themes/bagisto/images/logo.png" alt="Total Downloads"></a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/license.svg" alt="License"></a>
    <a href="https://github.com/bagisto/bagisto/actions"><img src="https://github.com/bagisto/bagisto/workflows/CI/badge.svg" alt="Backers on Open Collective"></a>
    <a href="#backers"><img src="https://opencollective.com/bagisto/backers/badge.svg" alt="Backers on Open Collective"></a>
    <a href="#sponsors"><img src="https://opencollective.com/bagisto/sponsors/badge.svg" alt="Sponsors on Open Collective"></a>
</p>

<p align="center">
    <a href="https://twitter.com/intent/follow?screen_name=bagistoshop"><img src="https://img.shields.io/twitter/follow/bagistoshop?style=social"></a>
    <a href="https://www.youtube.com/channel/UCbrfqnhyiDv-bb9QuZtonYQ"><img src="https://img.shields.io/youtube/channel/subscribers/UCbrfqnhyiDv-bb9QuZtonYQ?style=social"></a>
</p>

<p align="center">
    ➡️ <a href="https://bagisto.com/en/">Website</a> | <a href="https://devdocs.bagisto.com/">Documentation</a> | <a href="https://webkul.com/blog/laravel-ecommerce-website/">Installation Guide</a> | <a href="https://forums.bagisto.com/">Forums</a> | <a href="https://www.facebook.com/groups/bagisto/">Community</a> ⬅️
</p>

<p align="center" style="display: inline;">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/ar.svg" alt="Arabic" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/de.svg" alt="German" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/es.svg" alt="Spanish" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/ir.svg" alt="Persian" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/it.svg" alt="Italian" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/nl.svg" alt="Dutch" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/pl.svg" alt="Polish" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/pt.svg" alt="Portuguese" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/tr.svg" alt="Turkish" width="24" height="24">
<img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/eg.svg" alt="Egyptian" width="24" height="24">
</p>

## Topics

1. [Introduction](#introduction)
2. [Documentation](#documentation)
3. [Requirements](#requirements)
4. [Installation & Configuration](#installation-and-configuration)
5. [License](#license)
6. [Security Vulnerabilities](#security-vulnerabilities)
7. [Modules](#modules)
8. [Miscellaneous](#miscellaneous)

### Introduction

An easy to use, free and opensource laravel ecommerce platform to build your online shop in no time.

[Bagisto](https://www.bagisto.com) is a hand-tailored E-Commerce framework built on some of the hottest opensource technologies
such as [Laravel](https://laravel.com) (a [PHP](https://secure.php.net/) framework) and [Vue.js](https://vuejs.org)
a progressive Javascript framework.

**Bagisto can help you cut down your time, cost, and workforce for building online stores or migrating from physical stores
to the ever-demanding online world. Your business -- whether small or huge -- can benefit. And it's straightforward to set it up.**

**Read our documentation: [Bagisto Docs](https://devdocs.bagisto.com/)**

**We also have a forum for any concerns, feature requests, or discussions. Please visit: [Bagisto Forums](https://forums.bagisto.com/)**

# Visit our live [Demo](https://demo.bagisto.com)

It packs in lots of features that will allow your E-Commerce business to scale in no time:

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
* Simple, Configurable, Group, Bundle, Downloadable and Virtual Products.
* Price rules (Discount) inbuilt.
* Theme (Velocity).
* CMS Pages.
* Check out [these features and more](https://bagisto.com/features/).

**For Developers**:
Take advantage of two of the hottest frameworks used in this project -- Laravel and Vue.js -- both have been used in Bagisto.

### Documentation

#### Bagisto Documentation [https://devdocs.bagisto.com](https://devdocs.bagisto.com)

### Requirements

* **SERVER**: Apache 2 or NGINX.
* **RAM**: 3 GB or higher.
* **PHP**: 7.4 or higher.
* **For MySQL users**: 5.7.23 or higher.
* **For MariaDB users**: 10.2.7 or Higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

### Installation and Configuration

**1. You can install Bagisto by using the GUI installer.**

##### a. Download zip from the link below:

[Download the latest release](https://github.com/bagisto/bagisto/releases/latest)

##### b. Extract the contents of the zip and execute the project in your browser:

~~~
http(s)://example.com
~~~

**2. Or you can install Bagisto from your console.**

##### Execute these commands below, in order

~~~
1. composer create-project bagisto/bagisto
~~~

~~~
2. php artisan bagisto:install
~~~

**To execute Bagisto**:

##### On server:

Warning: Before going into production mode, we recommend you uninstall developer dependencies.
To do that, run the command below:

> composer install --no-dev

~~~
If not done, open the specified entry point in your host's file in your browser or make an entry in the host file.
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

**How to log in as a customer:**

*You can directly register as a customer and then log in.*

> *http(s)://example.com/customer/register*


### License
Bagisto is a truly open-source E-Commerce framework that will always be free under the [MIT License](https://github.com/bagisto/bagisto/blob/master/LICENSE).

### Security Vulnerabilities
Would you please not disclose security vulnerabilities publicly? If you find any security vulnerability in Bagisto, then please email us: mailto:support@bagisto.com.

### Modules
[Available Modules](https://bagisto.com/en/extensions)

Need something else? Please email us at support@bagisto.com.

### Miscellaneous

#### Contribute

Bagisto is a community-driven project; we appreciate every contribution from the community.

- If you found an issue that you think we should know about, or if you have a suggestion, please submit an issue.
- If you want to submit a solution or offer a new feature, please create a pull request.

Please read our [contributing guide](https://github.com/bagisto/bagisto/blob/master/.github/CONTRIBUTING.md) for more info.

#### Contributors

This project is on [Open Collective](https://opencollective.com/bagisto), and it exists thanks to the people who contribute.

<a href="https://github.com/bagisto/bagisto/graphs/contributors"><img src="https://opencollective.com/bagisto/contributors.svg?width=890&button=false"/></a>

#### Backers

Thank you to all our backers! 🙏

<a href="https://opencollective.com/bagisto#contributors" target="_blank"><img src="https://opencollective.com/bagisto/backers.svg?width=890"></a>

#### Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website.

<div>
    <a href="https://opencollective.com/bagisto/contribute/sponsor-7372/checkout" target="_blank">
        <img src="https://images.opencollective.com/static/images/become_sponsor.svg">
    </a>
</div>

<kbd>
    <a href="http://e.ventures/" target="_blank">
        <img src="https://images.opencollective.com/e-ventures1/7d61db2/logo.png" height="75">
    </a>
</kbd>
