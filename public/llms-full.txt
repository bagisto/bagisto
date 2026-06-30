# Bagisto Developer Documentation

> The comprehensive guide to developing with Bagisto e-commerce platform

A complete reference for developers building and extending Bagisto stores with modern Laravel, Vue.js, and Tailwind CSS technologies.

# Why Choose Bagisto?

When it comes to building a modern and scalable eCommerce store, choosing the right platform can make all the difference. Here's why [Bagisto](https://bagisto.com/) stands out as the perfect choice for your business.

## ⚡ Modern Technology Stack

Bagisto is built on the trusted [Laravel](https://laravel.com/) framework, styled with [Tailwind CSS](https://tailwindcss.com/), and powered by [Vue.js](https://vuejs.org/) for a seamless, responsive, and lightning-fast experience. This modern stack ensures your store is secure, scalable, and easy to maintain.

## 🎨 Unmatched Customization and Branding

Unlike many rigid eCommerce solutions, Bagisto gives you complete control over your store's look and feel. From themes and layouts to branding elements, everything can be customized to match your business identity. Plus, its integrated marketing tools make attracting and engaging customers much easier.

## 🔍 Built-In SEO Advantage

Bagisto comes with robust SEO features right out of the box, helping your store rank higher in search results and attract organic traffic without relying heavily on plugins or external tools.

## 💳 Flexible Payment and Shipping Integrations

Whether your customers prefer local payment gateways or international options, Bagisto has you covered. With support for multiple payment methods and seamless integration with major shipping providers, order fulfillment becomes smooth and efficient.

::: info The Bottom Line
Choosing Bagisto means choosing flexibility, performance, and long-term growth. With its modern technology, deep customization options, strong SEO foundation, and comprehensive eCommerce tools, Bagisto is more than just a platform — it's the partner your business needs to succeed online.
:::

# Before You Start

Welcome to the Bagisto development journey! This guide will help you prepare your development environment and understand the foundational knowledge needed to work effectively with Bagisto, a powerful Laravel-based e-commerce platform.

Whether you're planning to customize an existing store, build new features, or contribute to the community, having the right setup and background knowledge will set you up for success.

## Getting Ready for Bagisto

To work efficiently with Bagisto (especially the latest versions), having a basic understanding of certain concepts will help you learn faster and customize with confidence.

### 🐘 PHP Basics and Best Practices

- [Core PHP concepts](https://www.php.net/manual/en/langref.php): variables, functions, arrays, and control structures
- [Namespaces](https://www.php.net/manual/en/language.namespaces.php) and [how autoloading works](https://www.php.net/manual/en/language.oop5.autoload.php)
- [PSR-4 autoloading guidelines](https://www.php-fig.org/psr/psr-4/)
- [Object-Oriented Programming (OOP) in PHP](https://www.php.net/manual/en/language.oop5.php)
- [Using Composer to manage dependencies](https://getcomposer.org/doc/01-basic-usage.md)

### ⚡ Key Laravel Knowledge for Bagisto

- [Defining routes](https://laravel.com/docs/12.x/routing) and [building controllers](https://laravel.com/docs/12.x/controllers)
- Understanding the [Service Container](https://laravel.com/docs/12.x/container) and [Dependency Injection](https://laravel.com/docs/12.x/providers)
- [Middleware](https://laravel.com/docs/12.x/middleware) and the request-handling flow
- [Events](https://laravel.com/docs/12.x/events), [listeners](https://laravel.com/docs/12.x/events#defining-listeners), and [model observers](https://laravel.com/docs/12.x/eloquent#observers)
- Working with [Eloquent ORM](https://laravel.com/docs/12.x/eloquent) and [database migrations](https://laravel.com/docs/12.x/migrations)
- [Blade templating essentials](https://laravel.com/docs/12.x/blade)

### 🎨 Helpful Extras for Advanced Customization

- [Tailwind CSS](https://tailwindcss.com/docs/installation) for modern, responsive styling
- [Vue.js basics](https://vuejs.org/guide/introduction.html) for interactive features
- Asset bundling with [Vite](https://laravel.com/docs/12.x/vite)
- [Creating custom Laravel packages](https://laravel.com/docs/12.x/packages)

### 🔗 Learn and Connect

- [Bagisto GitHub Repository](https://github.com/bagisto/bagisto) – Browse code, report issues, and contribute
- [Bagisto Forums](https://forums.bagisto.com/) – Ask questions and join community discussions

::: tip 💡 Pro Tip
You can start with Bagisto even as a beginner, but familiarity with Laravel and Vue.js will make your development process much smoother.
:::

## System Requirements

Before diving into Bagisto development, ensure your system meets these requirements:

### 🖥️ Server Configuration

- **Server**: Apache 2 or NGINX
- **RAM**: 4GB or higher
- **Node**: 23.10.0 LTS or higher
- **PHP**: 8.2 or higher
- **Composer**: 2.5 or higher

### 🧩 PHP Extensions

- **php-intl**: Required for internationalization support
- **php-gd**: Essential for image processing and manipulation
- **Other standard Laravel extensions**: Check via `php -m` command

### ⚙️ PHP Configuration

Key settings in your `php.ini`:

```ini
memory_limit = 4G
max_execution_time = 360
date.timezone = Asia/Kolkata  # Change to your timezone
```

### 🗄️ Database

- **MySQL**: Version 8.0.32 or higher
- **Collation**: `utf8mb4_unicode_ci` (recommended)

::: tip Quick Check
Run `php -v` and `composer --version` to verify your PHP and Composer versions meet the requirements.
:::

# Installation

This guide will walk you through installing Bagisto using different methods. Choose the one that best fits your needs.

## 🚀 Quick Installation (Recommended)

The fastest way to get Bagisto up and running:

### Prerequisites

Before starting, ensure you have:
- PHP 8.3 or higher
- Composer 2.5 or higher
- MySQL 8.0.32 or higher
- Web server (Apache/Nginx)

::: tip System Requirements
If you haven't checked the system requirements yet, please review the [Before You Start](/getting-started/before-you-start#system-requirements) guide.
:::

### Step 1: Create Project

Open your terminal and run:

```bash
composer create-project bagisto/bagisto my-bagisto-store
```

### Step 2: Navigate to Directory

```bash
cd my-bagisto-store
```

### Step 3: Run Installation

```bash
php artisan bagisto:install
```

Follow the interactive prompts to configure your application, database, and admin account.

### Step 4: Start Development Server

```bash
php artisan serve
```

Your Bagisto store will be available at `http://localhost:8000`

## 🖥️ GUI Installation

If you prefer a web-based installer:

::: warning Important
Ensure Composer is installed and your web server is properly configured before proceeding.
:::

### Method 1: Using Composer

```bash
composer create-project bagisto/bagisto
```

Configure your web server's document root to the `public/` directory inside your Bagisto project (e.g., `/path/to/bagisto/public`), then visit:
```
http://localhost/
```

### Method 2: Download Package

1. [Download Bagisto](https://bagisto.com/en/download/) from the official website
2. Extract the downloaded file
3. Navigate to the project directory
4. Run:
  ```bash
  composer install
  ```
5. Configure your web server’s document root to point to the `public/` directory inside your Bagisto project (e.g., `/path/to/bagisto/public`), then open your browser and visit:
  ```
  http://localhost/
  ```

## 🔧 Manual Installation

For advanced users who want complete control over the installation process.

::: warning Important
Ensure Composer is installed and your web server is properly configured before proceeding.
:::

### Step 1: Get Bagisto

You can get Bagisto in two ways:

- **Method 1: Composer**  
  Create a new project using Composer. See [GUI Installation - Method 1](#method-1-using-composer) for step-by-step instructions.

- **Method 2: Download Package**  
  Download the package from the [official website](https://bagisto.com/en/download/) or clone the [GitHub repository](https://github.com/bagisto/bagisto). Refer to [GUI Installation - Method 2](#method-2-download-package) for details.

### Step 2: Configure Environment

1. Copy environment file and generate key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

2. Edit your `.env` file with database credentials and application settings.

### Step 3: Setup Store

::: tip Database Configuration
This step assumes your database is already created and configured in the `.env` file. Make sure your database connection details are correct before proceeding.
:::

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
```

### Step 4: Launch Store

```bash
php artisan serve
```

Visit `http://localhost:8000` to access your store.

## 🐳 Docker Installation

Perfect for containerized environments and easy deployment across different systems.

### Prerequisites

- [Docker](https://docs.docker.com/install/) installed on your system
- [Docker Compose](https://docs.docker.com/compose/install/) (for Method 2)

### Method 1: Using Docker Hub (Recommended)

The quickest way to get Bagisto running with Docker:

#### Step 1: Pull Bagisto Image

```bash
docker pull webkul/bagisto:2.3.11
```

#### Step 2: Run Container

```bash
docker run -it -d -p 80:80 webkul/bagisto:2.3.11
```

::: tip Port Configuration
If port 80 is already in use, you can use a different port:
```bash
docker run -it -d -p 8082:80 webkul/bagisto:2.3.11
```
Then access at `http://localhost:8082`
:::

#### Step 3: Access Your Store

Open your browser and visit `http://localhost`

### Method 2: Using Docker Compose

For more control and customization:

#### Step 1: Clone Repository

```bash
git clone https://github.com/bagisto/bagisto-docker.git bagisto-docker
cd bagisto-docker
```

#### Step 2: Configure Docker Compose

Edit `docker-compose.yml` to adjust ports and settings:

```yaml
version: '3.1'

services:
    bagisto-php-apache:
        build:
            args:
                container_project_path: /var/www/html/
                uid: 1000
                user: $USER
            context: .
            dockerfile: ./Dockerfile
        image: bagisto-php-apache
        ports:
            - 80:80
        volumes:
            - ./workspace/:/var/www/html/

    bagisto-mysql:
        image: mysql:8.0
        command: --default-authentication-plugin=mysql_native_password
        restart: always
        environment:
            MYSQL_ROOT_HOST: '%'
            MYSQL_ROOT_PASSWORD: root
        ports:
            - 3306:3306
        volumes:
            - ./.configs/mysql-data:/var/lib/mysql/

    bagisto-phpmyadmin:
        image: phpmyadmin:latest
        restart: always
        environment:
            PMA_HOST: bagisto-mysql
            PMA_USER: root
            PMA_PASSWORD: root
        ports:
            - 8080:80
```

#### Step 3: Launch Services

Run the following command to initialize and build the Docker containers:

```bash
sh setup.sh
```

This is a one-time setup script that prepares your environment for Bagisto. It will build the necessary Docker images, install dependencies, and configure your containers according to the Dockerfile and `docker-compose.yml`. Once completed, your services will be ready to start and you can access Bagisto through your browser.

#### Step 4: Access Services

- **Store**: `http://localhost`
- **Admin Panel**: `http://localhost/admin`
- **PHPMyAdmin**: `http://localhost:8080`

::: tip Managing Services
To stop the Docker Compose services, run:
```bash
docker compose down
```

To start them again, use:
```bash
docker compose up -d
```
:::

## ⛵ Laravel Sail Installation

Laravel Sail provides a Docker-powered development environment with pre-configured services for Bagisto.

### Prerequisites

- [Docker](https://docs.docker.com/install/) installed on your system
- [Docker Compose](https://docs.docker.com/compose/install/)

### Step 1: Get Bagisto

You can get Bagisto in two ways:

- **Method 1: Composer**  
  Create a new project using Composer. See [GUI Installation - Method 1](#method-1-using-composer) for step-by-step instructions.

- **Method 2: Download Package**  
  Download the package from the [official website](https://bagisto.com/en/download/) or clone the [GitHub repository](https://github.com/bagisto/bagisto). Refer to [GUI Installation - Method 2](#method-2-download-package) for details.

### Step 2: Install Sail

For a fresh project clone, install dependencies:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer require laravel/sail --dev --ignore-platform-reqs
```

For existing projects:
```bash
composer require laravel/sail --dev
```

### Step 3: Configure Environment

Update your `.env` file with Sail-specific configurations:

```properties
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=bagisto
DB_USERNAME=sail
DB_PASSWORD=password

# Redis Configuration
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

# Elasticsearch Configuration
ELASTICSEARCH_HOST=http://elasticsearch:9200
```

### Step 4: Build and Start Services

```bash
# Build containers
vendor/bin/sail build --no-cache

# Start services in background
vendor/bin/sail up -d
```

### Step 5: Install Bagisto

```bash
vendor/bin/sail artisan bagisto:install
```

### Step 6: Access Services

- **Store**: `http://localhost`
- **Admin Panel**: `http://localhost/admin`
- **MailPit (Email Testing)**: `http://localhost:8025`
- **Kibana (Elasticsearch UI)**: `http://localhost:5601`

::: tip Available Services
Sail includes Laravel, MySQL, Redis, Elasticsearch, Kibana, and MailPit for a complete development environment.
:::

::: tip Managing Sail
To stop services: `vendor/bin/sail down`

To restart: `vendor/bin/sail up -d`

To view logs: `vendor/bin/sail logs`
:::

## 🌐 Accessing Your Store

### Production Environment

Access your store using your domain:
```
https://yourdomain.com
```

::: tip Info
If your Apache (or Nginx) document root is properly mapped to the `public/` directory of your Bagisto project, your store will be accessible at your domain.
:::

### Development Environment

Use the built-in development server:
```bash
php artisan serve
```
Then visit: `http://localhost:8000`

### Admin Panel

Access the admin panel at:
```
https://yourdomain.com/admin
```

**Default Credentials:**
- Email: `admin@example.com`
- Password: `admin123`

::: tip Security
Change the default admin credentials immediately after installation!
:::

### Customer Registration

Customers can register at:
```
https://yourdomain.com/customer/register
```

## 📱 Mobile App Installation

Bagisto also provides a mobile application for your eCommerce store.

### Prerequisites

**Required Versions:**
- Bagisto: v2.0.0 or higher
- Android Studio: Flamingo 2022.2.1 or newer
- Flutter: 3.10.1 or higher
- Dart: 3.0.1 or higher
- Xcode: 14.3 or newer (for iOS)
- Swift: 5 or higher

**Minimum Device Support:**
- Android: API level 21+
- iOS: 12.0+

::: tip Before You Start
Make sure you can run a simple "Hello World" Flutter app first to verify your development environment is properly configured.
:::

### Clone the repository

- Open your terminal or command prompt
- Navigate to the directory where you want to save the project
- Use the git clone command followed by the repository URL

```bash
git clone https://github.com/bagisto/opensource-ecommerce-mobile-app.git
```

### Install dependencies

- Navigate to the project's directory

```bash
cd <repository-name>
```
  
- Run the following command to install the required packages

```bash
flutter pub get
```

### Generate Required files

- Navigate to the project's directory

```bash
cd <repository-name>
```

- Run the following command to generate the required files

```bash
flutter pub run build_runner build --delete-conflicting-outputs 
```

### Connect a device or emulator

* Physical Device

  1. Enable USB debugging on your device
  2. Connect it to your computer using a USB cable.

* Emulator

  1. Start an Android or iOS emulator using your preferred IDE or tools.

### Run the Project

- Use the following command to build and run the project

```bash
flutter run
```
### Minimum Versions

- Android: 21
- iOS: 12

### Configurations Steps

### For Setup

Change the baseUrl  as per your store

**Path:** lib/utils/server_configuration.dart

```bash
static const String baseUrl = ‘....’;
```

:::tip Note
Add the value of the complete URL ending with the GraphQL API endpoint. E.g - https://example.com/graphql 
:::

### For Theme

Change the Theme for your app

**Path:** lib/utils/mobikul_theme.dart

```bash
static const Color primaryColor = Color(***********);  
static const Color accentColor = Color(***********); 
```

### For Push Notification Service

- Android 

Replace "google-services.json".
- iOS 

Replace "GoogleService-Info.plist".

:::tip Note
Helpful Articles

Android  → https://mobikul.com/knowledgebase/generating-google-service-file-enable-fcm-firebase-cloud-messaging-android-application/

iOS → https://mobikul.com/knowledgebase/generating-new-googleservice-info-plist-file-fcm-based-project-ios-app/
:::

### For Application Title

* Android

  1. **Path:** android/app/src/main/AndroidManifest.xml
  2. **Change app name:** android:label="***********"

* iOS

  1. Go to the general tab and identity change the display name to your app name
 
:::tip Note 
For Homepage Header Title - Go to ‘assets/language/en.json’
(Note: Here, “en” in en.json refers to the languages that would be supported within the application)
:::

### For Splash Screen

* For adding Lottie as Splash Screen

  1. **Path:** assets/lottie/splash_screen.json
  2. After updating the Lottie file, update the ‘splashLottie’ in lib/utils/assets_constants.

```bash
 static const String splashLottie = "assets/lottie/splash_screen.json";
```
 
* For adding an Image as a Splash Screen

  1. **Path:** assets/images/splash.png
  2. After updating the Image file, update the ‘splashImage’ in lib/utils/assets_constants.

```bash
  static const String splashImage = "assets/images/splash.png";
```
### For App Icon

* **Android:** Open the android folder in Android Studio and then right click app > new > Image Asset set Image.
* **iOS:** Replace the icons over the path > ios/Runner/Assets.xcassets/AppIcon.appiconset

# 🔄 Upgrade Guide

Keep your Bagisto installation up-to-date with the latest features and security improvements.

::: warning Before You Start
Always backup your database and files before upgrading. Test the upgrade process in a staging environment first.
:::

## 📋 Prerequisites

Before upgrading, ensure you have:

- **Database Backup** - Complete backup of your current database
- **File Backup** - Backup of customizations and uploaded files
- **Server Requirements** - Check if your server meets the new version requirements
- **Downtime Planning** - Schedule maintenance window for the upgrade

## 🚀 Upgrade Process

### Step 1: Download Latest Version

Download the latest version of Bagisto from one of the following links:
- [Download From Official Bagisto Site](https://bagisto.com/en/download/)
- [Download From GitHub](https://github.com/bagisto/bagisto)

Get the latest Bagisto release:

::: code-group
```bash [GitHub (Recommended)]
# Clone the latest release
git clone https://github.com/bagisto/bagisto.git bagisto-new
cd bagisto-new
git checkout v2.4.0  # Replace with latest version
```
:::

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

1. **Copy your existing environment file:**

::: tip Safer .env Migration
Instead of copying the entire `.env` file, consider adding environment variables one by one. The latest version may introduce new variables or deprecate old ones. At this stage, you mainly need to set up database connection details and essential configuration. Review the sample `.env.example` for new options.
:::

```bash
cp /path/to/old-project/.env .env
```

2. **Update environment variables:**
```bash
# Generate new application key if needed
php artisan key:generate

# Review and update any new configuration options
nano .env
```

::: tip Environment Updates
Check the [CHANGELOG.md](https://github.com/bagisto/bagisto/blob/2.3/CHANGELOG.md) for any new environment variables that need to be added.
:::

### Step 4: Database Migration

::: danger Critical Step
Always backup your database before running migrations!
:::

```bash
# Run database migrations
php artisan migrate

# Run optimize clear
php artisan optimize:clear
```

::: warning Seeder Caution
Avoid using `php artisan db:seed` on existing installations as it may reset your settings and categories. Add default settings manually if needed.
:::

### Step 5: Storage and Assets

```bash
# Create storage link
php artisan storage:link
```

### Step 6: File Migration

Copy your existing files to the new installation:

```bash
# Copy uploaded files
cp -r /path/to/old-project/storage/app/public/* storage/app/public/

# Copy any custom assets
cp -r /path/to/old-project/public/storage/* public/storage/

# Copy custom themes (if any)
cp -r /path/to/old-project/packages/* packages/
```

::: info File Locations
If you've changed default storage paths or have custom file locations, ensure you copy those as well.
:::

### Step 7: Final Optimization

```bash
# Clear all the cache
php artisan optimize:clear
```

## 📚 Version-Specific Guides

### Upgrading from v2.3 to v2.4

For detailed breaking changes and migration steps, refer to:
- [Official UPGRADE.md](https://github.com/bagisto/bagisto/blob/2.4/UPGRADE.md)
- [CHANGELOG.md](https://github.com/bagisto/bagisto/blob/2.4/CHANGELOG.md)

### Upgrading from v2.2 to v2.3

For detailed breaking changes and migration steps, refer to:
- [Official UPGRADE.md](https://github.com/bagisto/bagisto/blob/2.3/UPGRADE.md)
- [CHANGELOG.md](https://github.com/bagisto/bagisto/blob/2.3/CHANGELOG.md)

### Upgrading from v2.1 to v2.2

For detailed breaking changes and migration steps, refer to:
- [Official UPGRADE.md](https://github.com/bagisto/bagisto/blob/2.2/UPGRADE.md)
- [CHANGELOG.md](https://github.com/bagisto/bagisto/blob/2.2/CHANGELOG.md)

# 🤝 Contribution Guide

Welcome to the Bagisto community! We appreciate your interest in contributing to our open-source e-commerce platform.

## 🐛 Bug Reports

We highly value active collaboration among our community members to continually enhance Bagisto's performance and reliability.

### Reporting Guidelines

When filing a bug report, please include:

- **Clear Title**: Descriptive and specific
- **Detailed Description**: Explain the problem thoroughly  
- **Reproduction Steps**: Step-by-step instructions
- **Code Sample**: Minimal code that reproduces the issue
- **Environment Details**: OS, PHP version, Bagisto version

## 💡 Feature Requests

We welcome proposals for new features and enhancements!

### Feature Proposal Process

1. **Check Existing Issues**: Search for similar requests
2. **Create Detailed Proposal**: Include use cases and implementation ideas
3. **Discuss with Community**: Engage in issue discussions
4. **Submit Implementation**: Provide code contribution

## 🌿 Branch Selection

Choose the appropriate branch for your contribution:

::: code-group
```bash [Bug Fixes]
# For general bug fixes (stable branch)
git checkout v2.3
git checkout -b fix/issue-description
```

```bash [Critical Fixes]
# For critical bugs in stable version
git checkout v2.3
git checkout -b hotfix/critical-issue
```

```bash [Breaking Changes]
# For new features and breaking changes
git checkout master
git checkout -b feature/new-functionality
```
:::

### Branch Guidelines

| Type | Target Branch | Description |
|------|--------------|-------------|
| 🐛 **Bug Fixes** | `v2.3` (stable) | General bug fixes for stable release |
| 🚨 **Critical Fixes** | `v2.3` (stable) | Security or critical issues |
| ✨ **Breaking Changes** | `master` | New features with potential breaking changes |

## 🎨 Styling Guidelines

### Tailwind CSS Class Ordering

Maintain consistency in Tailwind CSS class organization following [Tailwind's official sorting guidelines](https://tailwindcss.com/blog/automatic-class-sorting-with-prettier#how-classes-are-sorted):

**Recommended Class Order**:

```html
<!-- Layout → Flexbox → Spacing → Sizing → Typography → Visual → Misc -->
<div class="flex flex-col justify-center items-center p-4 w-full h-screen text-lg font-bold bg-white border rounded-lg shadow-md hover:shadow-lg">
  Content here
</div>
```

::: info Class Sorting Reference
For detailed information about how classes should be sorted, refer to the [official Tailwind CSS class sorting guide](https://tailwindcss.com/blog/automatic-class-sorting-with-prettier#how-classes-are-sorted).
:::

## 🧪 Testing

### Running Tests

Ensure all tests pass before submitting your pull request:

**Pest Tests (Unit/Feature Tests):**

To run Pest tests, navigate to your project's root directory and use the following commands:

```bash
# Run the full test suite
php artisan test

# Run specific test files
php artisan test tests/Feature/YourTestFile.php

# Run tests with coverage
php artisan test --coverage
```

**Playwright Tests (End-to-End Tests):**

To run Playwright tests, navigate to the root directory of either the `Shop` or `Admin` package, then execute:

```bash
# Run Playwright tests from the package root
npx playwright test --config=tests/e2e-pw/playwright.config.ts
```

This command will execute all end-to-end tests defined in the Playwright configuration for the selected package.

::: tip Prerequisite
Before running Playwright tests, ensure you have both `npx` and `playwright` installed in your development environment.
:::

**Pint Tests (Code Formatting):**

Pint is a PHP code style fixer that helps maintain consistent formatting across the codebase. It automatically applies standards like PSR-2 and PSR-12, making your code cleaner and easier to review. Before committing, run Pint to ensure your changes follow the project's coding standards.

```bash
# Check code formatting
vendor/bin/pint --test

# Fix code formatting
vendor/bin/pint
```

::: warning Important
All three test types (Pest, Playwright, and Pint) must pass before your PR can be merged. Run tests locally to avoid CI failures.
:::

## 📝 Coding Standards

Bagisto follows established PHP standards for consistency and readability:

### Standards We Follow
- **[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - Coding Style Guide
- **[PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)** - Autoloading Standard

### PHPDoc Example

```php
/**
 * Register a service with CoreServiceProvider.
 *
 * @param  string|array  $loader
 * @param  \Closure|string|null  $concrete
 * @param  bool  $shared
 * @return void
 */
protected function registerFacades($loader, $concrete = null, $shared = false): void
{
    // Implementation here
}
```

::: tip Automatic Formatting
You can use Pint to automatically format your code according to these standards:
```bash
vendor/bin/pint
```
:::

## 🔒 Security Vulnerabilities

::: danger Security Issues
If you discover a security vulnerability, **DO NOT** create a public issue.
:::

**Contact**: Email [support@bagisto.com](mailto:support@bagisto.com) immediately

### Security Report Should Include:
- Detailed description of the vulnerability
- Steps to reproduce the issue
- Potential impact assessment
- Suggested fix (if available)

Thank you for contributing to Bagisto! 🎉

# 🔒 Best Security Practices

Securing your Bagisto installation is critical to protecting your business, customer data, and reputation. This guide outlines essential security practices you should follow to minimize vulnerabilities and defend against common threats. By proactively implementing these measures, you can significantly reduce the risk of unauthorized access and data breaches.

## 🔄 Software Updates

To ensure the security of your system, follow these best practices:

- Use HTTPS to encrypt communication. Google now considers HTTPS as a ranking factor.
- Keep all software on the server up-to-date, including Bagisto, the database, Adminer/phpMyAdmin, Apache, Redis, etc.
- Regularly update the server operating system to apply available security patches.
- Manage files only through secure communication protocols like SSH, SFTP, or HTTPS. Disable FTP.
- Use the **`.htaccess`** file to protect system files when using the Apache web server.
- Disable unused ports and stop unnecessary services running on the server.
- Restrict access to the admin panel by allowing only specific IP addresses and enforcing two-factor authentication for admin logins.
- Ensure the use of strong and unique passwords.
- Configure and update the firewall properly to secure the connection between payment card data and the public network.

## 🚫 Limiting Error Messages

To limit the exposure of sensitive information in error messages, follow these steps:

- Edit your Apache configuration file to avoid displaying server and operating system details.
- Set **`ServerSignature`** to **`Off`** (by default, it is **`On`**).
- Add **`ServerTokens Prod`** to display only Apache as the product.

## 🔐 Limiting Admin Access

To restrict access to the admin area, modify the **`.htaccess`** file with the following code:

```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} .*/admin
RewriteCond %{REMOTE_ADDR} !=<IP address>
RewriteCond %{REMOTE_ADDR} !=<IP address>
RewriteRule ^(.*)$ - [R=403,L]
```

Ensure that there are no accessible development leftovers on the server, such as "log files," ".git directories," "database dumps," or "zip files."

## 📁 Restricting Unnecessary Files

To restrict access to unnecessary files, add the following code to your **`.htaccess`** file:

```apache
<FilesMatch "\.(git|zip|tar|sql)$">
    Require all denied
</FilesMatch>
```

Consider using a Web Application Firewall (WAF) to analyze traffic and detect suspicious patterns, such as credit card information being sent to attackers. Additionally, restrict public access to only ports 80 (HTTP) and 443 (HTTPS), while blocking other ports.

## 🚫 Restricting PHP Execution in Storage

To restrict PHP execution inside the storage directory, modify your Apache configuration file:

```apache
<Directory "~/www/bagisto/public/storage/">
    <FilesMatch "\.php$">
        Require all denied
    </FilesMatch>
    php_flag engine off
</Directory>
```

Don't forget to restart Apache after making these changes.

## 🛡️ Server Hardening

Take the following measures to harden your server:

- Use the **`mod_security`** module to detect and prevent intrusions.
- Implement the **`mod_passive`** module to prevent brute force attacks.
- Allow only specific users to log in.
- Disable login for users with empty passwords.
- Review and configure iptable rules to prevent unauthorized access and activity.
- Regularly back up important files and store them remotely in a secure environment.

## 🔑 Strong Passwords

Ensure the use of strong and unique passwords and encourage periodic password changes. You can use a password generator tool ([Password Generator](https://passwords-generator.org/)) to create strong passwords. Limit access to the Bagisto admin panel by updating the whitelist with authorized IP addresses.

## 🌐 Implementation of HTTP Security Headers

Implementing the following HTTP security headers enhances web security:

### 🔒 HTTP Strict Transport Security (HSTS)

Set the **`Strict-Transport-Security`** response header to instruct the browser to access the application only using HTTPS:

```
Strict-Transport-Security: max-age=<expire-time>
```

### ⚔️ Cross-Site Scripting Protection (X-XSS-Protection)

Set the **`X-XSS-Protection`** response header to enable browsers to detect and prevent cross-site scripting (XSS) attacks:

```
X-XSS-Protection: 1; mode=block
```

### 🖼️ X-Frame-Options

The **`X-Frame-Options`** response header protects applications against clickjacking attacks. It specifies whether the content can be displayed within frames:

```
X-Frame-Options: deny
```

### 📄 X-Content-Type-Options

The **`X-Content-Type-Options`** response header forces the browser to disable MIME sniffing, preventing MIME sniffing vulnerabilities:

```
X-Content-Type-Options: nosniff
```

### 🛡️ Content Security Policy (CSP)

Implement a Content Security Policy (CSP) response header to control which resources can be loaded in users' browsers. CSP helps detect and mitigate attacks such as XSS and clickjacking.

### 📊 Continuous Logging and Monitoring

Maintain continuous logging and monitoring of all network access and cardholder data activities. Keep an eye out for large volume orders of a single item from new customers, or a series of orders shipped to the same address but using different payment methods.

By implementing these best security practices, you can enhance the security of your system and protect it from potential threats.

# 🚀 Deployment

If you are deploying your Bagisto application to a server that is running Nginx, you may use the following configuration file as a starting point for configuring your web server. Most likely, this file will need to be customized depending on your server's configuration.

Please ensure, like the configuration below, your web server directs all requests to your application's `public/index.php` file. You should never attempt to move the `index.php` file to your project's root, as serving the application from the project root will expose many sensitive configuration files to the public Internet.

## 🌐 Nginx

Below is a sample Nginx configuration for Bagisto. This configuration should be placed in your web server's configuration file:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /srv/example.com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~* ^\/(?!cache).*\.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc|webp|woff|woff2)$ {
      expires max;
      access_log off;
      add_header Cache-Control "public";
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔄 Apache (Alternative)

To serve Bagisto using Apache, make sure your virtual host is properly configured. Below is a basic VirtualHost example suitable for local development:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/bagisto/public

    <Directory /var/www/html/bagisto/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

::: warning Mod Rewrite Required
Make sure you have the `mod_rewrite` module enabled in Apache. You can enable it using:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```
:::

## ⚡ Optimization

Before deploying your application, make sure to run the following optimization commands:

```shell
php artisan optimize
```

For additional optimization and security configurations, you may want to consider:

- Enabling SSL/TLS encryption
- Implementing proper firewall rules
- Setting up monitoring and logging
- Configuring caching mechanisms

::: tip Performance
For better performance in production, consider enabling OPcache and configuring your PHP-FPM settings appropriately.
:::

# AI in Bagisto

Bagisto ships with AI built in — both inside your store and inside your development workflow. This section is the home for everything AI-related: store features powered by AI, and tools that make the AI agents you build with smarter about Bagisto.

## For your store

- Magic AI — Generate product content and images, image search, review translation, and personalised checkout messages, powered by your choice of AI provider.

## For developers & AI agents

- Agent Skills — Pre-built skills that teach AI coding agents (Claude Code, Cursor, Windsurf) Bagisto's conventions so they generate correct packages, payment methods, and themes.
- llms.txt — Context files that help LLMs and AI tools understand Bagisto's architecture for more accurate, framework-aware suggestions.

## Magic AI — built into your store

Magic AI is a first-class Bagisto feature. Add an API key for any supported provider and you can:

- Generate content — product descriptions, page copy, and more from the admin editor.
- Generate images — create product imagery from a text prompt.
- Image search — let shoppers search your catalog by uploading a photo.
- Translate reviews — show customer reviews in the shopper's language.
- Checkout messages — personalised order confirmation messages.

Magic AI works with OpenAI, Anthropic, Gemini, Groq, xAI, DeepSeek, Mistral, and Ollama — pick a different model per feature, all from the admin panel.

## AI for developers

Bagisto also helps the AI tools you code with:

- Agent Skills — install bagisto/agent-skills so your AI agent generates Bagisto-correct packages, payment methods, themes, and tests.
- llms.txt — point Copilot, Cursor, ChatGPT, or Claude at Bagisto's context files for architecture-aware answers.

# Magic AI

Magic AI is Bagisto's built-in AI engine. It powers content and image generation in the
admin panel and AI features on the storefront, using whichever AI provider you configure.

It is built on the [Laravel AI](https://laravel.com/docs/ai) SDK, so a single configuration
works across every supported provider.

## Supported Providers

Magic AI supports eight providers out of the box:

**OpenAI** · **Anthropic** · **Gemini** · **Groq** · **xAI** · **DeepSeek** · **Mistral** · **Ollama**

You pick the **model per feature**, so you can mix providers — for example, OpenAI for text and Gemini for images.

::: tip Run models locally
Ollama lets you run open-source models on your own server with no per-request cost. Set its
URL (default `http://localhost:11434`) in the provider settings.
:::

## Configuration

Everything is configured from the admin panel under **Configure → Magic AI**. There are four groups:

### 1. General

Enable or disable Magic AI globally.

### 2. Providers

Add the **API key** for each provider you want to use (Ollama also takes a base **URL**).
You only need to configure the providers you actually plan to use.

### 3. Admin Features

| Feature | Description |
|---|---|
| **Text Generation** | AI writing assistant in admin editors (product descriptions, CMS pages, etc.). |
| **Image Generation** | Create images from a text prompt inside the admin. |

For each, toggle it on and choose which **providers** are available to admins.

### 4. Storefront Features

These are **channel-based** — configure them per store channel, each with its own model:

| Feature | Description |
|---|---|
| **Image Search** | Shoppers upload a photo; Magic AI extracts keywords to search the catalog. |
| **Review Translation** | Customer reviews are translated into the shopper's locale. |
| **Checkout Message** | A personalised success message is generated after an order is placed. |

::: warning API keys
Provider API keys are stored as secure (password) config values. Never commit them to your
repository — add them through the admin panel.
:::

## Using Magic AI in Code

Magic AI is available through the `magic_ai()` helper or the `Webkul\MagicAI\Facades\MagicAI`
facade. The provider is resolved automatically from the model name, and the stored API key is
injected for you.

```php
use Webkul\MagicAI\Facades\MagicAI;

// Generate text (uses the configured default model when none is passed)
$text = magic_ai()->generateContent('Write a product description for a leather wallet.');

// Generate text with a specific model
$text = MagicAI::generateContent('Summarize this policy in one line.', 'gpt-4o');
```

### Generating Images

```php
$images = magic_ai()->generateImage('A minimalist running shoe on a white background', [
    'n'       => 1,           // number of images
    'size'    => '1:1',       // 1:1 (square), 3:2 (landscape), 2:3 (portrait)
    'quality' => 'high',      // high, medium, low
]);

// Each item is a data URL: ['url' => 'data:image/png;base64,...']
$src = $images[0]['url'];
```

### Storefront Helpers

These read the model from your channel's storefront configuration automatically:

```php
// Analyze an uploaded image → comma-separated search keywords
$keywords = magic_ai()->analyzeImage($absoluteImagePath);

// Translate any text into a locale
$translated = magic_ai()->translate($review->comment, 'fr');

// Build a personalised checkout success message for an order
$message = magic_ai()->checkoutMessage($order);
```

### Reading Config in Code

All settings are readable via `core()->getConfigData()`:

```php
if (core()->getConfigData('magic_ai.general.settings.enabled')) {
    $model = core()->getConfigData('magic_ai.storefront_features.review_translation.model');
}
```

::: tip Extending Magic AI
Magic AI lives in the `packages/Webkul/MagicAI` package. Provider support is driven by a single
registry (`AiProvider`) plus a model enum per provider — adding a provider means adding one
registry entry and one enum.
:::


# Agent Skills

`bagisto/agent-skills` is a collection of Bagisto-specific skills for AI coding agents like
**Claude Code, Cursor, and Windsurf**. They teach your agent Bagisto's conventions so it
generates correct packages, payment methods, themes, and tests.

## Install

Install every skill:

```bash
npx skills add bagisto/agent-skills
```

Or install just the one you need:

```bash
npx skills add bagisto/agent-skills --skill "package-development"
```

## Available Skills

| Skill | Use it for |
|---|---|
| `package-development` | Creating and structuring Bagisto packages |
| `shipping-method-development` | Building custom shipping methods |
| `payment-method-development` | Building payment gateways |
| `product-type-development` | Creating custom product types |
| `shop-theme-development` | Storefront theme development |
| `admin-theme-development` | Admin theme development |
| `pest-testing` | Writing Pest tests with the right patterns |

Swap the `--skill` value for any name above to install it individually.

## Supported Tools

Claude Code · Cursor · Windsurf · any agent that supports the `skills` CLI.

::: tip
Install only the skills relevant to your work, and keep them updated alongside your Bagisto
version. See also [llms.txt](/ai/llms-txt) for broader AI context.
:::


# llms.txt

Bagisto publishes `llms.txt` files that describe its architecture and conventions in a
format AI tools understand. Pointing tools like GitHub Copilot, Cursor, ChatGPT, or Claude
at these files gives you more accurate, framework-aware suggestions.

## Two Context Files

| File | Content | Best for |
|---|---|---|
| [`llms.txt`](/llms.txt) | Core concepts, keywords, naming patterns | Quick setup, general assistance |
| [`llms-full.txt`](/llms-full.txt) | Detailed architecture and components | Complex, custom development |

Start with `llms.txt`; use `llms-full.txt` when you need deeper context.

## Setup

Download the files to your project root:

```bash
curl -O https://devdocs.bagisto.com/llms.txt
curl -O https://devdocs.bagisto.com/llms-full.txt
```

Then point your AI tool at them:

- **GitHub Copilot** — detects the files automatically.
- **Cursor** — reference with `@Docs llms.txt`.
- **ChatGPT / Claude** — upload or paste the file in your conversation.

::: tip For custom packages
Drop a project- or package-specific `llms.txt` alongside your code so agents pick up your own
conventions too. For task-specific guidance, pair this with [Agent Skills](/ai/agent-skills).
:::


# Architecture Overview

This document provides a comprehensive overview of Bagisto's architecture and core principles, designed to help developers understand the framework's structure and implementation approach.

## Technology Stack

Bagisto is built on a modern, robust technology stack leveraging proven [Open Source](https://en.wikipedia.org/wiki/Open_source) technologies:

- **[PHP](https://php.net)** - Server-side programming language
- **[Laravel](https://laravel.com)** - PHP framework for web application development
- **[Vue.js](https://vuejs.org/)** - Progressive JavaScript framework for user interfaces
- **[Tailwind CSS](https://tailwindcss.com/)** - Utility-first CSS framework for styling

## Core Architecture Principles

### Dual Interface Design

Bagisto provides a comprehensive e-commerce solution with two primary interfaces:

- **Customer Frontend** - Public-facing storefront for customer interactions
- **Administrative Backend** - Management interface for store administration and configuration

### Modular Package Structure

The framework follows a modular architecture where each core functionality is encapsulated in dedicated Laravel packages, providing a clean separation of concerns and enabling easy customization and extension.

### Component-Based Frontend

Bagisto utilizes Vue.js built-in components to create:

- Reusable UI elements
- Interactive user interfaces
- Dynamic content rendering
- Seamless user experience

### Event-Driven Architecture

The framework implements a comprehensive event system that:

- Triggers events across application lifecycle
- Enables custom functionality through event listeners
- Provides hooks for third-party integrations
- Supports extensibility without core modifications

# Bagisto Backend Overview

The Bagisto backend follows a modular, package-based architecture that promotes scalability, maintainability, and extensibility. Each functional area is organized into dedicated packages, allowing developers to work with specific components independently while maintaining seamless integration across the entire system.

Built on top of Laravel's robust foundation, Bagisto leverages the **Prettus L5 Repository** pattern to provide a clean abstraction layer between the application logic and data access operations. This repository pattern implementation ensures consistent data handling across all packages while maintaining code quality and testability standards.

## Modular Design in Bagisto

Bagisto is built on a modular architecture that enhances flexibility, scalability, and maintainability. This design philosophy allows developers to manage and extend the application efficiently by organizing functionality into discrete, well-structured packages.

### Key Benefits of Modular Design

- **Separation of Concerns**: Each module encapsulates specific functionality, creating clear boundaries between different application components
- **Reusability**: Modules can be leveraged across multiple projects, reducing development time and effort duplication
- **Maintainability**: Isolated modules simplify bug identification, debugging, and feature implementation without impacting unrelated components
- **Scalability**: New modules can be added seamlessly without requiring major modifications to the existing codebase

### Module Structure in Bagisto

Every Bagisto module follows a standardized structure that ensures consistency and simplifies management. A typical module includes:

```
Module/src/
├── Config/
│   ├── admin-menu.php
│   └── system.php
├── Database/
│   ├── Migrations/
│   │   └── create-module-tables.php
│   ├── Seeders/
│   │   └── ModuleSeeder.php
│   └── Factories/
│       └── ModuleFactory.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── ModuleController.php
│   │   └── Shop/
│   │       └── ModuleController.php
│   ├── Middleware/
│   │   └── ModuleMiddleware.php
│   └── Requests/
│       └── ModuleRequest.php
├── Models/
│   ├── Module.php
│   └── ModuleProxy.php
├── Repositories/
│   └── ModuleRepository.php
├── Resources/
│   ├── views/
│   │   ├── admin/
│   │   │   └── index.blade.php
│   │   └── shop/
│   │       └── index.blade.php
│   ├── lang/
│   │   └── en/
│   │       └── app.php
│   └── assets/
│       ├── css/
│       └── js/
├── Routes/
│   ├── admin-routes.php
│   └── shop-routes.php
└── Tests/
    ├── Unit/
    │   └── ModuleTest.php
    └── Feature/
        └── ModuleFeatureTest.php
```

**Key Components:**

- **Config**: Module-specific configuration files for admin menus, system settings, and module definitions
- **Database**: Migrations for schema changes, seeders for sample data, and factories for testing data generation
- **Http**: Controllers for admin and shop interfaces, middleware for request processing, and request validation classes
- **Models**: Eloquent models defining data structures and relationships with proxy models for extensibility
- **Repositories**: Repository pattern implementation providing abstraction layer for data access operations
- **Resources**: Views for frontend presentation, language files for internationalization, and static assets
- **Routes**: Separate routing files for admin and shop functionalities to maintain clear separation
- **Tests**: Comprehensive unit tests for individual components and feature tests for complete workflows

This modular approach enables developers to build robust, maintainable applications that are easy to extend and manage while following established architectural principles.

## Repository Pattern in Bagisto

Bagisto employs the **Repository Pattern** to further enhance the flexibility and maintainability of its codebase, adding an additional layer of abstraction on top of Laravel's Eloquent ORM to promote better code organization and consistency.

### Benefits of the Repository Pattern

- **Consistency**: Restricts the use of raw queries throughout the application, ensuring a standardized approach to database operations across all modules
- **Maintainability**: Enhances code organization by centralizing data access logic, making it easier to manage and maintain complex database operations
- **Flexibility**: Facilitates the implementation of changes without affecting the rest of the codebase, allowing for easier testing and modification
- **Testability**: Enables better unit testing by providing mockable interfaces for data access operations

### Implementation in Bagisto

Bagisto utilizes the [Prettus Repository](https://github.com/prettus/l5-repository) package to facilitate the implementation of the **Repository Pattern**. This choice provides several benefits:

- **Standardization**: Ensures a standardized approach to repository implementation across all packages
- **Extensibility**: Makes it easier to extend and customize the application as needed without modifying core functionality
- **Separation of Concerns**: Promotes a clear separation between business logic and data access logic
- **Query Optimization**: Provides built-in features for caching, criteria-based filtering, and query optimization

### Repository Structure

Each repository in Bagisto follows a consistent structure with:

- **Repository Contract**: Defines the interface that repositories must implement
- **Repository Implementation**: Contains the actual data access logic and business rules
- **Model Integration**: Works seamlessly with Eloquent models to provide clean data operations

By adopting the **Repository Pattern** with the Prettus Repository package, Bagisto enhances the overall architecture of the application, making it more robust, testable, and easier to evolve over time.

## Available Packages In Bagisto

Bagisto comes with a comprehensive collection of packages that demonstrate the power of its modular architecture and repository pattern implementation. Each package follows the same standardized structure and design principles, allowing developers to easily understand, extend, and customize functionality across the entire platform.

Laravel packages are the primary way of adding functionality. The following features are distributed into packages to enhance the application and allow developers to follow the standard way of developing custom functionality.

Below is a detailed overview of the default packages available in Bagisto, each showcasing how the modular design and repository pattern work together to create a robust, scalable e-commerce solution:

### Admin

The Admin package in Bagisto is a core component that provides the administrative interface and functionality for managing various aspects of an online store. It offers a comprehensive dashboard and a set of tools for administrators to efficiently manage products, orders, customers, configurations, and other essential elements of the store. Here's a detailed overview of the Admin package in Bagisto:

#### Key Features of the Admin Package

- **Dashboard**
  - Provides a summary of the store's performance with key metrics and analytics
  - Displays widgets for quick insights into sales, orders, customers, and other important data

- **Product Management**
  - Allows administrators to add, edit, and delete products
  - Supports the management of product attributes, categories, and inventories
  - Facilitates the creation of configurable, downloadable, and bundled products

- **Order Management**
  - Enables the management of customer orders, including viewing, updating, and canceling orders
  - Provides functionalities to manage order status, shipments, and invoices

- **Customer Management**
  - Allows administrators to manage customer accounts and their details
  - Facilitates the management of customer groups and segmentation for targeted marketing

- **Configuration and Settings**
  - Provides a wide range of configuration options to customize the store's behavior
  - Includes settings for payment methods, shipping methods, tax rates, and locales
  - Allows customization of email templates and other communication settings

- **CMS Management**
  - Facilitates the management of static pages, blocks, and sliders to enhance the store's frontend
  - Allows for the creation and management of content-rich pages without the need for coding

- **Marketing and Promotions**
  - Offers tools to create and manage promotions, discounts, and coupons
  - Provides functionalities to set up cart price rules and catalog price rules

- **Reports and Analytics**
  - Provides detailed reports on sales, customer activities, and product performance
  - Offers insights into store performance through various graphical representations and data export options

### Attribute

All the logic related to attributes is available in this package, which manages product attributes and attribute sets, allowing you to define and organize product information effectively.

The Attribute package in Bagisto manages product attributes and attribute sets, enabling you to define and organize product information effectively. This package is crucial for customizing product data, enhancing search capabilities, and improving product filtering and categorization.

#### Key Features of the Attribute Package

- **Attribute Management**
  - Create, edit, and delete product attributes
  - Define various attribute types, such as text, textarea, select, multiselect, date, price, and boolean
  - Set validation rules for attributes to ensure data consistency

- **Attribute Options**
  - Manage option values and their sorting order

- **Attribute Family**
  - Configure attribute families to group related attributes
  - Allow products to inherit attributes from attribute families, ensuring consistent product data structure

### BookingProduct

The Booking Product Package in Bagisto extends the platform’s capabilities by allowing merchants to offer products and services that require scheduling and reservations. This package is designed to handle various booking scenarios, including appointments, rentals, events, and more. It provides a flexible and seamless booking experience for both merchants and customers.

#### Key Features of the BookingProduct Package

- **Multiple Booking Types**
  - **Appointment Booking** – Ideal for doctors, salons, and consultancy services
  - **Event Booking** – Suitable for ticket-based bookings like concerts and conferences
  - **Rental Booking** – Used for vehicle rentals, equipment rentals, and room bookings
  - **Table Booking** – Supports restaurant reservations and seating arrangements

- **Date & Time Management**
  - Define available booking dates and times
  - Set time slots with custom intervals
  - Manage booking duration and buffer times between slots

- **Availability & Capacity Control**
  - Set maximum bookings per slot
  - Configure booking restrictions to prevent overbooking
  - Allow or restrict same-day bookings

- **Customer-Friendly Booking Experience**
  - Interactive date and time picker for seamless selection
  - Booking summary displayed before checkout
  - Email notifications and reminders for customers and admin

- **Admin Control & Order Management**
  - Manage bookings from the admin panel
  - Approve, cancel, or reschedule bookings
  - Export booking data for reporting and analysis

### CMS

The CMS package in Bagisto empowers store administrators to manage content pages and blocks efficiently, facilitating the creation and maintenance of static content for your e-commerce store.

- Allows creation, editing, and deletion of static pages such as About Us, Contact Us, FAQs, etc.
- Supports custom URL slugs for pages to improve SEO and user-friendly navigation

### CartRule

The CartRule package in Bagisto provides all the necessary logic to define conditions and actions for cart-based promotions, enabling you to offer dynamic and targeted discounts to your customers. This package allows you to create flexible discount rules that can be applied to the shopping cart, enhancing your promotional capabilities and driving sales.

#### Key Features of the CartRule Package

- **Cart Rule Management**
  - Create, edit, and delete cart rules
  - Define conditions and actions for each cart rule
  - Set start and end dates for the promotion period
  - Enable or disable cart rules as needed

- **Conditions**
  - Define conditions based on cart attributes, such as subtotal, total items quantity, shipping method, and payment method
  - Combine multiple conditions using logical operators (AND, OR) to create complex rules

- **Coupons**
  - Create and manage coupon codes associated with cart rules
  - Set usage limits for each coupon (per customer, total usage)
  - Generate unique coupon codes automatically

- **Validation and Enforcement**
  - Ensure that cart rules are validated and applied correctly based on defined conditions
  - Enforce the rules during the checkout process to provide accurate discounts

### CatalogRule

The CatalogRule package in Bagisto provides the logic to define conditions and actions for catalog-based promotions, allowing you to offer dynamic pricing adjustments and discounts on individual products or categories. This package enables you to create flexible pricing rules that can be applied directly to products in the catalog, enhancing your promotional capabilities and optimizing pricing strategies.

#### Key Features of the CatalogRule Package

- **Catalog Rule Management**
  - Create, edit, and delete catalog rules
  - Define conditions and actions for each catalog rule
  - Set start and end dates for the promotion period
  - Enable or disable catalog rules as needed

- **Conditions**
  - Define conditions based on product attributes, such as category, SKU, price, and stock status
  - Combine multiple conditions using logical operators (AND, OR) to create complex rules

- **Validation and Enforcement**
  - Ensure that catalog rules are validated and applied correctly based on defined conditions
  - Enforce the rules during catalog rendering to provide accurate discounts

### Category

The Category package in Bagisto manages the database logic related to categories. It allows you to define, organize, and manage product categories effectively, facilitating the categorization and hierarchical structuring of products within your store.

#### Key Features of the Category Package

- **Category Management**
  - Create, edit, and delete categories
  - Define parent-child relationships to establish a hierarchical category structure
  - Set category attributes such as name, description, and URL keys
  - Enable or disable categories as needed

- **SEO and URL Management**
  - Define URL keys for categories to enhance SEO
  - Set meta titles, descriptions, and keywords to improve search engine visibility

- **Multi-Store and Multi-Language Support**
  - Support for multiple store views and languages
  - Define category attributes and settings specific to each store view 

### Checkout

The Checkout package in Bagisto manages the entire checkout process, encompassing cart management, order processing, payment integration, and shipping methods. It plays a crucial role in facilitating a smooth and efficient transaction experience for customers on your e-commerce platform.

#### Key Components of the Checkout Package

- **Cart Management**
  - Handles the addition, removal, and updating of products in the shopping cart
  - Applies discounts and promotions based on cart conditions

- **Order Processing**
  - Manages the creation, editing, and processing of orders
  - Calculates the total amount due for an order, including taxes and shipping

- **Payment Integration**
  - Integrates with various payment providers to handle online transactions securely
  - Supports multiple payment options such as credit cards, PayPal, and more

- **Shipping Methods**
  - Integrates with shipping carriers to calculate shipping rates and manage delivery options
  - Applies conditions for free shipping, flat rates, or custom shipping charges

### Core

The Core package in Bagisto serves as the foundation for various functionalities and utilities essential for the operation of the entire e-commerce platform. It encapsulates critical components, settings, configurations, and common helper functions that are integral to the seamless functioning of other packages within Bagisto. Here’s a detailed description of the Core package:

#### Key Features and Components of the Core Package

- **Settings and Configurations**
  - Manages platform-wide configurations such as site name, logo, currency settings, and default language
  - Handles environment-specific settings for development, staging, and production environments

- **Common Helper Functions**
  - Includes a range of helper functions for tasks such as data manipulation, string operations, file handling, and date/time formatting
  - Offers validation functions for input data, ensuring data integrity and adherence to predefined rules

### Customer

The Customer package in Bagisto is designed to handle all aspects related to customer management, authentication, and customer-centric functionalities essential for e-commerce operations. It provides a comprehensive suite of features to manage customer accounts, streamline registration processes, and enhance user engagement.

#### Key Features and Components of the Customer Package

- **Customer Account Management**
  - Facilitates customer registration with email verification and password management. Supports social login integration for streamlined access
  - Allows customers to update personal information, manage addresses, and view order history from their account dashboard

- **Authentication and Authorization**
  - Ensures secure login mechanisms with hashing and encryption techniques to protect customer credentials

- **Integration with Sales and Marketing**
  - Supports customer segmentation for targeted marketing campaigns and personalized promotions

- **Analytics and Reporting**
  - Provides analytics on customer behavior, preferences, and lifetime value to optimize marketing strategies and customer retention efforts
  - Generates reports on customer registrations, login activities, and transaction histories for business analysis and decision-making


### DataGrid

The DataGrid package in Bagisto empowers administrators with a versatile solution for displaying and managing tabular data within the admin panel. It incorporates crucial components like models, repositories, and database interactions to streamline data handling and enhance user experience.

#### Key Features of the DataGrid Package

- **Dynamic Data Presentation**
  - Allows administrators to configure columns, filters, sorting options, and pagination settings for displaying data tables

- **Advanced Filtering and Sorting**
  - Enables administrators to apply filters based on various criteria to refine data views
  - Supports sorting functionalities to organize data based on specified attributes

### DataTransfer

This package contains all the logic related to data transfer. You can follow the given link for the more information about the [DataTransfer](https://bagisto.com/en/how-to-bulk-import-products-in-bagisto-2-1-0/).

> **Note:** The referenced blog post may be from an older version of Bagisto, but the core design patterns and workflow remain largely identical across versions. We recommend reviewing the documentation alongside your current Bagisto installation to identify any minor API or structural differences that may have evolved.

### DebugBar

This package includes essential functionalities to monitor, analyze, and debug the application, ensuring optimal performance and quick resolution of issues.

### FPC

This package provides advanced caching mechanisms to store generated pages in memory, reducing server load and improving page load times for your customers. You can follow the given link for the more information about the [FPC](https://bagisto.com/en/optimizing-bagisto-e-commerce-a-deep-dive-into-full-page-cache-implementation/).

> **Note:** The referenced blog post may be from an older version of Bagisto, but the core design patterns and workflow remain largely identical across versions. We recommend reviewing the documentation alongside your current Bagisto installation to identify any minor API or structural differences that may have evolved.

#### Key Features of the FPC Package

- Full Page Caching

    - Caches entire pages and serves them to users without re-processing server-side logic.
    - Reduces response time by serving pre-rendered pages directly from the cache.

- Cache Invalidation
    - Automatically invalidates and updates the cache when changes occur (e.g., product updates, inventory changes).
    - Ensures customers always see the most up-to-date content without compromising performance.

### GDPR

The GDPR Package in Bagisto allows customers to easily raise requests to update, modify, or delete their personal data stored on the platform. This feature empowers customers by giving them greater control over their information and ensures that businesses comply with data protection laws like the GDPR.

#### Key Features of the GDPR Package

- Customer-Initiated Data Modification Requests
    - Customers can raise requests to modify or update their personal details (such as email, name, address, etc.) in their account settings.
    - Customers can easily request to delete their personal data from the system. This is in accordance with the GDPR’s "Right to Erasure," which allows individuals to request the deletion of their data from systems that no longer need it.
    - Customers can revoke consent for data processing, allowing businesses to stop collecting or using their data. The system keeps track of revocation timestamps (revoked_at), ensuring compliance with GDPR.
    - While the customers can submit requests, the admin can review, approve, or reject them via the admin panel. The admin can also track the progress and history of these requests.

### Installer

The Installer package in Bagisto simplifies the setup and installation process of your e-commerce platform, providing a streamlined experience for deploying Bagisto on various environments. This package includes essential functionalities to configure database connections, install dependencies, and initialize the application environment, ensuring a smooth and hassle-free installation process.

### Inventory 

The Inventory package in Bagisto offers comprehensive tools to manage and track product inventory efficiently within your e-commerce store. This package includes essential functionalities to monitor stock levels, track inventory movements, and ensure accurate stock availability for seamless order fulfillment. Here’s a detailed description of the Inventory package:

#### Key Components of the Inventory Package

- Stock Management
    - Allows businesses to manage stock levels for each product.
    - Supports updating stock quantities manually or via automated processes.

- Multi-Warehouse Support
    - Enables management of inventory across multiple warehouses.
    - Allows assigning stock to specific warehouses for better control and distribution.
 
- Inventory Movements
    - Tracks inventory movements including stock additions, subtractions, transfers, and adjustments.
    - Provides detailed logs of inventory changes for audit purposes.

### MagicAI 

The MagicAI package in Bagisto integrates advanced artificial intelligence capabilities directly into your e-commerce platform, offering powerful tools to enhance efficiency, customer experience, and decision-making processes. You can follow the given link for the more information about the [MagicAI](https://bagisto.com/en/laravel-bagisto-2-1-0-ai-features/)

> **Note:** The referenced blog post may be from an older version of Bagisto, but the core design patterns and workflow remain largely identical across versions. We recommend reviewing the documentation alongside your current Bagisto installation to identify any minor API or structural differences that may have evolved.

### Marketing 

The Marketing package in Bagisto encompasses all functionalities related to marketing strategies and promotions within the e-commerce platform. It includes tools for defining and managing cart rules, catalog rules, promotions, discounts, and other marketing campaigns to enhance customer engagement and drive sales.

### Notification

The Notification package in Bagisto handles all functionalities related to notifications within the e-commerce platform. It provides mechanisms for sending automated alerts, updates, and messages to customers and administrators based on various events and triggers, enhancing communication and user engagement throughout the shopping experience.

### Payment

The Payment package in Bagisto integrates various payment gateways seamlessly into the e-commerce platform. It facilitates secure and reliable processing of customer orders by enabling merchants to configure and manage multiple payment methods. This package ensures smooth transaction flows, enhances checkout experiences, and supports a wide range of payment gateways to meet diverse business needs and customer preferences.

### Paypal 

The PayPal package in Bagisto handles all functionalities related to integrating the PayPal payment gateway into your e-commerce store. This integration allows merchants to offer PayPal as a payment option to their customers, enhancing convenience and trust during the checkout process. Key features of the PayPal package include:

-  Enables merchants to configure PayPal credentials and settings through the Bagisto admin panel.

- Facilitates secure and seamless processing of payments using PayPal's APIs, ensuring transactions are reliable and efficient.

-  Integrates PayPal's transaction data with Bagisto's order management system, providing real-time updates and synchronization.

- Enhances the checkout experience by offering customers the option to pay with PayPal, a widely recognized and trusted payment method.


### Product

The Product package in Bagisto encapsulates comprehensive functionalities related to managing and presenting product information within your e-commerce store. Key aspects and features of the Product package include:

- Stores and manages essential details about each product, including name, description, pricing, and inventory levels.

- Facilitates the creation and management of product attributes and variants, allowing for flexible product configurations and options.

- Supports categorization of products into hierarchical categories, enabling organized navigation and browsing.

- Tracks and updates inventory levels in real-time, ensuring accurate stock availability displayed to customers.

- Defines structured data models and repository patterns for efficient data handling and interaction.

- Allows administrators to create new products, update existing ones, and manage product life cycle efficiently.

### Sales

The Sales package in Bagisto provides functionalities related to managing and tracking sales within your e-commerce store. It includes features such as order management, invoicing, shipment tracking, and customer communication. With the Sales package, you can efficiently process and fulfill customer orders, ensuring a seamless shopping experience.

- Facilitates the creation, modification, and tracking of customer orders from initiation to fulfillment.

- Defines various order statuses (e.g., pending, processing, completed) to indicate the current stage of each order.

- Generates invoices automatically upon order confirmation, detailing product prices, taxes, and discounts.

- Integrates with various payment gateways to securely process customer payments, ensuring flexibility and convenience.

- Manages refund requests and return processes, tracking the status and processing refunds accordingly.

- Generates reports on sales performance, order trends, revenue analysis, and inventory insights to support decision-making.


### Shipping

The Shipping package in Bagisto provides functionalities to manage and handle shipping methods and rates for customer orders. It includes features such as configuring shipping carriers, defining shipping zones, calculating shipping rates based on various factors like weight, dimensions, and destination, and integrating with third-party shipping APIs for real-time shipping quotes. With the Shipping package, you can ensure smooth and efficient order fulfillment by offering reliable and cost-effective shipping options to your customers.

### Shop

The Shop package in Bagisto provides the frontend functionality for your e-commerce store. It includes features such as displaying products, managing the shopping cart, processing the checkout process, and integrating with various payment gateways for secure and convenient payment processing. With the Shop package, you can create a seamless and user-friendly shopping experience for your customers.

- Product Display and Management
    - Displays products in a structured and organized manner, allowing customers to browse and search for products based on categories, attributes, and filters.

    - Provides detailed product pages with essential information such as product descriptions, specifications, pricing, and availability.

    - Supports product variants (e.g., sizes, colors) and options, enabling customers to select their preferred options directly on the product page.

- Localization and Multi-currency Support
    - Supports multiple languages, allowing you to cater to diverse customer bases and enhance accessibility for international shoppers.

    -  Displays product prices in different currencies, enabling customers to shop and complete transactions in their preferred currency.

- Shopping Cart and Checkout Process
    - Manages customer-selected products, quantities, and total prices, providing a seamless shopping cart experience with features like add to cart, update cart, and remove items.

    - Guides customers through a secure and intuitive checkout process, collecting necessary information such as shipping address, payment method selection, and order review.

### Sitemap

This package manages all the logics related to sitemap. The Sitemap package in Bagisto empowers e-commerce businesses to enhance their SEO efforts by automating the generation and management of XML sitemaps. By leveraging its features, businesses can improve search engine visibility, drive organic traffic growth, and provide a seamless user experience for their customers.

### SocialLogin

The SocialLogin package in Bagisto empowers e-commerce businesses to enhance user engagement, streamline registration processes, and leverage social media platforms for improved customer acquisition and retention.

### SocialShare

 This package enables customers to easily share products, categories, and content across various social media platforms, enhancing visibility and engagement.

### Tax

This package enables businesses to configure and apply taxes accurately based on customer locations, product types, and regulatory requirements, ensuring compliance and seamless transaction processing.

### Theme 

The theme package in Bagisto handles all the logic related to theme customization. This package is essential for businesses that want to create unique storefronts, enhance their brand identity, and deliver tailored shopping experiences to their customers. Here’s a detailed description of the theme package

#### Key features of the Theme package include

- Customization Capabilities
    - Businesses can create custom themes or modify existing ones to reflect their brand identity and visual preferences. This includes customizing colors, typography, layouts, and styles.

- Theme Management
    -  The package facilitates easy installation, activation, and management of themes through configuration files and administrative controls. Themes can be switched seamlessly without affecting site functionality.

- Customizable Layouts
    - Allows users to create and modify layouts to fit their specific needs.
    - Supports various layout structures, including header, footer, and content sections.

- Multi-theme Support
    - Enables the use of multiple themes in a single Bagisto instance.
    - Supports theme switching based on customer preferences or store settings

### User

This package empowers administrators to efficiently manage user registrations, profiles, roles, and permissions, ensuring secure and personalized customer interactions.

Service provider enables features such as loading [routes](/package-development/routes), [migrations](/package-development/migrations), [languages](/package-development/localization) or publishing [views](/package-development/views), etc so **Bagisto** is developed considering these aspects.

# Bagisto Frontend Overview

Bagisto's frontend leverages powerful tools and frameworks to create a dynamic, responsive, and visually appealing user interface. Designed to deliver a seamless shopping experience, Bagisto combines modern technologies and best practices to ensure optimal performance and flexibility.

## Tailwind CSS

Bagisto uses [Tailwind CSS](https://tailwindcss.com/) for its styling needs. Tailwind CSS is a highly customizable, utility-first CSS framework that allows developers to build responsive and modern designs efficiently.

### Key Features

- **Customization**: Tailwind CSS provides extensive configuration options, enabling developers to tailor the design system to specific project requirements
- **Utility-First Approach**: It offers utility classes that can be combined to create any design directly in your HTML

### Configuration

To configure Tailwind CSS in Bagisto, you need to define your Blade file path along with the JavaScript file directory in the `tailwind.config.js` file. Tailwind CSS will compile all the CSS defined at the specified location.

## Vue.js

The dynamic user interfaces in Bagisto are powered by [Vue.js](https://vuejs.org/), a robust and flexible JavaScript framework.

### Key Features

- **Reactive Components**: Vue.js enables the development of reactive components that update seamlessly as the data changes
- **Component-Based Architecture**: This promotes reusability and maintainability of code by breaking down the UI into isolated, reusable components

### Build Tool Integration

In conjunction with Vue.js, Bagisto uses [Vite](https://vitejs.dev/) as the build tool. Vite offers a fast and efficient development environment. The `vite.config.js` file defines the build directory path, and Vite compiles all CSS and JavaScript assets into the public directory.

## Blade

Bagisto utilizes the Blade template engine, which is integrated with [Laravel](https://laravel.com). Blade allows developers to use both Blade components and plain PHP code within templates, providing flexibility and power for crafting dynamic and efficient solutions.

### Key Features

- **Template Inheritance**: Blade supports template inheritance, which allows for a modular and maintainable template structure
- **Directives**: Blade includes various directives that simplify common tasks, such as loops and conditionals

# Getting Started

A package is a self-contained module that encapsulates specific features or functionality, allowing developers to add custom features without altering the core codebase. This approach not only preserves the integrity of the core system but also ensures that updates and maintenance can be carried out smoothly.

By developing packages, you can introduce new functionalities, integrate third-party services, or customize existing features to better meet your business requirements. Each package is isolated, promoting clean code practices and enabling easier debugging and testing.

To provide you with a practical understanding of package development, we'll be building a basic **RMA (Return Merchandise Authorization)** package throughout this documentation. This will demonstrate real-world implementation patterns and show you how different components work together.

The RMA package will include:
- Customer return request functionality
- Admin panel for managing returns
- Email notifications
- Basic reporting features

::: warning Demonstration Purpose
This RMA package is designed for educational purposes to demonstrate package development concepts. It includes only basic CRUD operations and simplified workflows. For production use, you would need to implement additional features like complex business rules, advanced security measures, and comprehensive error handling.
:::

## Prerequisites

Before getting started with package development, ensure you have:

- A working [Bagisto Application](/getting-started/installation.html#%F0%9F%9A%80-quick-installation-recommended)
- Basic knowledge of [Laravel Framework](https://laravel.com/docs)
- Understanding of [PHP](https://www.php.net/manual/) and Object Oriented Programming

## Using Bagisto Package Generator

To facilitate package development, you can use the [Bagisto Package Generator](https://github.com/bagisto/bagisto-package-generator). Follow the steps below to install it:

::: tip Package Generator Benefits
The [Bagisto Package Generator](https://github.com/bagisto/bagisto-package-generator) automatically creates the necessary directory structure, service providers, and configuration files, saving you time and ensuring consistency across packages.
:::

### Installation

Install the [Bagisto Package Generator](https://github.com/bagisto/bagisto-package-generator) by running the following command in the root folder of your Bagisto application:

```bash
composer require bagisto/bagisto-package-generator
```

### Creating a Package

Once installed, you can generate your package using the following command:

::: info Example Package
We will assume that the package name is **"RMA"** (Return Merchandise Authorization) for demonstration purposes.
:::

- If the package directory does not exist:

  ```bash
  php artisan package:make Webkul/RMA
  ```

- If the package directory already exists, you can use the `--force` option to overwrite it:

  ```bash
  php artisan package:make Webkul/RMA --force
  ```

This command will set up the necessary files and directories in the `packages` directory.

### Registering Your Package

To register your package, follow these steps:

#### Update Composer Autoloader

Add your package's namespace to the `psr-4` section in the `composer.json` file located in the root directory of your Bagisto application. Update it as follows:

```json{5}
"autoload": {
    ...
    "psr-4": {
        // Other PSR-4 namespaces
        "Webkul\\RMA\\": "packages/Webkul/RMA/src"
    }
}
```

Run the following command to regenerate the autoloader files:

```bash
composer dump-autoload
```

This ensures that the new namespace mapping is properly loaded by Composer's autoloader.

#### Register Service Provider

Register your package's service provider in the `bootstrap/providers.php` file located in the root directory of your Bagisto application. Add the following line `Webkul\RMA\Providers\RMAServiceProvider::class,` just like other Bagisto service providers:

```php{16}
<?php

return [
    /**
     * Application service providers.
     */
    App\Providers\AppServiceProvider::class,

    /**
     * Webkul's service providers.
     */

    /**
     * RMA service providers.
     */
    Webkul\RMA\Providers\RMAServiceProvider::class,
];
```

#### Final Setup Commands

Run the following command to clear the application cache:

```bash
php artisan optimize:clear
```

::: tip Success
Congratulations! Your RMA package is now successfully registered and ready for development. The package generator has automatically configured the basic structure, routes, and admin menu - you should now be able to see the RMA menu in the admin navigation panel. You can now start building the RMA functionality step by step according to your requirements.
:::

## Manual Setup of Files

If you prefer to set up your package manually, follow these steps assuming you are familiar with package directory structures and workflows. We'll use the default `package` folder in Bagisto as an example.

::: warning Manual Setup
Manual setup requires good understanding of Laravel package development. If you're new to this, consider using the [Package Generator](#using-bagisto-package-generator) method above.
:::

### Create Package Directory

Inside the `packages/Webkul` folder, create a folder with your package name. Your structure should look like this:

```
└── packages
    └── Webkul
        └── RMA
```

In your package folder, create a folder named as `src`. This is where you'll put all your package-related files. Your updated structure will look like this:

```
└── packages
    └── Webkul
        └── RMA
            └── src
```

### Create Service Provider

In the `src` folder, create a folder named as `Providers`. Inside that folder, create a file named as `RMAServiceProvider.php`. Your structure should look like this:

```
└── packages
    └── Webkul
        └── RMA
            └── src
                └── Providers
                    └── RMAServiceProvider.php
```

Copy the following code and paste it into `RMAServiceProvider.php`:

```php
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```

::: info Service Provider Explanation
The Service Provider is the central place to register your package's services, including routes, views, configurations, and other components. The `boot()` method is called after all services are registered, while `register()` is used to bind services into the container.
:::

### Register Your Package

#### Update Composer Autoloader

Add your package's namespace to the `psr-4` section in the `composer.json` file located in the root directory of your Bagisto application. Update it as follows:

```json{5}
"autoload": {
    ...
    "psr-4": {
        // Other PSR-4 namespaces
        "Webkul\\RMA\\": "packages/Webkul/RMA/src"
    }
}
```

Run the following command to regenerate the autoloader files:

```bash
composer dump-autoload
```

#### Register Service Provider

Register your package's service provider in the `bootstrap/providers.php` file located in the root directory of your Bagisto application. Add the following line `Webkul\RMA\Providers\RMAServiceProvider::class,` just like other Bagisto service providers:

```php{16}
<?php

return [
    /**
     * Application service providers.
     */
    App\Providers\AppServiceProvider::class,

    /**
     * Webkul's service providers.
     */

    /**
     * RMA service providers.
     */
    Webkul\RMA\Providers\RMAServiceProvider::class,
];
```

#### Final Setup Commands

Run the following command to clear the application cache:

```bash
php artisan optimize:clear
```

::: tip Package Ready
Your package is now ready for development! Note that the [Package Generator](#using-bagisto-package-generator) creates a more complete structure with additional boilerplate files. For a full-featured setup, consider using the Package Generator method which includes controllers, models, views, and other components automatically.
:::

## Next Steps

Once your package is set up, you can start building its functionality. For the remainder of this documentation, we'll assume you're following the manual setup approach, as this allows you to understand each component registration process step by step - such as routes, views, models, and controllers. While the Package Generator automates these registrations, learning the manual process helps you understand how each piece works together.

::: info Learning Approach
The remaining sections will guide you through manual registration of components to provide deeper understanding of package development concepts. If you used the Package Generator, you can still follow along to understand what was automatically created for you.
:::

# Migrations

Migrations provide version control for your database schema, allowing you to define and share database changes across different environments.

For our RMA (Return Merchandise Authorization) package, we'll create a migration that establishes the database structure needed for basic CRUD operations where admin users can create and manage return requests on behalf of customers.

::: info Learning Objective
This migration demonstrates how to create a realistic e-commerce table structure with proper relationships, constraints, and indexes for optimal performance.
:::

For detailed information about Laravel migrations, visit the [Laravel Documentation](https://laravel.com/docs/12.x/migrations).

## RMA Database Schema Overview

For our RMA package demonstration, we'll create a single table that supports basic CRUD operations where admin users can create, view, edit, and manage RMA requests on behalf of customers. This table will include all essential fields needed for a functional return management system.

**Key Features of Our RMA Table:**
- **Primary Key**: Auto-incrementing ID for database relationships
- **Customer & Order References**: Links to existing customers and orders
- **Product Information**: Track which products are being returned with SKU, name, and quantity
- **Status Management**: Workflow states from pending to completed
- **Return Reason**: Optional field for tracking why items are being returned
- **Admin Notes**: Internal comments for tracking and communication
- **Timestamps**: Audit trail for creation and updates

## Setting up Migration Support

Before creating migrations, we need to configure our service provider to load them. Update your `RMAServiceProvider.php` file:

```php{26}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
```

::: info Service Provider Registration
The `loadMigrationsFrom()` method tells Laravel where to find your package's migrations. This allows them to be run alongside the application's migrations using standard Artisan commands.
:::

## Creating Migration Files

Now that we have configured our service provider to load migrations, let's create the actual migration file. There are two approaches you can use:

### Using Bagisto Package Generator

This command creates a new migration class in the `packages/Webkul/RMA/src/Database/Migrations` directory.

```bash
php artisan package:make-migration CreateRmaRequestsTable Webkul/RMA
```

**Command Parameters:**
- `CreateRmaRequestsTable`: specifies the name of the migration file for our RMA requests table
- `Webkul/RMA`: specifies the package name

The package generator will automatically create the migration file with a basic structure. You'll see a new file created like this:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rma_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_requests');
    }
};
```

::: tip Generated Migration Structure
The package generator creates a basic migration with just the table name, `id`, and `timestamps`. You'll need to add your custom fields to complete the migration for your RMA functionality.
:::

Now you need to modify this generated migration to add the specific fields for your RMA system. Continue to the [Writing the Migration](#writing-the-migration) section to see the complete implementation.

### Using Laravel Artisan Command

If you prefer using the standard Laravel artisan command, you can use the `--path` option to specify where your migration file will be placed. This command will automatically create the necessary directory structure for you.

::: tip Scoped to your package
Using `--path` ensures the migration is created inside your package rather than the app-level `database/migrations` folder.
:::

```bash
php artisan make:migration CreateRmaRequestsTable --path=packages/Webkul/RMA/src/Database/Migrations
```

This will automatically create the following directory structure if it doesn't exist:

```text
└── packages
    └── Webkul
        └── RMA
            └── src
                ├── ...
                └── Database
                    ├── Migrations
                    └── Seeders
```

## Writing the Migration

To create the RMA requests table, copy the code provided here and paste it into your migration file. This migration creates a comprehensive table structure suitable for a real-world RMA system:

```php{17-36}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rma_requests', function (Blueprint $table) {
            $table->id();
            
            // Customer And Order References
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('order_id');
            
            // Product Information
            $table->string('product_sku');
            $table->string('product_name');
            $table->integer('product_quantity');
            
            // Return Details
            $table->string('status')->default('pending');
            $table->string('reason')->nullable();
            
            // Comments And Notes
            $table->text('admin_notes')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rma_requests');
    }
};
```

### Migration Explanation

Let's break down the key components of this migration:

**Primary Key:**
- `id`: Auto-incrementing primary key for database relationships

**Reference Fields:**
- `customer_id`: Links to existing customer records in Bagisto
- `order_id`: Links to specific orders for context

**Product Information:**
- `product_sku`: Unique product identifier 
- `product_name`: Human-readable product name
- `product_quantity`: Number of items being returned

**Business Logic Fields:**
- `reason`: Optional field to categorize why items are being returned
- `status`: Tracks workflow progression (defaults to 'pending')

**Administrative Fields:**
- `admin_notes`: Text field for internal comments and communication
- `timestamps`: Laravel's created_at and updated_at for audit trails

## Run Migrations

Run the following command to create the `rma_requests` table in your database:

```bash
php artisan migrate
```

You should see output similar to:
```
2025_01_01_000000_create_rma_requests_table ............... 75.34ms DONE
```

### Useful Migration Commands

As you continue developing your RMA package, you'll frequently need to manage your migrations. Here are the most commonly used migration commands for package development:

```bash
# Check migration status
php artisan migrate:status

# Run only package migrations
php artisan migrate --path=packages/Webkul/RMA/src/Database/Migrations

# Rollback last migration batch
php artisan migrate:rollback

# Reset and re-run all migrations (development only)
php artisan migrate:fresh
```

## Your Next Step

With your migration complete, you now need to create a model that interacts with the `rma_requests` table. In Bagisto, models follow a specific architecture pattern using Contracts, Proxies, and Concord registration.

**Continue to:** **[Models](./models.md)** - Create the ReturnRequest model for your RMA package

# Models

In Bagisto, models follow a specific architecture pattern that combines Laravel's Eloquent with additional layers for modularity and flexibility. Unlike standard Laravel applications, Bagisto uses the [konekt/concord](https://packagist.org/packages/konekt/concord) package for modular development and implements the Repository pattern for data access.

## Bagisto's Model Architecture

Bagisto's model architecture consists of several key components:

### 1. **Konekt/Concord Integration**
Concord enables true modular development by allowing packages to define their own models, migrations, and business logic while maintaining loose coupling between modules.

### 2. **Contract-Based Design**
Each model implements a contract (interface) that defines its public API. This allows for easy model swapping and testing without breaking dependent code.

### 3. **Model Proxies**
Proxies act as intermediaries that enable runtime model resolution. This means packages can extend or override existing models without modifying core files.

### 4. **Repository Pattern**
Bagisto uses the Repository pattern to abstract data access logic. Repositories provide a consistent interface for data operations while keeping business logic separate from data persistence concerns.

::: info Why This Architecture?
This layered approach allows Bagisto to be highly modular and extensible. Developers can create packages that integrate seamlessly with the core system while maintaining the ability to customize and extend functionality.
:::

## Creating Models

When creating models in Bagisto, you have two approaches: using the package generator for convenience, or manually creating the components for more control. Models in Bagisto follow Laravel's Eloquent ORM but with additional architectural layers.

Learn more about Laravel Eloquent: https://laravel.com/docs/12.x/eloquent

Below, we'll create a `ReturnRequest` model for an RMA package to demonstrate both approaches.

### Using Bagisto Package Generator

The fastest way to create a complete model structure is using Bagisto's package generator. This command creates all three required components in one go:

```bash
php artisan package:make-model ReturnRequest Webkul/RMA
```

::: tip Package Generator Benefits
The generator automatically creates the proper file structure, namespaces, and basic implementations following Bagisto conventions. This saves time and ensures consistency.
:::

#### Generated Files Overview

The package generator creates three interconnected files that work together:

**1. Model Contract** - `packages/Webkul/RMA/src/Contracts/ReturnRequest.php`
```php
<?php

namespace Webkul\RMA\Contracts;

interface ReturnRequest
{
}
```

**2. Model Proxy** - `packages/Webkul/RMA/src/Models/ReturnRequestProxy.php`
```php
<?php

namespace Webkul\RMA\Models;

use Konekt\Concord\Proxies\ModelProxy;

class ReturnRequestProxy extends ModelProxy
{
}
```

**3. Base Model** - `packages/Webkul/RMA/src/Models/ReturnRequest.php`
```php
<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\ReturnRequest as ReturnRequestContract;

class ReturnRequest extends Model implements ReturnRequestContract
{
    protected $fillable = [];
}
```

### Using Laravel Artisan Command (Manual Approach)

If you prefer understanding each component or need more control, you can create each file manually. This approach creates the exact same three files as the package generator, helping you understand how the pieces fit together.

#### Step 1: Create the Contract

**File:** `packages/Webkul/RMA/src/Contracts/ReturnRequest.php`

```bash
mkdir -p packages/Webkul/RMA/src/Contracts
```

```php
<?php

namespace Webkul\RMA\Contracts;

interface ReturnRequest
{
}
```

#### Step 2: Create the Proxy

**File:** `packages/Webkul/RMA/src/Models/ReturnRequestProxy.php`

```bash
mkdir -p packages/Webkul/RMA/src/Models
```

```php
<?php

namespace Webkul\RMA\Models;

use Konekt\Concord\Proxies\ModelProxy;

class ReturnRequestProxy extends ModelProxy
{
}
```

#### Step 3: Create the Base Model

**File:** `packages/Webkul/RMA/src/Models/ReturnRequest.php`

```bash
php artisan make:model ReturnRequest
# Move from app/Models to packages/Webkul/RMA/src/Models
```

```php
<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\ReturnRequest as ReturnRequestContract;

class ReturnRequest extends Model implements ReturnRequestContract
{
    protected $fillable = [];
}
```

::: tip Comparing Approaches
**Package Generator Result = Manual Creation Result**

Both approaches create identical basic files. The manual approach helps you understand the structure, while the package generator saves time. Choose based on your learning preference!
:::

## Completing the Model Implementation

Whether you used the package generator or manual approach, you now have the same basic structure. Next, customize the base model to work with your `rma_requests` migration table:

**Transform your basic model into a fully functional RMA model:**

```php{10-21}
<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\ReturnRequest as ReturnRequestContract;

class ReturnRequest extends Model implements ReturnRequestContract
{
    protected $table = 'rma_requests';

    protected $fillable = [
        'customer_id',
        'order_id',
        'product_sku',
        'product_name',
        'product_quantity',
        'status',
        'reason',
        'admin_notes',
    ];
}
```

::: info Model Properties Explained
**Table Property Convention:**
- **`protected $table = 'rma_requests';`** - Explicitly defines the table name for this model
- **Why needed?** Laravel's default convention would expect `return_requests` (plural snake_case of model name), but we're using `rma_requests` to namespace our table with the package prefix
- **Best Practice:** Always use package prefixes (`rma_`, `blog_`, etc.) to avoid table name conflicts with core Bagisto tables or other packages

**Fillable Array:**
- **Purpose:** Defines which attributes can be mass-assigned using `create()` or `update()` methods
- **Security:** Protects against mass assignment vulnerabilities by explicitly whitelisting safe attributes
- **Convention:** Include all user-input fields that should be mass-assignable, excluding `id`, `created_at`, `updated_at` (automatically managed)
- **Rule of Thumb:** If a field appears in forms or API requests, it should be in the fillable array

**What's Protected:**
- Primary keys (`id`) are never fillable
- Timestamps (`created_at`, `updated_at`) are automatically managed by Laravel
- Sensitive fields like authentication tokens should use `$guarded` instead
:::

## Registering Models with Concord

Now that your model is complete, you need to register it with Bagisto's modular system. This is where the **ModuleServiceProvider** comes in.

### Why ModuleServiceProvider?

The ModuleServiceProvider serves a crucial purpose in Bagisto's architecture:

- **Model Registration**: Tells Concord about your package's models
- **Proxy Resolution**: Enables runtime model resolution and extensibility
- **Package Discovery**: Allows Bagisto to automatically discover your package components
- **Dependency Management**: Ensures proper loading order of package components

Without this registration, Bagisto won't know about your models and they won't be available for dependency injection or proxy resolution.

### Creating the ModuleServiceProvider

Create `packages/Webkul/RMA/src/Providers/ModuleServiceProvider.php`:

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Providers
                ├── ModuleServiceProvider.php
                └── RMAServiceProvider.php
```

```php
<?php

namespace Webkul\RMA\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\RMA\Models\ReturnRequest::class,
    ];
}
```

::: info Understanding the Registration
**What This Does:**

- **`$models` Array**: Lists all models in your package that should be registered with Concord
- **`BaseModuleServiceProvider`**: Provides the functionality to register models, enums, and other components
- **Automatic Discovery**: Concord uses this list to set up proxies and dependency injection

This registration enables features like model swapping, where other packages can extend or replace your models without modifying your code.
:::

### Registering with Concord

Finally, register your ModuleServiceProvider with Bagisto's Concord system by adding it to `config/concord.php`:

- Open the configuration file at `config/concord.php` in your Laravel application.
- Inside the `modules` array, add the `ModuleServiceProvider` class to register it with Concord.

```php{6}
<?php

return [
    'modules' => [
        // Other service providers...
        \Webkul\RMA\Providers\ModuleServiceProvider::class,
    ],
];
```

## Testing Your Complete Setup

Verify everything works together:

```bash
php artisan tinker
```

```php
// Test model creation via direct model
\Webkul\RMA\Models\ReturnRequest::create([
    'customer_id' => 1,
    'order_id' => 1,
    'product_sku' => 'SAMPLE-001',
    'product_name' => 'Test Product 1',
    'product_quantity' => 1,
    'reason' => 'Defective Item'
]);

// Test model creation via proxy
\Webkul\RMA\Models\ReturnRequestProxy::create([
    'customer_id' => 2,
    'order_id' => 2,
    'product_sku' => 'SAMPLE-002',
    'product_name' => 'Test Product 2',
    'product_quantity' => 1,
    'reason' => 'Defective Item'
]);
```

::: info Testing Tips
**Quick Verification Commands:**
- Check if migration ran: `php artisan migrate:status`
- Clear cache if needed: `php artisan optimize:clear`
:::

## Troubleshooting Common Issues

When working with Bagisto models, you might encounter several common issues. Here's how to identify and resolve them:

### 1. Model Proxy Registration Errors

**Error:**
```
TypeError: Konekt\Concord\Proxies\ModelProxy::targetClass(): Return value must be of type string, null returned.
```

**Cause:** This error occurs when the model is not properly registered with Concord or the ModuleServiceProvider is not loaded.

**Solution:**
1. **Verify ModuleServiceProvider Registration:**
   ```php
   // Check config/concord.php contains your provider
   'modules' => [
       \Webkul\RMA\Providers\ModuleServiceProvider::class,
   ],
   ```

2. **Ensure Model is Listed in ModuleServiceProvider:**
   ```php
   protected $models = [
       \Webkul\RMA\Models\ReturnRequest::class, // Must be the actual model, not proxy
   ];
   ```

3. **Clear Cache:**
   ```bash
   php artisan optimize:clear
   ```

### 2. Table Not Found Errors

**Error:**
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'bagisto.rma_requests' doesn't exist
```

**Solution:**
1. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

2. **Check Migration Status:**
   ```bash
   php artisan migrate:status
   ```

3. **Verify Table Name in Model:**
   ```php
   protected $table = 'rma_requests'; // Must match migration table name
   ```

### 3. Namespace and Autoloading Issues

**Error:**
```
Class 'Webkul\RMA\Models\ReturnRequest' not found
```

**Solution:**
1. **Verify PSR-4 Autoloading in composer.json:**
   ```json
   "autoload": {
       "psr-4": {
           "Webkul\\RMA\\": "packages/Webkul/RMA/src/"
       }
   }
   ```

2. **Update Composer Autoload:**
   ```bash
   composer dump-autoload
   ```

### 4. Fillable Attribute Errors

**Error:**
```
Illuminate\Database\Eloquent\MassAssignmentException: customer_id
```

**Solution:**
```php
// Ensure all required fields are in fillable array
protected $fillable = [
    'customer_id',
    'order_id',
    'product_sku',
    'product_name',
    'product_quantity',
    'status',
    'reason',
    'admin_notes',
];
```

### 5. Contract Implementation Issues

**Error:**
```
Class must implement interface Webkul\RMA\Contracts\ReturnRequest
```

**Solution:**
```php
// Ensure model implements contract
class ReturnRequest extends Model implements ReturnRequestContract
{
    // Model implementation
}
```

## Overriding Core Models (Optional)

Sometimes you need to extend or modify existing Bagisto core models (like Product, Customer, Order) to add custom functionality. Bagisto's Concord architecture makes this possible without modifying core files.

::: info When to Override Models
Model overriding is an **advanced technique** used when you need to:
- Add custom attributes or relationships to core models
- Modify existing model behavior
- Integrate third-party services with core entities
- Create specialized business logic for existing models
:::

### Quick Override Example

Here's how to override the core `Product` model to add custom functionality:

**1. Create Your Extended Model**

Create `packages/Webkul/RMA/src/Models/Product.php`:

```php
<?php

namespace Webkul\RMA\Models;

use Webkul\Product\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    /**
     * Get return requests for this product.
     */
    public function returnRequests()
    {
        return $this->hasMany(ReturnRequestProxy::modelClass(), 'product_sku', 'sku');
    }

    /**
     * Check if product is returnable.
     */
    public function isReturnable(): bool
    {
        return $this->status && $this->type !== 'digital';
    }
}
```

**2. Register the Override**

Register the model override in your main service provider:

```php{15-18}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    // Other methods...

    public function boot()
    {
        // Other boot logic...

        $this->app->concord->registerModel(
            \Webkul\Product\Contracts\Product::class,
            \Webkul\RMA\Models\Product::class
        );
    }
}
```

::: tip Model Override Registration
This method registers your extended model with Concord's dependency injection system. When any part of Bagisto requests the Product contract, your extended model will be used instead of the core model.
:::

**3. Use Everywhere via Repository**

Following Bagisto's best practices, access your extended model through repositories:

```php
// In controllers, services, etc.
use Webkul\Product\Repositories\ProductRepository;

class SomeController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    public function checkReturnable($productId)
    {
        $product = $this->productRepository->find($productId);
        
        // This automatically uses your extended model
        return $product->isReturnable();
    }
}
```

::: tip Repository Pattern Benefits
- **Consistent Interface**: All data access goes through repositories
- **Automatic Model Resolution**: Repositories use dependency injection to get the correct model
- **Business Logic**: Repositories can contain query logic and business rules
- **Testability**: Easy to mock repositories for unit testing
:::

**Alternative: Direct Contract Usage (When Needed)**

```php
// For specific cases where you need direct model access
use Webkul\Product\Contracts\Product as ProductContract;

class SpecialService
{
    public function processProduct(ProductContract $product)
    {
        // Direct model usage - automatically gets your extended model
        return $product->isReturnable();
    }
}
```

::: warning Important Notes
- **Always extend the base model**, never replace it entirely
- **Use dependency injection** with contracts for automatic resolution
- **Test thoroughly** as overrides affect the entire application
- **Consider alternatives** like observers or custom services for simple additions
:::

## Your Next Step

With your model complete and registered, you now need to implement Bagisto's Repository pattern. Repositories abstract your data access logic and provide a consistent interface for data operations while keeping business logic separate from data persistence.

**Continue to:** **[Repositories](./repositories.md)** - Implement the Repository pattern for your RMA model

# Repositories

In Bagisto, the Repository pattern is a crucial architectural component that abstracts database operations and promotes cleaner, more maintainable code. Unlike traditional development where application logic is often embedded directly in controllers, Bagisto uses repositories to decouple models from controllers and provide readable names for complex queries.

Repositories provide a consistent interface for data operations while keeping business logic separate from data persistence concerns. This separation enhances code readability, reusability, and adherence to the separation of concerns principle.

::: info Why Repositories in Bagisto?
Bagisto's repository pattern, powered by the [Prettus L5 Repository](https://github.com/andersao/l5-repository) package, provides advanced features like criteria-based filtering, caching, and automatic query optimization that enhance the standard Laravel Eloquent experience.
:::

For our RMA package, we'll create a `ReturnRequestRepository` that works with the `ReturnRequest` model we created earlier.

## Creating Repositories

When creating repositories in Bagisto, you have two approaches: using the package generator for convenience, or manually creating the repository for more control.

### Using Bagisto Package Generator

The fastest way to create a repository is using Bagisto's package generator:

```bash
php artisan package:make-repository ReturnRequestRepository Webkul/RMA
```

**Command Parameters:**
- `ReturnRequestRepository`: The name of the repository class
- `Webkul/RMA`: The package where the repository will be created

This will create a repository file at `packages/Webkul/RMA/src/Repositories/ReturnRequestRepository.php`:

```php
<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;

class ReturnRequestRepository extends Repository
{
    /**
     * Specify the Model contract class name.
     */
    public function model(): string
    {
        return 'Webkul\RMA\Contracts\ReturnRequest';
    }
}
```

::: tip Package Generator Benefits
The generator automatically creates the proper file structure, namespaces, and extends the correct base repository class following Bagisto conventions.
:::

### Manual Repository Creation

If you prefer understanding each component or need more control, you can create the repository manually:

#### Step 1: Create Repository Directory

Create a `Repositories` folder within your package:

```bash
mkdir -p packages/Webkul/RMA/src/Repositories
```

#### Step 2: Create Repository File

Create `packages/Webkul/RMA/src/Repositories/ReturnRequestRepository.php`:

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Repositories
                └── ReturnRequestRepository.php
```

#### Step 3: Implement Repository Class

```php
<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;

class ReturnRequestRepository extends Repository
{
    /**
     * Specify the Model contract class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\RMA\Contracts\ReturnRequest';
    }
}
```

::: info Understanding the Repository Structure
**Key Components:**

- **Namespace**: Follows PSR-4 autoloading standards
- **Base Class**: Extends `Webkul\Core\Eloquent\Repository` which provides all repository methods
- **Model Contract**: References the model contract (not the model directly) for better flexibility
- **Return Type**: The `model()` method must return the full class path of your model contract
:::

## Available Repository Methods

Bagisto repositories leverage the [Prettus L5 Repository](https://github.com/andersao/l5-repository) package, providing a rich set of methods for database operations. Here are the most commonly used methods:

### Basic CRUD Operations

#### Create New Records

```php
// Create a single return request
$returnRequest = $this->returnRequestRepository->create([
    'customer_id' => 1,
    'order_id' => 123,
    'product_sku' => 'SAMPLE-001',
    'product_name' => 'Test Product',
    'product_quantity' => 1,
    'reason' => 'Defective item',
    'status' => 'pending',
]);
```

#### Retrieve Records

```php
// Get all return requests
$allReturns = $this->returnRequestRepository->all();

// Find by ID
$returnRequest = $this->returnRequestRepository->find($id);

// Find by ID or throw exception
$returnRequest = $this->returnRequestRepository->findOrFail($id);

// Get first record matching conditions
$firstPending = $this->returnRequestRepository->findWhere([
    'status' => 'pending'
])->first();
```

#### Update Records

```php
// Update by ID
$returnRequest = $this->returnRequestRepository->update([
    'status' => 'approved',
    'admin_notes' => 'Approved for return'
], $id);
```

#### Delete Records

```php
// Delete by ID
$this->returnRequestRepository->delete($id);
```

### Advanced Query Methods

#### Conditional Queries

```php
// Find records matching specific conditions
$pendingReturns = $this->returnRequestRepository->findWhere([
    'status' => 'pending',
    'customer_id' => 456,
]);

// Find records where field value is in array
$specificReturns = $this->returnRequestRepository->findWhereIn('id', [1, 2, 3, 4, 5]);

// Find records where field value is between two values
$recentReturns = $this->returnRequestRepository->findWhereBetween('created_at', [
    '2024-01-01',
    '2024-12-31'
]);
```

#### Pagination

```php
// Paginate results (15 per page by default)
$paginatedReturns = $this->returnRequestRepository->paginate(15);

// With custom pagination
$returns = $this->returnRequestRepository->paginate($perPage = 20, $columns = ['*'], $method = 'paginate');
```

#### Relationships and Eager Loading

```php
// Eager load relationships (assuming you have defined them in your model)
$returnWithRelations = $this->returnRequestRepository
    ->with(['customer', 'order'])
    ->find($id);

// Multiple relationships
$returns = $this->returnRequestRepository
    ->with(['customer', 'order', 'product'])
    ->paginate(15);
```

### Custom Query Methods

You can add custom methods to your repository for complex business logic:

```php{16-49}
<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;

class ReturnRequestRepository extends Repository
{
    /**
     * Specify the Model contract class name.
     */
    public function model(): string
    {
        return 'Webkul\RMA\Contracts\ReturnRequest';
    }

    /**
     * Get pending return requests for a specific customer.
     */
    public function getPendingForCustomer(int $customerId)
    {
        return $this->findWhere([
            'customer_id' => $customerId,
            'status' => 'pending'
        ]);
    }

    /**
     * Get return requests statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => $this->count(),
            'pending' => $this->findWhere(['status' => 'pending'])->count(),
            'approved' => $this->findWhere(['status' => 'approved'])->count(),
            'rejected' => $this->findWhere(['status' => 'rejected'])->count(),
        ];
    }

    /**
     * Get recent return requests.
     */
    public function getRecent(int $limit = 10)
    {
        return $this->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
```

## Testing Your Repository

Verify your repository works correctly:

```bash
php artisan tinker
```

```php
// Test repository through service container
$repository = app('Webkul\RMA\Repositories\ReturnRequestRepository');

// Create a test record
$return = $repository->create([
    'customer_id' => 1,
    'order_id' => 1,
    'product_sku' => 'TEST-001',
    'product_name' => 'Test Product',
    'product_quantity' => 1,
    'reason' => 'Testing repository',
    'status' => 'pending'
]);

// Test retrieval
$retrieved = $repository->find($return->id);
echo $retrieved->product_name; // Should output: Test Product

// Test update
$updated = $repository->update(['status' => 'approved'], $return->id);
echo $updated->status; // Should output: approved
```

::: info Testing Tips
**Quick Verification Commands:**
- Check if repository resolves: `php artisan tinker` then `app('Webkul\RMA\Repositories\ReturnRequestRepository')`
- Test basic operations: Create, read, update, delete operations
- Verify relationships work if you've defined them in your model
:::

## Your Next Step

With your repository complete, you now have a clean data access layer for your RMA package. The next logical step is to define routes that will connect HTTP requests to your repository operations.

**Continue to:** **[Routes](./routes.md)** - Configure routing for your RMA package

# Routes

Routes define the entry points to your application, mapping HTTP requests to specific controllers or actions. In Bagisto, routes are organized to handle both admin panel functionality and storefront operations, supporting all common HTTP methods (GET, POST, PUT, DELETE, PATCH) with middleware protection and RESTful patterns.

For our RMA package, we'll create routes that allow administrators to manage return requests and provide customer-facing functionality for submitting and tracking returns.

::: info Learning Objective
This section demonstrates how to create organized, secure routes for both admin and shop sections of your Bagisto package, following best practices for middleware configuration and URL structure.
:::

For detailed information on Laravel routing concepts, visit the [Laravel Documentation on Routing](https://laravel.com/docs/12.x/routing).

## Bagisto Route Organization

Bagisto follows a structured approach to route organization:

### Admin Routes
- **Purpose**: Administrative functionality for managing your package features
- **Access**: Protected by admin authentication middleware
- **URL Pattern**: Prefixed with the admin URL (typically `/admin`)
- **Features**: Full CRUD operations, data management, reporting

### Shop Routes  
- **Purpose**: Customer-facing functionality for your package
- **Access**: Protected by shop middleware (locale, theme, currency)
- **URL Pattern**: Public URLs accessible to customers
- **Features**: Customer interactions, public APIs, frontend functionality

## Creating Route Files

Let's create the route structure for our RMA package. We'll organize routes into separate files for better maintainability.

### Directory Structure

Create the following directory structure in your package:

```bash
mkdir -p packages/Webkul/RMA/src/Routes
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Routes
                ├── admin-routes.php
                └── shop-routes.php
```

### Admin Routes File

Create `packages/Webkul/RMA/src/Routes/admin-routes.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'admin'], 
    'prefix' => config('app.admin_url')
], function () {
    /**
     * Return request routes.
     */
    Route::prefix('rma/return-requests')->group(function () {
        /**
         * First route. 
         */
        Route::get('', function () {
            return 'Admin RMA Return Requests List';
        })->name('admin.rma.return-requests.index');
    });
});
```

::: info Admin Route Explanation
**Route Structure:**

- **Middleware**: `['web', 'admin']` ensures proper session handling and admin authentication
- **Prefix**: Uses `config('app.admin_url')` (typically `/admin`) for all admin routes
- **Route Prefix**: `rma/return-requests` creates organized URL structure
- **Callback Functions**: Simple closures that return strings to demonstrate route functionality
- **Naming Convention**: Uses `admin.rma.return-requests.*` pattern for easy route referencing
- **RESTful Pattern**: Will follow standard CRUD operations when we add controllers

**Note**: We'll replace these callback functions with proper controllers in the **[Controllers](./controllers.md)** section.
:::

### Shop Routes File

Create `packages/Webkul/RMA/src/Routes/shop-routes.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'locale', 'theme', 'currency']
], function () {
    // Leave it blank for now...
});
```

::: info Shop Route Explanation
**Route Structure:**

- **Middleware**: `['web', 'locale', 'theme', 'currency']` handles storefront essentials
- **No Prefix**: Shop routes are accessible directly from the root URL
- **Placeholder**: Currently empty, will be populated when we add customer-facing functionality
- **Future Structure**: Will include routes for customers to create and view their return requests
- **Naming Convention**: Will use `shop.rma.*` pattern to distinguish from admin routes

**Note**: Shop routes will be added with proper controllers in the **[Controllers](./controllers.md)** section.
:::

## Registering Routes with Service Provider

Now we need to register these route files with our RMA service provider so Laravel can load them.

Update your `packages/Webkul/RMA/src/Providers/RMAServiceProvider.php`:

```php{27-29}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
    }
}
```

::: tip Service Provider Loading Order
The `loadRoutesFrom()` method automatically registers your routes with Laravel's routing system. Routes are loaded during the application's boot process, making them available immediately.
:::

## Testing Your Routes

Verify your routes are properly registered and working:

```bash
# Test a route in your browser
# Visit: http://your-app.com/admin/rma/return-requests (will show "Admin RMA Return Requests List")
```

::: info Route Testing Tips
**Verification Commands:**
- Check route registration: `php artisan route:list | grep rma`
- Test route generation: `php artisan tinker` then `route('admin.rma.return-requests.index')`
- Visit routes in browser to see callback responses
- Verify middleware: Look for middleware column in route:list output
:::

## Your Next Step

With your routes defined using callback functions, you now have a working URL structure for your RMA package. These routes currently return simple strings to demonstrate the routing concept.

In the **Controllers** section, we'll create proper controller classes that use the repository we built earlier, and then **update these routes** to use the controllers instead of callback functions.

**Continue to:** **[Controllers](./controllers.md)** - Build controllers and update your routes to use them

# Controllers

Controllers in Laravel act as the bridge between your routes and business logic, handling HTTP requests and coordinating with repositories to return appropriate responses. In Bagisto, controllers follow a structured approach that separates admin panel functionality from storefront operations while integrating seamlessly with the repository pattern.

For our RMA package, we'll create controllers that handle the admin interface for managing return requests, using the repository we built earlier to interact with our data.

::: info Learning Objective
This section demonstrates how to create organized, maintainable controllers that use dependency injection with repositories and follow Bagisto's architectural patterns for both admin and shop functionality.
:::

For detailed information on Laravel controllers, visit the [Laravel Documentation on Controllers](https://laravel.com/docs/12.x/controllers).

## Bagisto Controller Architecture

Bagisto follows a structured approach to controller organization:

### Admin Controllers
- **Purpose**: Handle administrative functionality for your package
- **Location**: `Http/Controllers/Admin/` directory
- **Features**: Full CRUD operations, data management, repository integration
- **Access**: Protected by admin middleware

### Shop Controllers  
- **Purpose**: Handle customer-facing functionality
- **Location**: `Http/Controllers/Shop/` directory
- **Features**: Customer interactions, limited operations, public interfaces
- **Access**: Protected by shop middleware

## Creating Controller Structure

Let's create the controller structure for our RMA package, starting with the basic setup and then implementing the index functionality.

### Directory Structure

Create the following directory structure in your package:

```bash
mkdir -p packages/Webkul/RMA/src/Http/Controllers/Admin
mkdir -p packages/Webkul/RMA/src/Http/Controllers/Shop
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Http
                └── Controllers
                    ├── Controller.php
                    ├── Admin
                    │   └── ReturnRequestController.php
                    └── Shop
                        └── ReturnRequestController.php
```

### Base Controller

Create `packages/Webkul/RMA/src/Http/Controllers/Controller.php`:

```php
<?php

namespace Webkul\RMA\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
```

::: info Base Controller Explanation
**Purpose**: Provides common functionality for all controllers in your package

**Traits Used:**
- **AuthorizesRequests**: Enables authorization policies and gates
- **DispatchesJobs**: Allows dispatching queued jobs
- **ValidatesRequests**: Provides request validation capabilities

**Inheritance**: Extends Laravel's base controller while maintaining package isolation
:::

## Creating Controllers

Now let's create the actual controllers that will handle our RMA functionality, starting with the essential index method.

### Admin Controller

Create `packages/Webkul/RMA/src/Http/Controllers/Admin/ReturnRequestController.php`:

```php
<?php

namespace Webkul\RMA\Http\Controllers\Admin;

use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Repositories\ReturnRequestRepository;

class ReturnRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ReturnRequestRepository $returnRequestRepository
    ) {}

    /**
     * Display a listing of return requests.
     */
    public function index()
    {
        // For now, return a simple response
        // We'll enhance this with views in the Views section
        return 'Admin RMA Return Requests List - Using Controller!';
    }
}
```

::: info Admin Controller Explanation
**Key Components:**

- **Dependency Injection**: Repository injected via constructor using PHP 8 property promotion
- **Namespace**: Organized under `Admin` for clear separation
- **Base Class**: Extends our package's base controller
- **Index Method**: Simple implementation that will be enhanced with views later

**Repository Integration**: The controller uses the repository we created earlier for data access
:::

### Shop Controller

Create `packages/Webkul/RMA/src/Http/Controllers/Shop/ReturnRequestController.php`:

```php
<?php

namespace Webkul\RMA\Http\Controllers\Shop;

use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Repositories\ReturnRequestRepository;

class ReturnRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ReturnRequestRepository $returnRequestRepository
    ) {}

    /**
     * Display a listing of customer return requests.
     */
    public function index()
    {
        // For now, return a simple response
        // We'll enhance this with views in the Views section
        return 'Shop RMA Return Requests List - Using Controller!';
    }
}
```

::: info Shop Controller Explanation
**Key Components:**

- **Customer Focus**: Designed for customer-facing functionality
- **Limited Scope**: Typically fewer operations than admin controllers
- **Same Structure**: Follows the same dependency injection pattern as admin controller

**Future Enhancement**: Will be expanded with customer-specific functionality in later sections
:::

## Updating Routes to Use Controllers

Now that we have our controllers, let's update the route files we created earlier to use these controllers instead of callback functions.

### Update Admin Routes

Update `packages/Webkul/RMA/src/Routes/admin-routes.php`:

```php{17-18}
<?php

use Illuminate\Support\Facades\Route;
use Webkul\RMA\Http\Controllers\Admin\ReturnRequestController;

Route::group([
    'middleware' => ['web', 'admin'], 
    'prefix' => config('app.admin_url')
], function () {
    /**
     * Return request routes.
     */
    Route::prefix('rma/return-requests')->group(function () {
        /**
         * List return requests.
         */
        Route::get('', [ReturnRequestController::class, 'index'])
            ->name('admin.rma.return-requests.index');
    });
});
```

### Update Shop Routes

Update `packages/Webkul/RMA/src/Routes/shop-routes.php`:

```php{16-17}
<?php

use Illuminate\Support\Facades\Route;
use Webkul\RMA\Http\Controllers\Shop\ReturnRequestController;

Route::group([
    'middleware' => ['web', 'locale', 'theme', 'currency']
], function () {
    /**
     * Customer return request routes.
     */
    Route::prefix('rma/return-requests')->group(function () {
        /**
         * List customer return requests.
         */
        Route::get('', [ReturnRequestController::class, 'index'])
            ->name('shop.rma.return-requests.index');
    });
});
```

::: tip Route Update Benefits
**Before**: Routes used callback functions that returned simple strings

**After**: Routes now use proper controllers with dependency injection and repository access

This change enables us to add complex business logic, data retrieval, and view rendering as we continue developing the package.
:::

## Testing Your Controllers

Verify your controllers are working correctly:

```bash
# Test the routes in your browser
# Admin: http://your-app.com/admin/rma/return-requests
# Shop: http://your-app.com/rma/return-requests
```

You should now see:
- **Admin Route**: "Admin RMA Return Requests List - Using Controller!"
- **Shop Route**: "Shop RMA Return Requests List - Using Controller!"

::: info Testing Tips
**Verification Commands:**
- Check routes are updated: `php artisan route:list | grep rma`
- Test dependency injection: Controllers should load without errors
- Verify repository access: No "class not found" errors indicate successful injection
:::

## Your Next Step

With your controllers created and routes updated, you now have a working controller layer that integrates with your repository. The next logical step is to create views that will replace the simple string responses with proper HTML interfaces.

**Continue to:** **[Views](./views.md)** - Create admin panel interfaces for your RMA package

# Views

Views in Laravel provide a clean separation between application logic and presentation layer, using the powerful Blade templating engine to create dynamic, maintainable interfaces. In Bagisto, views are organized to support both admin panel functionality and customer-facing storefront operations while maintaining consistency with Bagisto's design patterns.

For our RMA package, we'll create views that display return request listings and forms, integrating seamlessly with Bagisto's existing admin interface and storefront design.

::: info Learning Objective
This section demonstrates how to create organized, reusable Blade templates that integrate with Bagisto's admin interface and follow established patterns for data presentation, starting with listing pages and progressing to form creation.
:::

For detailed information on Laravel views and Blade templating, visit the [Laravel Documentation on Views](https://laravel.com/docs/12.x/views).

## Bagisto View Architecture

Bagisto follows a structured approach to view organization that separates administrative interfaces from customer-facing pages:

### Admin Views
- **Purpose**: Administrative interfaces for managing package features
- **Integration**: Extends Bagisto's admin layout and components
- **Features**: Data tables, forms, modals, CRUD operations
- **Styling**: Uses Bagisto's admin CSS framework and Vue components

### Shop Views  
- **Purpose**: Customer-facing interfaces for package functionality
- **Integration**: Uses storefront theme and layout components
- **Features**: Customer interactions, responsive design, theme compatibility
- **Styling**: Inherits from active storefront theme

## Creating View Structure

Let's create the view structure for our RMA package, starting with the essential directory organization and then building the listing functionality.

### Directory Structure

Create the following directory structure in your package:

```bash
mkdir -p packages/Webkul/RMA/src/Resources/views/admin/return-requests
mkdir -p packages/Webkul/RMA/src/Resources/views/shop/return-requests
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Resources
                └── views
                    ├── admin
                    │   └── return-requests
                    │       ├── index.blade.php
                    │       └── create.blade.php
                    └── shop
                        └── return-requests
                            ├── index.blade.php
                            └── create.blade.php
```

::: info View Organization Strategy
**Admin Views**: Organized under `admin/return-requests/` for clear feature separation

**Shop Views**: Located under `shop/return-requests/` for customer-facing functionality

**Naming Convention**: Uses descriptive folder names (`return-requests`) instead of generic terms for better organization

**Scalability**: Structure supports adding more views (edit, show, etc.) as the package grows
:::

## Registering Views with Service Provider

Before creating view templates, we need to register our views with the service provider so Laravel can find them.

Update your `packages/Webkul/RMA/src/Providers/RMAServiceProvider.php`:

```php{30-31}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
    }
}
```

::: tip View Namespace
The `loadViewsFrom()` method registers views with the `rma` namespace, allowing you to reference them as `rma::admin.return-requests.index` instead of using full file paths.
:::

## Creating Admin Listing View

Let's start with the most important view - the admin listing page that displays all return requests. This view will integrate with Bagisto's admin interface and display data from our repository.

### Admin Index View

Create `packages/Webkul/RMA/src/Resources/views/admin/return-requests/index.blade.php`:

```blade
<x-admin::layouts>
    <x-slot:title>
        RMA Listing Title
    </x-slot:title>

    RMA Listing Content
</x-admin::layouts>
```

::: info Admin View Explanation
**Key Components:**

- **Bagisto Layout**: Uses `<x-admin::layouts>` for consistent admin interface
- **Basic Structure**: Simple title and content placeholders to demonstrate layout integration
- **Component Integration**: Shows how to use Bagisto's slot-based layout system
- **Scalable Foundation**: Structure supports adding DataGrid, forms, and other components later

**Note on Localization**: You'll notice we haven't used any `@lang()` or `trans()` methods in these views. We're keeping the views simple at this stage and will cover comprehensive localization techniques in the **[Localization](./localization.md)** section.
:::

## Updating Controllers to Use Views

Now let's update our controllers to render these views instead of returning simple strings.

### Update Admin Controller

Update `packages/Webkul/RMA/src/Http/Controllers/Admin/ReturnRequestController.php`:

```php{22-24}
<?php

namespace Webkul\RMA\Http\Controllers\Admin;

use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Repositories\ReturnRequestRepository;

class ReturnRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ReturnRequestRepository $returnRequestRepository
    ) {}

    /**
     * Display a listing of return requests.
     */
    public function index()
    {
        // For now, we'll render the view without data
        // In a later section, we'll add DataGrid functionality for data loading
        return view('rma::admin.return-requests.index');
    }
}
```

::: tip Controller Update
We've updated the controller to render the Blade view instead of returning a string. This demonstrates the basic integration between routes, controllers, and views in the Bagisto architecture.
:::

## Creating Shop Views

For the shop section, you can create views following the same pattern as the admin views. Since we're focusing on understanding the admin panel architecture in this section, we'll concentrate on the admin implementation.

### Shop View Structure

The shop views would follow a similar structure:

```bash
# Shop view creation (for reference)
# packages/Webkul/RMA/src/Resources/views/shop/return-requests/index.blade.php
```

```blade
<x-shop::layouts>
    <x-slot:title>
        RMA Shop Listing Title
    </x-slot:title>

    RMA Shop Listing Content
</x-shop::layouts>
```

::: tip Shop Implementation
The shop views follow the same principles as admin views but use `<x-shop::layouts>` for storefront integration. For now, we're focusing on the admin panel to understand the core concepts before expanding to customer-facing functionality.
:::

## Testing Your Views

Test your views are working correctly:

```bash
# Clear cache to ensure views are loaded
php artisan optimize:clear

# Test the admin route in your browser
# Admin: http://your-app.com/admin/rma/return-requests
```

You should now see:
- **Admin Route**: Basic admin interface with Bagisto layout displaying "RMA Listing Title" and "RMA Listing Content"

::: info Testing Tips
- Check views load without errors
- Verify styling matches Bagisto's admin interface
- Test that layout components render correctly
- Ensure views display the basic title and content placeholders
:::

## Your Next Step

With your basic admin views complete, you now have a foundation presentation layer for your RMA package. These simple views demonstrate how to integrate with Bagisto's admin layout system.

The views currently show basic content placeholders, which is perfect for this stage of learning. In subsequent sections, you'll expand these views with more advanced features and multi-language support.

**Continue to:** **[Localization](./localization.md)** - Add multi-language support to your RMA package views and content

::: tip Learning Approach
Starting with basic admin layouts helps you understand Bagisto's component system before adding complexity. This foundation will make it easier to implement advanced features like localization, datagrids, and forms in later sections.
:::

# Localization

Localization in Laravel enables your application to support multiple languages and regional settings, making your package accessible to a global audience. In Bagisto, localization is deeply integrated into the system, supporting both frontend customer interfaces and backend administrative panels with consistent translation patterns.

For our RMA package, we'll implement comprehensive localization that covers admin panel labels, customer-facing messages, email notifications, and validation messages, demonstrating how to create a truly international e-commerce extension.

::: info Learning Objective
This section demonstrates how to create organized, maintainable translation files for your Bagisto package, register them properly with the service provider, and use them effectively in views, controllers, and other components.
:::

For detailed information on Laravel localization features, visit the [Laravel Documentation on Localization](https://laravel.com/docs/12.x/localization).

## Bagisto Localization Architecture

Bagisto's localization system extends Laravel's built-in functionality with additional features for e-commerce applications:

### Translation Organization
- **Admin Translations**: Interface elements, form labels, buttons, and messages for administrative users
- **Shop Translations**: Customer-facing content, product information, checkout messages, and notifications
- **Email Translations**: Transactional emails, notifications, and communication templates
- **Validation Translations**: Custom validation messages specific to your package functionality

### Namespace Support
- **Package Namespacing**: Each package maintains its own translation namespace for isolation
- **Fallback System**: Graceful fallback to default language when translations are missing
- **Override Capability**: Ability to override core translations without modifying core files

### Multi-Language Features
- **RTL Support**: Right-to-left language support for Arabic, Hebrew, and other RTL languages
- **Currency Localization**: Automatic currency formatting based on locale settings
- **Date/Time Formatting**: Locale-aware date and time display throughout the interface

## Understanding Laravel Localization Basics

Before diving into Bagisto-specific implementation, let's understand the foundational concepts that power Laravel's localization system.

### Publish Laravel Language Files (Optional)

Laravel's default installation doesn't include the `lang` directory in your project root. If you need to customize Laravel's own error messages and validation text, you can publish them:

```bash
php artisan lang:publish
```

This creates a `lang` directory in your project root with Laravel's default English translations, which you can then modify or use as templates for other languages.

::: tip When to Use This
You typically only need to publish Laravel's language files if you want to customize framework-level messages like validation errors, authentication messages, or HTTP status messages.
:::

### Configure Application Locale

Set your default and fallback locales in `config/app.php`. These settings affect how Laravel resolves translations throughout your application:

```php
/*
|--------------------------------------------------------------------------
| Application Locale Configuration
|--------------------------------------------------------------------------
|
| The application locale determines the default locale that will be used
| by the translation service provider. You are free to set this value
| to any of the locales which will be supported by the application.
|
*/

'locale' => env('APP_LOCALE', 'en'),

'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
```

::: info Locale Configuration Explanation
**Application Locale**: The primary language your application will use by default.

**Fallback Locale**: When a translation key is missing in the current locale, Laravel will attempt to find it in the fallback locale before displaying the key itself.

**Environment Override**: Using `env('APP_LOCALE', 'en')` allows you to set different default locales for different environments (staging, production, etc.).
:::

## Creating Package Localization Structure

Now let's create a comprehensive localization structure for our RMA package that covers all the different types of content we'll need to translate.

### Directory Structure

Create a simple language directory structure for our basic translations:

```bash
mkdir -p packages/Webkul/RMA/src/Resources/lang/en
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Resources
                └── lang
                    └── en
                        └── app.php
```

::: info Simple Translation Structure
**app.php**: Contains the basic translations we need for our simple admin view

**Starting Simple**: We're beginning with just English and one file to match our basic view implementation

**Expandable**: This structure can easily be expanded with more languages and specialized files as your package grows
:::

### Creating Translation Files

Let's create a simple translation file that matches our basic view implementation.

#### Basic Translation File

Create `packages/Webkul/RMA/src/Resources/lang/en/app.php` with just the translations we need for our basic view:

```php
<?php

return [
    'admin' => [
        'return-requests' => [
            'title' => 'RMA Listing Title',
            'content' => 'RMA Listing Content',
        ],
    ],
];
```

::: tip Simple Start
We're keeping the translations minimal to match our basic view implementation. This demonstrates the core concept without overwhelming complexity. As you add more features to your views, you can expand these translation files accordingly.
:::

## Registering Translations with Service Provider

Register your package translations in the service provider so they're available throughout the application. This step is crucial for Laravel to recognize and load your translation files.

Update `packages/Webkul/RMA/src/Providers/RMAServiceProvider.php`:

```php{32-33}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
        
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'rma');
    }
}
```

::: tip Translation Namespace
The `loadTranslationsFrom()` method registers translations with the `rma` namespace. This means you'll reference translation keys as `rma::admin.title` or `rma::shop.create-return` in your application.
:::

### Publishing Translations (Optional)

If you want to allow users to customize your package's translations, you can make them publishable:

```php
// Add to the boot() method in RMAServiceProvider
$this->publishes([
    __DIR__ . '/../Resources/lang' => resource_path('lang/vendor/rma'),
], 'rma-translations');
```

Users can then publish and customize your translations:

```bash
php artisan vendor:publish --tag=rma-translations
```

::: info Publishing Benefits
**Customization**: Users can modify translations without editing your package files

**Override System**: Published translations take precedence over package translations

**Maintenance**: Users can update your package without losing their translation customizations

**Localization**: Teams can add new languages without modifying the original package
:::

## Using Translations in Your Package

Now that we've set up our translation structure, let's see how to use these translations in different parts of your RMA package.

### In Blade Templates

Update your views to use translations instead of hardcoded text. Let's update the admin index view we created earlier to use our simple translations:

**Update:** `packages/Webkul/RMA/src/Resources/views/admin/return-requests/index.blade.php`

```blade{3,6}
<x-admin::layouts>
    <x-slot:title>
        @lang('rma::app.admin.return-requests.title')
    </x-slot:title>

    @lang('rma::app.admin.return-requests.content')
</x-admin::layouts>
```

::: tip Matching Our Basic View
This translation implementation perfectly matches our simple view from the Views section, demonstrating how to replace hardcoded strings with translation keys without adding unnecessary complexity.
:::

### In Controllers

Use translations in controller methods for basic responses:

```php{16-20}
<?php

namespace Webkul\RMA\Http\Controllers\Admin;

use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Repositories\ReturnRequestRepository;

class ReturnRequestController extends Controller
{
    public function __construct(
        protected ReturnRequestRepository $returnRequestRepository
    ) {}

    public function index()
    {
        // Test accessing a translation
        $title = trans('rma::app.admin.return-requests.title');

        // This is just for demonstration
        dd($title);

        return view('rma::admin.return-requests.index');
    }
}
```

::: tip Simple Controller Integration
For now, our controller simply renders the view. As we add more functionality like create, edit, and delete operations, we'll expand the translation usage for success messages and validation feedback.
:::

### Translation Helper Functions

Laravel provides several helper functions for accessing translations:

```php
// Using trans() function
$title = trans('rma::app.admin.return-requests.title');

// Using __() helper (shorter syntax)
$content = __('rma::app.admin.return-requests.content');

// Using @lang directive in Blade templates (already shown above)
@lang('rma::app.admin.return-requests.title')
```

::: tip Translation Best Practices
**Consistent Naming**: Use descriptive, hierarchical keys that reflect your content structure

**Avoid Hardcoding**: Replace hardcoded strings with translation keys for better maintainability

**Fallback Values**: Laravel automatically falls back to the key name if translation is missing

**Simple Start**: Begin with basic translations and expand as your package features grow
:::

## Testing Your Translations

Verify your translations are working correctly:

```bash
# Clear cache to ensure translations are loaded
php artisan optimize:clear

# Test different language settings
php artisan tinker
```

Now test translation functionality in the tinker console:

```php
// Test in tinker
App::setLocale('en');
echo __('rma::app.admin.return-requests.title'); // Should output: "RMA Listing Title"

// Test fallback behavior
App::setLocale('fr'); 
echo __('rma::app.admin.return-requests.title'); // Should fallback to English since French not defined
```

::: info Testing Tips
**Route Testing**: Visit your admin routes to see translations in action

**Language Switching**: Test fallback behavior when translations are missing

**Cache Clearing**: Always clear cache after adding new translation files
:::

## Your Next Step

With comprehensive localization implemented, your RMA package now supports multiple languages and provides a professional, international user experience. The translation system you've built provides a solid foundation for expanding to additional languages and regions.

You've successfully created:
- **Organized translation files** for different interface sections
- **Service provider registration** for automatic translation loading  
- **Practical examples** of translation usage in views and controllers
- **Testing strategies** for verifying translation functionality

The localization system is now ready to support your package's growth and international expansion.

**Continue to:** **[DataGrid](./datagrid.md)** - Learn how to create data tables with sorting, filtering, and pagination for your admin interface

::: tip Internationalization Strategy
As your package grows, consider creating translation files for major e-commerce markets (Spanish, French, German, Arabic) and implementing locale-specific features like RTL support and currency formatting.
:::

# DataGrid

DataGrid is one of the most powerful features in Bagisto for displaying and managing tabular data in admin interfaces. It provides built-in functionality for sorting, filtering, pagination, and mass actions, transforming your raw database records into professional, interactive data tables.

For our RMA package, we'll create a comprehensive DataGrid that displays return requests with full administrative functionality, demonstrating how to build efficient data management interfaces that scale with your business needs.

::: info Learning Objective
This section demonstrates how to create a fully functional DataGrid for your Bagisto package, including data presentation, filtering capabilities, and administrative actions - essential skills for building professional admin interfaces.
:::

## Understanding Bagisto DataGrid Architecture

Bagisto's DataGrid system is built on Laravel's query builder and provides a powerful abstraction layer for creating data tables:

### Core Components
- **Abstract DataGrid Class**: Base functionality that all custom DataGrids extend
- **Query Builder Integration**: Seamless integration with Laravel's database layer  
- **Column Management**: Flexible column definitions with type-specific handling
- **Action System**: Built-in support for row actions and mass operations

### Key Features
- **Automatic Pagination**: Built-in pagination with customizable page sizes
- **Advanced Filtering**: Column-specific filters with multiple data types
- **Sorting Capabilities**: Multi-column sorting with intelligent defaults
- **Export Functionality**: Built-in CSV/Excel export capabilities
- **Mass Actions**: Bulk operations on selected records

### DataGrid Properties

Understanding the core properties helps you customize DataGrid behavior:

| Property          | Functionality                                                                                   |
| ----------------- | ----------------------------------------------------------------------------------------------- |
| **`primaryColumn`**    | Specifies the primary identifier column for the data grid, typically set to `'id'` for unique identification of data entries. |
| **`queryBuilder`**     | Manages the database query operations for fetching data based on configured criteria. |
| **`columns`**          | Array defining the columns to be displayed in the data grid. |
| **`sortColumn`**       | Optional. Specifies the default column used for sorting data in the grid.  |
| **`sortOrder`**        | Specifies the default order ('asc' or 'desc') for sorting data in the grid. |
| **`actions`**          | Array containing configurations for actions that can be performed on individual data grid entries. |
| **`massActions`**      | Array defining actions that can be applied to multiple entries simultaneously in the data grid. |
| **`paginator`**        | Stores an instance of `LengthAwarePaginator` for managing pagination of grid data. |
| **`itemsPerPage`**     | Specifies the default number of items to display per page in the data grid.|
| **`perPageOptions`**   | Array of options allowing users to select different numbers of items per page.  |
| **`exportable`**       | Boolean indicating whether the data grid can exported.  |
| **`exportFile`**       | Stores metadata related to exported data if `exportable` is enabled. |

## Creating Your First DataGrid

Let's create a DataGrid for our RMA package that displays return requests in the admin panel. This will demonstrate the complete process from creation to implementation.

### Directory Structure

Create the DataGrid directory structure in your package:

```bash
mkdir -p packages/Webkul/RMA/src/DataGrids/Admin
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── DataGrids
                └── Admin
                    └── ReturnRequestDataGrid.php
```

### Creating the DataGrid Class

Create `packages/Webkul/RMA/src/DataGrids/Admin/ReturnRequestDataGrid.php`:

```php
<?php

namespace Webkul\RMA\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ReturnRequestDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('rma_requests')
            ->select('id');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('rma::app.admin.return-requests.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
        ]);
    }
}
```

::: info Simple DataGrid Explanation
**Query Builder**: Selects only the `id` column from our `rma_requests` table - keeping it simple

**Single Column**: Just the ID column to demonstrate the basic DataGrid concept

**No Actions**: We're focusing on data display first, actions can be added later

**Basic Properties**: Only essential properties - searchable false, sortable true, filterable false

**No Mass Actions**: Keeping it minimal to understand the core concept first
:::

## Adding DataGrid Translations

Create the necessary translation keys for your DataGrid by updating `packages/Webkul/RMA/src/Resources/lang/en/app.php`:

```php{8-11}
<?php

return [
    'admin' => [
        'return-requests' => [
            'title' => 'RMA Listing Title',
            'content' => 'RMA Listing Content',
            
            'datagrid' => [
                'id' => 'ID',
            ],
        ],
    ],
];
```

::: tip Organized Translation Structure
The DataGrid translations are nested under `return-requests` since they're specific to the return requests DataGrid. This keeps translations organized and makes it clear which section they belong to.
:::

## Integrating DataGrid with Controller

Update your admin controller to use the DataGrid:

**Update:** `packages/Webkul/RMA/src/Http/Controllers/Admin/ReturnRequestController.php`

```php{23-26}
<?php

namespace Webkul\RMA\Http\Controllers\Admin;

use Webkul\RMA\Http\Controllers\Controller;
use Webkul\RMA\Repositories\ReturnRequestRepository;
use Webkul\RMA\DataGrids\Admin\ReturnRequestDataGrid;

class ReturnRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ReturnRequestRepository $returnRequestRepository
    ) {}

    /**
     * Display a listing of return requests.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ReturnRequestDataGrid::class)->process();
        }

        return view('rma::admin.return-requests.index');
    }
}
```

::: tip DataGrid Integration
**AJAX Processing**: When the request is AJAX (DataGrid pagination, filtering, sorting), the `datagrid()` helper processes the request and returns JSON data

**View Rendering**: For regular page loads, it renders the Blade view that contains the DataGrid component

This pattern allows the same controller method to handle both initial page loads and subsequent DataGrid operations.
:::

## Creating DataGrid Views

Create the admin view that will display your DataGrid. First, ensure the views directory structure exists:

```bash
mkdir -p packages/Webkul/RMA/src/Resources/views/admin/return-requests
```

**Create:** `packages/Webkul/RMA/src/Resources/views/admin/return-requests/index.blade.php`

```blade{5-6}
<x-admin::layouts>
    <x-slot:title>
        @lang('rma::app.admin.return-requests.title')
    </x-slot:title>

    <x-admin::datagrid :src="route('admin.rma.return-requests.index')" />
</x-admin::layouts>
```

::: info Simple View Explanation
**Layout Component**: Uses Bagisto's admin layout with consistent styling

**Basic Header**: Just shows the page title without additional buttons

**DataGrid Component**: The `<x-admin::datagrid>` component renders the DataGrid interface

**Minimal Structure**: No permissions, no create buttons, no events - just the essential DataGrid display
:::

## Creating Sample Data

Before we can see our DataGrid in action, let's create some simple return request records using Laravel Tinker:

```bash
php artisan tinker
```

```php
// Create simple return requests - we only need basic data for our ID-only DataGrid
DB::table('rma_requests')->insert([
    [
        'customer_id' => 1,
        'order_id' => 1,
        'product_sku' => 'LAPTOP-001',
        'product_name' => 'Gaming Laptop',
        'product_quantity' => 1,
        'reason' => 'Defective screen',
        'status' => 'pending',
        'admin_notes' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'customer_id' => 2,
        'order_id' => 5,
        'product_sku' => 'PHONE-002',
        'product_name' => 'Smartphone Pro',
        'product_quantity' => 1,
        'reason' => 'Wrong color received',
        'status' => 'approved',
        'admin_notes' => null,
        'created_at' => now()->subDays(2),
        'updated_at' => now()->subDays(1),
    ],
    [
        'customer_id' => 3,
        'order_id' => 8,
        'product_sku' => 'HEADPHONES-003',
        'product_name' => 'Wireless Headphones',
        'product_quantity' => 2,
        'reason' => 'Not as described',
        'status' => 'rejected',
        'admin_notes' => null,
        'created_at' => now()->subDays(5),
        'updated_at' => now()->subDays(3),
    ],
]);

// Verify the data was created
DB::table('rma_requests')->count(); // Should return 3

// Check the IDs were created correctly (this is what our DataGrid will show)
DB::table('rma_requests')->select('id')->get();
```

::: tip Simple Data for Simple DataGrid
**Complete Records**: We still need all the required fields for the database, but our DataGrid will only display the ID

**Focus on IDs**: The main point is to see that our DataGrid can display the ID column from these records

**Three Records**: Just enough to see that the DataGrid is working and displaying multiple rows

**Next Steps**: Once this basic DataGrid works, you can add more columns and features gradually
:::

## Testing Your DataGrid

Now you can test your basic DataGrid functionality:

```bash
# Clear cache and visit the admin route
php artisan optimize:clear

# Visit in browser: http://your-app.com/admin/rma/return-requests
```

**Expected DataGrid Features:**
- **Data Display**: All sample record IDs shown in a simple table
- **Column Sorting**: Click the "ID" column header to sort the records
- **Basic Pagination**: Navigation controls if you have many records
- **Clean Interface**: Simple, professional table showing just the essential data

You should see a clean table with just one column showing the IDs: 1, 2, 3 (or whatever IDs were generated).

::: info Simple DataGrid Testing Checklist
**Core Functionality:**
- ✅ Records display correctly in a table
- ✅ ID column shows the correct values
- ✅ Sorting works when clicking the ID column header
- ✅ Page loads without errors
- ✅ DataGrid displays with proper styling

**What's NOT included (and that's okay!):**
- ❌ Multiple columns - we're keeping it simple
- ❌ Filtering options - not needed for basic concept
- ❌ Search functionality - will add later
- ❌ Actions buttons - focusing on data display first
- ❌ Mass actions - advanced feature for later
:::

::: tip Success Indicators
If you see a table with a single "ID" column showing your record IDs (1, 2, 3), congratulations! Your basic DataGrid is working perfectly. This foundation can now be expanded with additional columns and features as you become more comfortable with the DataGrid system.
:::

## Understanding DataGrid Methods

Now that you've seen your basic DataGrid working, let's understand how the DataGrid methods work and how you can expand them:

### prepareQueryBuilder()

This method defines what data to retrieve from the database. In our simple example:

```php
public function prepareQueryBuilder()
{
    $queryBuilder = DB::table('rma_requests')
        ->select('id'); // We only select ID for our simple DataGrid

    return $queryBuilder;
}
```

For more complex DataGrids, you might select multiple columns:

```php
public function prepareQueryBuilder()
{
    $queryBuilder = DB::table('rma_requests')
        ->select(
            'id',
            'customer_id',
            'product_sku',
            'product_name',
            'status',
            'created_at'
        );

    return $queryBuilder;
}
```

::: tip Query Builder Best Practices
**Select Only What You Need**: Only select columns that will be displayed or used for filtering

**Use Joins Wisely**: Add joins for related data but be mindful of performance

**Apply Default Filters**: Add conditions like `->where('deleted_at', null)` if needed

**Optimize for Large Datasets**: Consider indexing frequently filtered/sorted columns
:::

### prepareColumns()

This method defines how each column should behave. Our simple example:

```php
public function prepareColumns()
{
    $this->addColumn([
        'index'      => 'id',
        'label'      => trans('rma::app.admin.return-requests.datagrid.id'),
        'type'       => 'integer',
        'searchable' => false,
        'sortable'   => true,
        'filterable' => false,
    ]);
}
```

#### Column Configuration Options

| Key                     | Type | Description |
| ----------------------- | ---- | ----------- |
| **`index`**             | String | Database column name or alias |
| **`label`**             | String | Column header text (use translations) |
| **`type`**              | String | Data type: `string`, `integer`, `decimal`, `boolean`, `date`, `datetime` |
| **`searchable`**        | Boolean | Enable text search for this column |
| **`sortable`**          | Boolean | Enable column sorting |
| **`filterable`**        | Boolean | Enable column filtering |
| **`filterable_type`**   | String | Filter type: `dropdown`, `date_range`, `datetime_range` |
| **`filterable_options`** | Array | Options for dropdown filters |
| **`closure`**           | Function | Custom formatting function |

### prepareActions() (Optional)

This method defines row-level actions (like edit, delete buttons). We didn't include this in our basic example:

```php
public function prepareActions()
{
    $this->addAction([
        'index'  => 'edit',
        'icon'   => 'icon-edit',
        'title'  => 'Edit',
        'method' => 'GET',
        'url'    => function ($row) {
            return route('admin.rma.return-requests.edit', $row->id);
        },
    ]);
}
```

### prepareMassActions() (Optional)

This method defines bulk operations. Also not included in our basic example:

```php
public function prepareMassActions()
{
    $this->addMassAction([
        'icon'   => 'icon-delete',
        'title'  => 'Delete Selected',
        'method' => 'POST',
        'url'    => route('admin.rma.return-requests.mass-delete'),
    ]);
}
```

## Expanding Your DataGrid

Once your basic DataGrid is working, you can gradually add more features. Let's walk through each enhancement step-by-step:

### Adding More Columns

Let's add a product name column to make our DataGrid more useful. This requires updating three parts:

#### Step 1: Update the Query Builder

First, modify your `prepareQueryBuilder()` method to select the additional column:

```php{4}
public function prepareQueryBuilder()
{
    $queryBuilder = DB::table('rma_requests')
        ->select('id', 'product_name'); // Add product_name to selection

    return $queryBuilder;
}
```

#### Step 2: Add the Column Definition

Add the new column to your `prepareColumns()` method:

```php{11-19}
public function prepareColumns()
{
    $this->addColumn([
        'index'      => 'id',
        'label'      => trans('rma::app.admin.return-requests.datagrid.id'),
        'type'       => 'integer',
        'searchable' => false,
        'sortable'   => true,
        'filterable' => false,
    ]);

    $this->addColumn([
        'index'      => 'product_name',
        'label'      => trans('rma::app.admin.return-requests.datagrid.product-name'),
        'type'       => 'string',
        'searchable' => true,
        'sortable'   => true,
        'filterable' => false,
    ]);
}
```

#### Step 3: Add Translation Keys

Update your translation file `packages/Webkul/RMA/src/Resources/lang/en/app.php`:

```php{3}
'datagrid' => [
    'id' => 'ID',
    'product-name' => 'Product Name',
],
```

Now your DataGrid will display both ID and Product Name columns with search functionality enabled for the product name.

### Adding Status Column with Dropdown Filter

Let's add a status column that demonstrates filtering capabilities:

#### Step 1: Update Query Builder

```php{4}
public function prepareQueryBuilder()
{
    $queryBuilder = DB::table('rma_requests')
        ->select('id', 'product_name', 'status'); // Add status column

    return $queryBuilder;
}
```

#### Step 2: Add Status Column with Filter

```php{4-27}
public function prepareColumns()
{
    // ...existing columns...

    $this->addColumn([
        'index'              => 'status',
        'label'              => trans('rma::app.admin.return-requests.datagrid.status'),
        'type'               => 'string',
        'searchable'         => false,
        'sortable'           => true,
        'filterable'         => true,
        'filterable_type'    => 'dropdown',
        'filterable_options' => [
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.pending'),
                'value' => 'pending',
            ],
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.approved'),
                'value' => 'approved',
            ],
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.rejected'),
                'value' => 'rejected',
            ],
        ],
    ]);
}
```

#### Step 3: Add Status Translation

```php{4-7}
'datagrid' => [
    'id' => 'ID',
    'product-name' => 'Product Name',
    'status' => 'Status',
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
],
```

### Adding Custom Formatting with Closure

Let's enhance the status column to display status values with styled badges using the `closure` feature:

#### Step 1: Basic Badge Formatting

First, let's add a simple closure that displays all status values with a consistent badge style:

```php{4-30}
public function prepareColumns()
{
    // ...existing columns...

    $this->addColumn([
        'index'              => 'status',
        'label'              => trans('rma::app.admin.return-requests.datagrid.status'),
        'type'               => 'string',
        'searchable'         => false,
        'sortable'           => true,
        'filterable'         => true,
        'filterable_type'    => 'dropdown',
        'filterable_options' => [
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.pending'),
                'value' => 'pending',
            ],
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.approved'),
                'value' => 'approved',
            ],
            [
                'label' => trans('rma::app.admin.return-requests.datagrid.rejected'),
                'value' => 'rejected',
            ],
        ],
        'closure' => function ($row) {
            return "<span class='badge label-info'>" . ucfirst($row->status) . "</span>";
        },
    ]);
}
```

This simple closure transforms plain text like "pending" into a styled badge that displays as "Pending" with consistent styling.

#### Step 2: Understanding Basic Closure

The `closure` parameter allows you to:

- **Transform Data**: Convert raw database values into formatted display
- **Add HTML**: Return HTML elements like badges, links, or formatted text
- **Simple Logic**: Apply basic transformations like capitalization or formatting

#### Step 3: Advanced Conditional Styling (Optional)

Once you're comfortable with basic closures, you can enhance it with conditional styling:

```php{2-22}
'closure' => function ($row) {
    $statusConfig = [
        'pending' => [
            'label' => trans('rma::app.admin.return-requests.datagrid.pending'),
            'class' => 'label-pending'
        ],
        'approved' => [
            'label' => trans('rma::app.admin.return-requests.datagrid.approved'),
            'class' => 'label-active'
        ],
        'rejected' => [
            'label' => trans('rma::app.admin.return-requests.datagrid.rejected'),
            'class' => 'label-canceled'
        ],
    ];

    $config = $statusConfig[$row->status] ?? [
        'label' => ucfirst($row->status),
        'class' => 'label-info'
    ];

    return "<span class='badge {$config['class']}'>{$config['label']}</span>";
},
```

This advanced version provides different colors for each status type, making it easier to quickly identify status at a glance.

- **Custom Formatting**: Transform raw data into formatted display
- **HTML Output**: Return HTML elements like badges, links, or icons
- **Conditional Logic**: Apply different styling based on data values
- **Translation Integration**: Use translation keys for internationalization

#### Step 4: Common Closure Use Cases

Here are simple examples of other closure implementations you might use:

**Basic Date Formatting:**
```php
'closure' => function ($row) {
    return $row->created_at ? date('M d, Y', strtotime($row->created_at)) : '-';
},
```

**Simple Price Formatting:**
```php
'closure' => function ($row) {
    return '$' . number_format($row->price, 2);
},
```

**Basic Boolean Display:**
```php
'closure' => function ($row) {
    return $row->is_active ? 'Yes' : 'No';
},
```

**Text Truncation:**
```php
'closure' => function ($row) {
    return strlen($row->description) > 30 
        ? substr($row->description, 0, 30) . '...'
        : $row->description;
},
```

::: tip Closure Best Practices
**Start Simple**: Begin with basic transformations like capitalization or formatting

**Security**: Always escape user data when outputting HTML to prevent XSS attacks

**Performance**: Keep closure logic simple since it runs for every row

**Consistency**: Use consistent styling classes that match your admin theme

**Progression**: Master basic closures before moving to complex conditional logic
:::

::: info Why Use Closures in DataGrid?
**Better Display**: Transform plain database values into user-friendly formatted text

**Visual Enhancement**: Add styling and formatting without changing underlying data

**Flexibility**: Easy to modify display format without database changes

**User Experience**: Make data more readable and professional-looking

**Maintainability**: Keep formatting logic centralized in the DataGrid definition
:::

Now your status column will display neat badges instead of plain text, making it much easier for administrators to quickly scan the data.

### Adding Actions

When you're ready for user interactions, let's start by adding a simple view action to each row:

#### Step 1: Add Basic View Route

First, add a simple view route in your package's route file `packages/Webkul/RMA/src/Routes/admin-routes.php`:

```php{5-7}
Route::group(['middleware' => ['admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('rma')->group(function () {
            // ...existing routes...

            Route::get('return-requests/{id}', [ReturnRequestController::class, 'show'])
                ->name('admin.rma.return-requests.show');
        });
    });
});
```

#### Step 2: Add Basic Controller Method

Add the corresponding view method to your controller:

```php{4-13}
class ReturnRequestController extends Controller
{
    // ...existing methods...

    /**
     * Show the specified return request.
     */
    public function show($id)
    {
        $returnRequest = $this->returnRequestRepository->findOrFail($id);
        
        return view('rma::admin.return-requests.show', compact('returnRequest'));
    }
}
```

#### Step 3: Create the View Template

Create the view template for displaying individual return requests:

**Create:** `packages/Webkul/RMA/src/Resources/views/admin/return-requests/show.blade.php`

::: tip Quick Implementation
Don't worry about understanding every part of this view template right now. Simply copy and paste the code below - it's a standard admin detail page that follows Bagisto's design patterns. Focus on understanding how the DataGrid action connects to this view.
:::

```blade
<x-admin::layouts>
    <x-slot:title>
        @lang('rma::app.admin.return-requests.show.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('rma::app.admin.return-requests.show.title') #{{ $returnRequest->id }}
        </p>
    </div>

    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                    @lang('rma::app.admin.return-requests.show.general-info')
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('rma::app.admin.return-requests.show.product-name'):
                        </p>
                        <p class="text-gray-800 dark:text-white">
                            {{ $returnRequest->product_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('rma::app.admin.return-requests.show.status'):
                        </p>
                        <span class="badge label-info">
                            {{ ucfirst($returnRequest->status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('rma::app.admin.return-requests.show.reason'):
                        </p>
                        <p class="text-gray-800 dark:text-white">
                            {{ $returnRequest->reason }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('rma::app.admin.return-requests.show.created-at'):
                        </p>
                        <p class="text-gray-800 dark:text-white">
                            {{ $returnRequest->created_at }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
```

#### Step 4: Add Simple Action to DataGrid

Now add the `prepareActions()` method to your DataGrid with just the view action:

```php
public function prepareActions()
{
    $this->addAction([
        'icon'   => 'icon-view',
        'title'  => trans('rma::app.admin.return-requests.datagrid.view'),
        'method' => 'GET',
        'url'    => function ($row) {
            return route('admin.rma.return-requests.show', $row->id);
        },
    ]);
}
```

#### Step 5: Add View Translations

Update your translations to include the view page labels:

```php{8,10-18}
'datagrid' => [
    'id' => 'ID',
    'product-name' => 'Product Name',
    'status' => 'Status',
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'view' => 'View',
],

'show' => [
    'title' => 'View Return Request',
    'general-info' => 'General Information',
    'product-name' => 'Product Name',
    'status' => 'Status',
    'reason' => 'Reason',
    'created-at' => 'Created At',
],
```

Now your DataGrid will have a simple "View" button on each row that takes administrators to a detail page for that return request.

#### Step 6: Adding More Actions (Optional)

Once you're comfortable with basic actions, you can add edit and delete actions:

```php{11-28}
public function prepareActions()
{
    $this->addAction([
        'icon'   => 'icon-view',
        'title'  => trans('rma::app.admin.return-requests.datagrid.view'),
        'method' => 'GET',
        'url'    => function ($row) {
            return route('admin.rma.return-requests.show', $row->id);
        },
    ]);

    $this->addAction([
        'icon'   => 'icon-edit',
        'title'  => trans('rma::app.admin.return-requests.datagrid.edit'),
        'method' => 'GET',
        'url'    => function ($row) {
            return route('admin.rma.return-requests.edit', $row->id);
        },
    ]);

    $this->addAction([
        'icon'   => 'icon-delete',
        'title'  => trans('rma::app.admin.return-requests.datagrid.delete'),
        'method' => 'DELETE',
        'url'    => function ($row) {
            return route('admin.rma.return-requests.delete', $row->id);
        },
    ]);
}
```

::: warning Important Implementation Note
Remember to add the corresponding routes and controller methods for edit and delete functionality when you're ready to implement them. Without these routes and methods, the action buttons will result in 404 errors when clicked.
:::

### Adding Mass Actions

For bulk operations, add the `prepareMassActions()` method:

#### Step 1: Add Mass Delete Route

```php{5-7}
Route::group(['middleware' => ['admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('rma')->group(function () {
            // ...existing routes...

            Route::post('return-requests/mass-delete', [ReturnRequestController::class, 'massDestroy'])
                ->name('admin.rma.return-requests.mass-delete');
        });
    });
});
```

#### Step 2: Add Mass Delete Controller Method

```php{4-17}
class ReturnRequestController extends Controller
{
    // ...existing methods...

    /**
     * Mass delete return requests.
     */
    public function massDestroy()
    {
        $indices = request()->input('indices');
        
        foreach ($indices as $index) {
            $this->returnRequestRepository->delete($index);
        }
        
        return response()->json(['message' => 'Selected return requests deleted successfully.']);
    }
}
```

#### Step 3: Add Mass Actions to DataGrid

```php
public function prepareMassActions()
{
    $this->addMassAction([
        'icon'   => 'icon-delete',
        'title'  => trans('rma::app.admin.return-requests.datagrid.mass-delete'),
        'method' => 'POST',
        'url'    => route('admin.rma.return-requests.mass-delete'),
    ]);
}
```

#### Step 4: Add Mass Action Translation

```php{9}
'datagrid' => [
    'id' => 'ID',
    'product-name' => 'Product Name',
    'status' => 'Status',
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'view' => 'View',
    'mass-delete' => 'Delete Selected',
],
```

::: tip Gradual Enhancement
Start with just the ID column, then add one feature at a time. This approach helps you understand each DataGrid component without getting overwhelmed by complexity.
:::

## Your Next Step

Congratulations! You've successfully mastered DataGrid development in Bagisto.

Your foundation is solid! You can now build professional admin interfaces with powerful data management capabilities. The next logical step is to make your DataGrid accessible to administrators through proper navigation.

**Continue to:** **[Menu](./menu.md)** - Create admin menu entries so administrators can easily access your DataGrid

With DataGrid and menu integration complete, you'll have a fully functional admin interface that administrators will love to use. Each new concept builds upon what you've already learned, making your Bagisto packages more robust and user-friendly.

# Menu

In Bagisto, there are two types of menus to understand:

**Shop Menu**: The frontend navigation that customers see is managed through the **Categories** section in the admin panel. These menus are automatically generated from your product categories and don't require package development.

**Admin Menu**: This section focuses on the backend navigation that administrators use to manage the system. Specifically, we will cover how to create custom admin menu items for your package.

Admin menus provide navigation structure for your package's administrative interface in Bagisto. They allow administrators to easily access different sections and features of your package from the admin panel sidebar.

For our RMA package, we'll create a simple admin menu that provides access to the return requests listing page, demonstrating how to integrate your package seamlessly into Bagisto's admin navigation.

::: info Learning Objective
This section demonstrates how to create admin menu items for your Bagisto package, providing intuitive navigation for administrators to access your package's features.
:::

## Understanding Bagisto Admin Menu

Bagisto's admin menu system is hierarchical and provides multiple levels of navigation:

### Menu Levels
- **First Level (Sidebar)**: Primary navigation items in the left sidebar
- **Second Level (Dropdown)**: Sub-items that appear when hovering over first-level items
- **Third Level (Tabs)**: Additional tabs within specific admin pages

### Menu Components
- **Configuration**: PHP array defining menu structure
- **Routes**: Named routes that menu items link to
- **Icons**: CSS classes for menu item icons
- **Permissions**: Access control for menu visibility

## Creating Your First Admin Menu

Let's create a simple admin menu for our RMA package that provides access to the return requests listing page.

### Directory Structure

Create the configuration directory structure in your package:

```bash
mkdir -p packages/Webkul/RMA/src/Config
```

```text
packages
└── Webkul
    └── RMA
        └── src
            ├── ...
            └── Config
                └── admin-menu.php
```

### Creating the Menu Configuration

Create `packages/Webkul/RMA/src/Config/admin-menu.php`:

```php
<?php

return [
    [
        'key'   => 'rma',
        'name'  => 'RMA',
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 100, // Order position `100` places it near the end of the menu
        'icon'  => '',
    ],
];
```

::: info Simple Menu Explanation
**Key**: Unique identifier `rma` for our menu item

**Name**: Display text `RMA` that appears in the admin sidebar

**Route**: Points to our DataGrid listing page we created earlier

**Sort**: Order position `100` places it near the end of the menu

**Icon**: Left empty for this tutorial - you can add any icon class as per your requirement
:::

### Adding Menu Translations

Update your translation file `packages/Webkul/RMA/src/Resources/lang/en/app.php` to include menu translations:

```php{6-9}
<?php

return [
    'admin' => [
        // ...existing translations...
        
        'menu' => [
            'rma' => 'RMA',
        ],
    ],
];
```

Now update your menu configuration to use translations:

```php{6}
<?php

return [
    [
        'key'   => 'rma',
        'name'  => 'rma::app.admin.menu.rma',
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 100,
        'icon'  => '',
    ],
];
```

### Registering the Menu Configuration

Update your package's service provider to register the admin menu configuration:

**Update:** `packages/Webkul/RMA/src/Providers/RMAServiceProvider.php`

```php{14-17}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
        
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'rma');
    }
}
```

### Testing Your Admin Menu

Now you can test your admin menu:

```bash
# Clear cache
php artisan optimize:clear

# Visit the admin panel
# Login to: http://your-app.com/admin
```

**Expected Results:**
- **Menu Item Visible**: "RMA" appears in the admin sidebar
- **Navigation Works**: Clicking the menu takes you to the return requests DataGrid
- **Translation Active**: Menu uses translation keys for internationalization

::: info Simple Menu Testing Checklist
**Core Functionality:**
- ✅ RMA menu item appears in admin sidebar
- ✅ Clicking menu navigates to return requests page  
- ✅ No console errors or broken links
- ✅ Menu integrates seamlessly with existing Bagisto admin menus

**What's Working:**
- ✅ Simple, single-level menu item
- ✅ Direct link to your DataGrid page
- ✅ Translation support enabled
:::

## Understanding Menu Configuration Options

Now that you have a working menu, let's understand the configuration options available:

### Basic Menu Properties

| Property | Type | Description |
| -------- | ---- | ----------- |
| **`key`** | String | Unique identifier for the menu item |
| **`name`** | String | Display name (use translation keys) |
| **`route`** | String | Named Laravel route to link to |
| **`sort`** | Integer | Sort order (lower numbers appear first) |
| **`icon`** | String | CSS class for menu icon |
| **`info`** | String | Additional information or badge text |

### Creating Hierarchical Menu Structure

In Bagisto, hierarchical menus are created by using dot notation in the `key` property, not through nested arrays. Each menu level is defined as a separate array item. Here's how to create a multi-level menu structure:

```php
<?php

return [
    // Main RMA menu item
    [
        'key'   => 'rma',
        'name'  => 'RMA',  // Use 'rma::app.admin.menu.rma' for translations
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 100,
        'icon'  => 'icon-rma',
    ],
    
    // Sub-menu: Return Requests
    [
        'key'   => 'rma.return-requests',
        'name'  => 'Return Requests',  // Use 'rma::app.admin.menu.return-requests' for translations
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
        'icon'  => '',
    ],
    
    // Sub-menu: RMA Settings
    [
        'key'   => 'rma.settings',
        'name'  => 'Settings',  // Use 'rma::app.admin.menu.settings' for translations
        'route' => 'admin.rma.return-requests.index',  // Same route for demo
        'sort'  => 2,
        'icon'  => '',
    ],
    
    // Sub-menu: Reports (if needed)
    [
        'key'   => 'rma.reports',
        'name'  => 'Reports',  // Use 'rma::app.admin.menu.reports' for translations
        'route' => 'admin.rma.return-requests.index',  // Same route for demo
        'sort'  => 3,
        'icon'  => '',
    ],
];
```

::: info Route Configuration Note
For demonstration purposes, all menu items above use the same route (`admin.rma.return-requests.index`). In a real implementation, you would create separate routes for each menu item:

- `admin.rma.settings.index` for the settings page
- `admin.rma.reports.index` for the reports page

Make sure to create appropriate routes, controllers, and views for each menu item as per your package requirements.
:::

::: info How Hierarchical Keys Work
**Parent Menu**: `'key' => 'rma'` creates the main menu item

**Child Menu**: `'key' => 'rma.return-requests'` creates a sub-item under the RMA menu

**Grandchild Menu**: `'key' => 'rma.settings.general'` would create a third-level item

The hierarchy is automatically built based on the dot notation in the key names.
:::

::: tip Menu Best Practices
**Keep It Simple**: Start with a single menu item and expand as your package grows

**Use Translations**: Always use translation keys for menu names to support internationalization

**Logical Ordering**: Use the sort property to position your menu logically among existing items

**Consistent Icons**: Choose icons that match the visual style of existing Bagisto admin menus

**Clear Naming**: Use descriptive names that clearly indicate the menu's purpose
:::

## Your Next Step

Excellent! You've now successfully created an admin menu for your RMA package. Your package now has a complete navigation structure.

At this point in your package development journey, you have mastered the core backend components. However, to ensure proper security and access control for your admin menu, you'll need to implement permission-based access.

**Continue to:** **[Access Control List (ACL)](./access-control-list.md)** - Set up access control lists to manage who can view and interact with your admin menu

With menu navigation and proper access control in place, you'll have a secure, professional admin interface that integrates seamlessly with Bagisto's permission system.

# Access Control List (ACL)

Access Control Lists (ACL) in Bagisto provide security by controlling which admin users can access different sections of your package. ACL ensures that only authorized administrators can view and interact with specific features based on their assigned roles and permissions.

For our RMA package, we'll create ACL configuration to control access to the return requests management section, demonstrating how to secure your package's admin features.

::: info Learning Objective
This section demonstrates how to implement Access Control Lists for your Bagisto package, ensuring proper security and user access management for your admin features.
:::

## Directory Structure

To configure Access Control List (ACL) settings in Bagisto, follow these structured steps:

### Create Configuration File

 Begin by creating a new file named `acl.php` within the `Config` directory of your package located at `packages/Webkul/RMA/src/Config`:

```
└── packages
      └── Webkul
         └── RMA
            └── src
                  ├── ...
                  └── Config
                     ├── acl.php
                     └── ...
```

### Define ACL Configuration

Inside `acl.php`, define ACL settings using an array format. Each array element represents a menu item or resource with parameters such as key, `name`, `route`, and `sort`. Here’s an example:
 
Add the following code to `acl.php`:

```php
<?php

return [
    [
        'key'   => 'rma',
        'name'  => 'RMA',  // Using direct text for now
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests',
        'name'  => 'Return Requests',  // Using direct text for now
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests.view',
        'name'  => 'View',  // Using direct text for now
        'route' => 'admin.rma.return-requests.view',
        'sort'  => 1,
    ],
];
```

In the above code, we start with a simple ACL configuration using direct text for permission names. This creates a hierarchical structure: main module (RMA) → sub-module (Return Requests) → specific action (View).

## Adding Translations

Once you have the basic ACL working, you can implement translations for better internationalization support. Here's how to upgrade your configuration:

### Step 1: Add Translation File

Create or update your translation file `packages/Webkul/RMA/src/Resources/lang/en/app.php`:

```php{11-15}
<?php

return [
    'admin' => [
        // ...existing admin translations...
        
        'menu' => [
            'rma' => 'RMA',
        ],
        
        'acl' => [
            'rma' => 'RMA',
            'return-requests' => 'Return Requests',
            'view' => 'View',
        ],
    ],
];
```

### Step 2: Update ACL Configuration

Replace the direct text with translation keys in your `acl.php`:

```php{6,11,16}
<?php

return [
    [
        'key'   => 'rma',
        'name'  => 'rma::app.admin.acl.rma',  // Now using translation key
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests',
        'name'  => 'rma::app.admin.acl.return-requests',  // Now using translation key
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests.view',
        'name'  => 'rma::app.admin.acl.view',  // Now using translation key
        'route' => 'admin.rma.return-requests.view',
        'sort'  => 1,
    ],
];
```

::: tip Translation Benefits
- **Multi-language Support**: Your ACL permissions can be displayed in different languages
- **Consistency**: Matches Bagisto's core translation patterns
- **Maintainability**: Easier to update permission names across the application
:::

## Register ACL Configuration

Update your package's service provider to register the ACL:

**Update:** `packages/Webkul/RMA/src/Providers/RMAServiceProvider.php`

```php{18-22}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
        
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'rma');
    }
}
```

### Testing Your ACL Configuration

Now you can test your ACL configuration:

```bash
# Clear cache
php artisan optimize:clear

# Visit the admin panel
# Login to: http://your-app.com/admin
```

**Expected Results:**
- **Permission Created**: "RMA" appears in the admin roles/permissions interface
- **Menu Integration**: RMA menu is automatically hidden for users without permission
- **Route Protection**: Direct access to RMA routes requires proper permission

::: info Simple ACL Testing Checklist
**Core Functionality:**
- ✅ RMA permission appears in admin roles management
- ✅ Super admin can access RMA features by default
- ✅ Users without RMA permission cannot see the menu
- ✅ Direct URL access is blocked for unauthorized users

**What's Working:**
- ✅ Basic ACL permission configured
- ✅ Integration with admin menu system
- ✅ Route-level security enabled
:::

## Understanding ACL Configuration Options

Now that you have a working ACL setup, let's understand the configuration options:

### Basic ACL Properties

| Property | Type | Description |
| -------- | ---- | ----------- |
| **`key`** | String | Unique identifier for the permission |
| **`name`** | String | Display name in admin roles interface |
| **`route`** | String | Named Laravel route to protect |
| **`sort`** | Integer | Sort order in permissions list |

### Creating Hierarchical ACL Structure

For packages with multiple admin sections, you can create hierarchical permissions following Bagisto's standard pattern:

```php
<?php

return [
    // Main RMA permission
    [
        'key'   => 'rma',
        'name'  => 'RMA',  // Consider using translation key: rma::app.admin.acl.rma
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests',
        'name'  => 'Return Requests',  // Consider using translation key: rma::app.admin.acl.return-requests
        'route' => 'admin.rma.return-requests.index',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests.view',
        'name'  => 'View',  // Consider using translation key: rma::app.admin.acl.view
        'route' => 'admin.rma.return-requests.view',
        'sort'  => 1,
    ], [
        'key'   => 'rma.return-requests.create',
        'name'  => 'Create',  // Consider using translation key: rma::app.admin.acl.create
        'route' => 'admin.rma.return-requests.create',
        'sort'  => 2,
    ], [
        'key'   => 'rma.return-requests.edit',
        'name'  => 'Edit',  // Consider using translation key: rma::app.admin.acl.edit
        'route' => 'admin.rma.return-requests.edit',
        'sort'  => 3,
    ], [
        'key'   => 'rma.return-requests.delete',
        'name'  => 'Delete',  // Consider using translation key: rma::app.admin.acl.delete
        'route' => 'admin.rma.return-requests.delete',
        'sort'  => 4,
    ], [
        'key'   => 'rma.settings',
        'name'  => 'Settings',  // Consider using translation key: rma::app.admin.acl.settings
        'route' => 'admin.rma.settings.index',
        'sort'  => 2,
    ], [
        'key'   => 'rma.settings.view',
        'name'  => 'View',  // Consider using translation key: rma::app.admin.acl.view
        'route' => 'admin.rma.settings.view',
        'sort'  => 1,
    ], [
        'key'   => 'rma.settings.edit',
        'name'  => 'Edit',  // Consider using translation key: rma::app.admin.acl.edit
        'route' => 'admin.rma.settings.edit',
        'sort'  => 2,
    ],
];
```

::: info ACL Structure Explanation
**Hierarchical Structure**: Uses dot notation (e.g., `rma.return-requests.view`) to create permission hierarchy

**Translation Keys**: Uses `rma::app.acl.*` pattern for internationalization support

**Array Format**: Each permission is a separate array element, similar to Bagisto core modules like Sales

**Permission Levels**:
- **Module Level**: `rma` - Overall access to RMA features
- **Section Level**: `rma.return-requests` - Access to specific sections
- **Action Level**: `rma.return-requests.view` - Specific actions within sections

**Route Matching**: Each permission protects specific admin routes for fine-grained control
:::

## Checking Permissions in Your Code

To check permissions in your controllers or views, use Bagisto's built-in methods:

### In Controllers

```php
<?php

namespace Webkul\RMA\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;

class ReturnRequestController extends Controller
{
    public function index()
    {
        // Check if user has RMA permission
        if (! bouncer()->hasPermission('rma')) {
            abort(401, 'Unauthorized access.');
        }
        
        // Your controller logic here
    }
}
```

### In Blade Views

```blade
@if (bouncer()->hasPermission('rma'))
    <!-- Show RMA-related content -->
    <div class="rma-section">
        <h3>Return Requests Management</h3>

        <!-- RMA content here -->
    </div>
@endif
```

::: tip ACL Best Practices
**Match Menu Keys**: Use the same key for both ACL and menu configuration for consistency

**Descriptive Names**: Use clear, descriptive names for permissions in the admin interface

**Logical Hierarchy**: Organize permissions logically to match your package structure

**Test Thoroughly**: Always test with different user roles to ensure proper access control

**Document Permissions**: Document what each permission allows for easier role management
:::

## Your Next Step

You've now successfully implemented Access Control Lists for your RMA package. Your package now has proper security controls that integrate seamlessly with Bagisto's user management system.

With ACL configured, administrators can now:
- Create custom roles with specific RMA permissions
- Assign users to roles based on their responsibilities
- Control access to your package features at a granular level
- Ensure sensitive return request data is properly protected

Now that your package has comprehensive security alongside its data management and navigation features, the next step is to make your package configurable through the admin interface.

**Continue to:** **[System Configuration](./system-configuration.md)** - Add configurable settings that administrators can manage through the Bagisto admin panel

With ACL security and system configuration in place, your package will be both secure and flexible, allowing administrators to customize its behavior according to their business needs.

# System Configuration

System configuration in Bagisto allows you to create admin-configurable settings for your package that can be managed directly from the admin panel. This provides a user-friendly interface for administrators to adjust your package settings without modifying code.

For our RMA package, we'll create system configuration options to control return request settings, demonstrating how to add configurable options to your Bagisto package.

::: info Learning Objective
This section demonstrates how to create system configuration settings for your Bagisto package, allowing administrators to configure your package behavior through the admin panel.
:::

## Directory Structure

To create system configuration for your package, follow these structured steps:

### Create Configuration File

Begin by creating a new file named `system.php` within the `Config` directory of your package located at `packages/Webkul/RMA/src/Config`:

```
└── packages
    └── Webkul
        └── RMA
            ├── ...
            └── src
                └── ...
                └── Config
                    ├── acl.php
                    ├── admin-menu.php
                    └── system.php
            
```

### Define Configuration Settings

Inside the `system.php` file, include the following code to define your RMA configuration settings:

```php
<?php

return [
    [
        'key'  => 'rma',
        'name' => 'RMA',  // Use direct text for now
        'info' => 'Return Merchandise Authorization settings',  // Use direct text for now
        'sort' => 1
    ], [
        'key'  => 'rma.settings',
        'name' => 'General Settings',  // Use direct text for now
        'info' => 'Configure basic RMA functionality',  // Use direct text for now
        'icon' => 'settings/settings.svg',
        'sort' => 1,
    ], [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',  // Use direct text for now
        'info'   => 'Basic RMA settings and options',  // Use direct text for now
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'Enable RMA',  // Use direct text for now
                'type'  => 'boolean'
            ], [
                'name'  => 'allow_partial_returns',
                'title' => 'Allow Partial Returns',  // Use direct text for now
                'type'  => 'boolean',
            ], [
                'name'       => 'max_return_days',
                'title'      => 'Maximum Return Days',  // Use direct text for now
                'type'       => 'number',
                'validation' => 'numeric|min:1'
            ]
        ]
    ]
];
```

This configuration defines RMA-specific settings including enable/disable functionality, partial return options, and return time limits.

## Register Configuration

In the `register` method, add the following code to merge your system configuration:

```php{23-27}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
        
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'rma');
    }
}
```

This code merges the system configuration with the existing core configuration.

### Testing Your Configuration

Now you can test your system configuration:

```bash
# Clear cache
php artisan optimize:clear

# Visit the admin panel
# Navigate to: Configuration → RMA
```

**Expected Results:**
- **Configuration Menu**: "RMA" appears in the admin Configuration menu
- **Settings Interface**: RMA settings are accessible and editable
- **Form Validation**: Configuration fields validate according to defined rules

::: info Configuration Testing Checklist
**Core Functionality:**
- ✅ RMA section appears in admin Configuration menu
- ✅ Settings can be saved and retrieved
- ✅ Validation rules work correctly
- ✅ Default values are applied properly

**What's Working:**
- ✅ Basic system configuration structure
- ✅ Integration with admin configuration panel
- ✅ Form field validation and data persistence
:::

## Adding Translations

Once you have the basic configuration working, you can implement translations for better internationalization support:

### Step 1: Add Configuration Translations

Update your translation file `packages/Webkul/RMA/src/Resources/lang/en/app.php`:

```php{16-27}
<?php

return [
    'admin' => [
        // ...existing admin translations...
        
        'menu' => [
            'rma' => 'RMA',
        ],
        
        'acl' => [
            'rma' => 'RMA',
            'return-requests' => 'Return Requests',
            'view' => 'View',
        ],
        
        'system' => [
            'rma' => 'RMA',
            'rma-info' => 'Return Merchandise Authorization settings',
            'general-settings' => 'General Settings',
            'general-settings-info' => 'Configure basic RMA functionality',
            'rma-configuration' => 'RMA Configuration',
            'rma-configuration-info' => 'Basic RMA settings and options',
            'enable-rma' => 'Enable RMA',
            'allow-partial-returns' => 'Allow Partial Returns',
            'max-return-days' => 'Maximum Return Days',
        ],
    ],
];
```

### Step 2: Update Configuration with Translation Keys

Replace the direct text with translation keys in your `system.php`:

```php{6,7,11,12,17,18,23,27,31}
<?php

return [
    [
        'key'  => 'rma',
        'name' => 'rma::app.admin.system.rma',  // Now using translation key
        'info' => 'rma::app.admin.system.rma-info',  // Now using translation key
        'sort' => 1
    ], [
        'key'  => 'rma.settings',
        'name' => 'rma::app.admin.system.general-settings',  // Now using translation key
        'info' => 'rma::app.admin.system.general-settings-info',  // Now using translation key
        'icon' => 'settings/settings.svg',
        'sort' => 1,
    ], [
        'key'    => 'rma.settings.general',
        'name'   => 'rma::app.admin.system.rma-configuration',  // Now using translation key
        'info'   => 'rma::app.admin.system.rma-configuration-info',  // Now using translation key
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'rma::app.admin.system.enable-rma',  // Now using translation key
                'type'  => 'boolean'
            ], [
                'name'  => 'allow_partial_returns',
                'title' => 'rma::app.admin.system.allow-partial-returns',  // Now using translation key
                'type'  => 'boolean',
            ], [
                'name'       => 'max_return_days',
                'title'      => 'rma::app.admin.system.max-return-days',  // Now using translation key
                'type'       => 'number',
                'validation' => 'numeric|min:1'
            ]
        ]
    ]
];
```

## Supported Field Types

Bagisto supports several field types for system configurations. Here are all the available field types with RMA-related examples:

### Text Type

This field type provides an input field of type text, useful for simple text configurations.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'return_email',
                'title'   => 'Return Request Email',  // Consider using translation key
                'type'    => 'text',
                'default' => 'returns@yourstore.com',
                'validation' => 'email',
            ],
        ],
    ],
    // ...
];
```

### Password Type

This field type provides a password input field for sensitive information.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.api',
        'name'   => 'API Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'api_secret',
                'title'      => 'API Secret Key',  // Consider using translation key
                'type'       => 'password',
                'validation' => 'required',
            ],
        ],
    ],
    // ...
];
```

### Number Type

This field type provides an input field for numeric values.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'max_return_days',
                'title'      => 'Maximum Return Days',  // Consider using translation key
                'type'       => 'number',
                'validation' => 'required|numeric|min:1|max:365',
            ],
        ],
    ],
    // ...
];
```

### Color Type

This field type provides a color picker input field.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.appearance',
        'name'   => 'Appearance Settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'return_button_color',
                'title'   => 'Return Button Color',  // Consider using translation key
                'type'    => 'color',
                'default' => '#007bff',
            ],
        ],
    ],
    // ...
];
```

### Boolean Type

This field type provides an enable/disable switch, perfect for feature toggles.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable_auto_approval',
                'title' => 'Auto-approve Return Requests',  // Consider using translation key
                'type'  => 'boolean',
            ],
        ],
    ],
    // ...
];
```

### Select Type

This field type provides a select field with specified options, useful for predefined choices.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'default_return_status',
                'title'   => 'Default Return Status',  // Consider using translation key
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Pending Review',
                        'value' => 'pending',
                    ], [
                        'title' => 'Approved',
                        'value' => 'approved',
                    ], [
                        'title' => 'Rejected',
                        'value' => 'rejected',
                    ],
                ],
            ],
        ],
    ],
    // ...
];
```

### Multiselect Type

This field type provides a multiselect field allowing multiple option selections.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'allowed_return_reasons',
                'title'   => 'Allowed Return Reasons',  // Consider using translation key
                'type'    => 'multiselect',
                'options' => [
                    [
                        'title' => 'Defective Product',
                        'value' => 'defective',
                    ], [
                        'title' => 'Wrong Item Received',
                        'value' => 'wrong_item',
                    ], [
                        'title' => 'Not as Described',
                        'value' => 'not_described',
                    ], [
                        'title' => 'Changed Mind',
                        'value' => 'changed_mind',
                    ],
                ],
            ],
        ],
    ],
    // ...
];
```

### Textarea Type

This field type provides a textarea field, mostly used for longer text content.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'return_policy_text',
                'title' => 'Return Policy Description',  // Consider using translation key
                'type'  => 'textarea'
            ],
        ],
    ],
    // ...
];
```

### Editor Type

This field type provides a rich text editor with TinyMCE for formatted content.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.content',
        'name'   => 'Content Settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'return_instructions',
                'title' => 'Return Instructions (Rich Text)',  // Consider using translation key
                'type'  => 'editor'
            ],
        ],
    ],
    // ...
];
```

### Image Type

This field type provides a file upload option for uploading images.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.general',
        'name'   => 'RMA Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'return_label_logo',
                'title'      => 'Return Label Logo',  // Consider using translation key
                'type'       => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ],
    // ...
];
```

### File Type

This field type provides a file upload option for documents and other file types.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.documents',
        'name'   => 'Document Settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'return_policy_pdf',
                'title'      => 'Return Policy Document',  // Consider using translation key
                'type'       => 'file',
                'validation' => 'mimes:pdf,doc,docx|max:10240', // 10MB max
            ],
        ],
    ],
    // ...
];
```

### Country Type

This field type provides a dropdown of available countries.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.location',
        'name'   => 'Location Settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'return_center_country',
                'title' => 'Return Center Country',  // Consider using translation key
                'type'  => 'country',
            ],
        ],
    ],
    // ...
];
```

### State Type

This field type provides a dropdown of states/provinces based on the selected country.

#### Example

```php
return [
    // ...
    [
        'key'    => 'rma.settings.location',
        'name'   => 'Location Settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'return_center_state',
                'title' => 'Return Center State/Province',  // Consider using translation key
                'type'  => 'state',
            ],
        ],
    ],
    // ...
];
```

## Dependent Fields

The `depends` feature in Bagisto's configuration system allows you to conditionally display or enable certain configuration fields based on the value of other fields. This feature is particularly useful for creating dynamic and context-sensitive configuration forms.

The `depends` attribute is used within the configuration array to specify a condition under which the setting should be enabled or visible. It evaluates the value of another field in real-time and adjusts the display accordingly.

Consider the following RMA configuration example:

```php
return [
    // ...
    [
        'key'    => 'rma.settings.return_policy',
        'name'   => 'Return Policy Settings',  // Consider using translation key
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'enable_return_policy',
                'title' => 'Enable Return Policy',  // Consider using translation key
                'type'  => 'boolean',
            ], [
                'name'       => 'max_return_days',
                'title'      => 'Maximum Return Days',  // Consider using translation key
                'type'       => 'number',
                'validation' => 'required_if:enable_return_policy,1|numeric|min:1',
                'depends'    => 'enable_return_policy:1',
            ], [
                'name'    => 'auto_approve_returns',
                'title'   => 'Auto-approve Returns',  // Consider using translation key
                'type'    => 'boolean',
                'depends' => 'enable_return_policy:1',
            ], [
                'name'    => 'require_return_reason',
                'title'   => 'Require Return Reason',  // Consider using translation key
                'type'    => 'boolean',
                'depends' => 'enable_return_policy:1',
            ], [
                'name'    => 'return_policy_text',
                'title'   => 'Return Policy Description',  // Consider using translation key
                'type'    => 'textarea',
                'depends' => 'enable_return_policy:1',
            ],
        ],
    ],
    // ...
];
```

#### Explanation 

- The `enable_return_policy` field determines if the return policy feature is enabled.

- If `enable_return_policy` is set to `1` (true), the dependent fields (`max_return_days`, `auto_approve_returns`, `require_return_reason`, `return_policy_text`) become visible and accessible.

- The `depends` attribute ensures that these configuration options are only shown when the return policy feature is actually enabled, creating a cleaner and more intuitive admin interface.

## Validations in System Configuration

In Bagisto, validations are defined in the configuration array for each field under the `validation` key. These validations follow Laravel's validation rules, providing robust data integrity for your configuration settings.

### Common Validation Rules

- `required` - Ensures the field is not empty.
- `string` - Ensures the field contains a string.
- `integer` - Ensures the field contains an integer.
- `boolean` - Ensures the field contains a boolean value (true or false).
- `numeric` - Ensures the field contains a numeric value.
- `email` - Ensures the field contains a valid email address.
- `mimes` - Ensures the uploaded file is of a specific MIME type.
- `max` - Ensures the field contains a value not greater than a specified maximum.
- `min` - Ensures the field contains a value not less than a specified minimum.
- `required_if` - Ensures the field is required if another field has a specific value.

#### Example RMA Configuration with Validations

```php
return [
    [
        'key'    => 'rma.settings.validation_example',
        'name'   => 'RMA Validation Examples',  // Consider using translation key
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'return_email',
                'title'      => 'Return Request Email',  // Consider using translation key
                'type'       => 'text',
                'validation' => 'required|email|max:255',
            ],
            [
                'name'       => 'max_return_days',
                'title'      => 'Maximum Return Days',  // Consider using translation key
                'type'       => 'number',
                'validation' => 'required|numeric|min:1|max:365',
            ],
            [
                'name'       => 'enable_notifications',
                'title'      => 'Enable Email Notifications',  // Consider using translation key
                'type'       => 'boolean',
                'validation' => 'required|boolean',
            ],
            [
                'name'       => 'notification_email',
                'title'      => 'Notification Email',  // Consider using translation key
                'type'       => 'text',
                'validation' => 'required_if:enable_notifications,1|email',
                'depends'    => 'enable_notifications:1',
            ],
            [
                'name'       => 'return_label_logo',
                'title'      => 'Return Label Logo',  // Consider using translation key
                'type'       => 'image',
                'validation' => 'mimes:jpeg,jpg,png|max:2048',
            ],
        ],
    ],
];
```

## Using Configuration Values in Your Code

Once you've defined your system configuration, you can access these values throughout your RMA package:

### In Controllers

```php{11,13}
<?php

namespace Webkul\RMA\Http\Controllers;

use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function create()
    {
        $isRmaEnabled = core()->getConfigData('rma.settings.general.enable');

        $maxReturnDays = core()->getConfigData('rma.settings.general.max_return_days');
        
        if (! $isRmaEnabled) {
            return redirect()->back()->with('error', 'RMA is currently disabled.');
        }
        
        // Your logic here
    }
}
```

### In Blade Views

```blade{1,5}
@if (core()->getConfigData('rma.settings.general.enable'))
    <div class="return-request-section">
        <h3>Request Return</h3>

        <p>You have {{ core()->getConfigData('rma.settings.general.max_return_days') }} days to return this item.</p>
    </div>
@endif
```

::: tip Configuration Best Practices
**Logical Grouping**: Organize related settings together for better admin experience

**Clear Naming**: Use descriptive field names that clearly indicate their purpose

**Proper Validation**: Always validate configuration inputs to prevent invalid data

**Default Values**: Provide sensible defaults for a better initial experience

**Dependencies**: Use dependent fields to show/hide related configuration options

**Documentation**: Comment your configuration arrays to explain complex settings
:::

## Your Next Step

You've now successfully implemented system configuration for your RMA package. Your package now provides administrators with an intuitive interface to configure RMA behavior without touching code.

With system configuration in place, administrators can now:
- Enable or disable RMA functionality across the store
- Configure return policies and time limits
- Set up notification preferences
- Customize return request workflow settings
- Upload custom branding elements for return labels

Your RMA package now has comprehensive configuration management alongside its security, navigation, and data management features.

By following these steps and examples, you can create and manage custom configurations in Bagisto effectively, ensuring a flexible and tailored experience for your package users.

# Getting Started

Creating custom shipping methods in Bagisto allows you to tailor delivery options to meet your specific business needs. Whether you need special handling for fragile items, express delivery options, or region-specific shipping rules, custom shipping methods provide the flexibility your e-commerce store requires.

For our tutorial, we'll create a **Custom Express Shipping** method that demonstrates all the essential concepts you need to build any type of shipping solution.

::: info Learning Objectives
By the end of this guide, you'll be able to:
- Understand Bagisto's shipping architecture and components
- Create custom shipping methods using both generator and manual approaches
- Configure admin interfaces for shipping method settings
- Implement rate calculation logic
:::

## Understanding Bagisto Shipping Architecture

Bagisto's shipping system is built around a flexible carrier-based architecture that separates configuration from business logic:

### Core Components

| Component | Purpose | Location |
|-----------|---------|----------|
| **Carriers Configuration** | Defines shipping method properties and metadata | `Config/carriers.php` |
| **Carrier Classes** | Contains business logic for rate calculation | `Carriers/ClassName.php` |
| **System Configuration** | Admin interface forms for method settings | `Config/system.php` |
| **Service Provider** | Registers shipping method with Bagisto core | `Providers/ServiceProvider.php` |

### Key Features

- **Flexible Rate Calculation**: Support for per-unit, per-order, weight-based, or custom pricing models
- **Configuration Management**: Admin-friendly settings interface with validation
- **Multi-channel Support**: Different rates and settings per sales channel
- **Localization Ready**: Full translation support for international stores
- **Extensible Architecture**: Easy integration with third-party APIs and services

## Development Workflow

The typical workflow for creating a custom shipping method follows these steps:

### 1. Create Your Shipping Method
Choose between package generator (quick) or manual setup (educational) to create a complete working shipping method.

**📖 [Create Your First Shipping Method →](./create-your-first-shipping-method.md)**

This section shows you how to build a complete working shipping method, then the remaining sections help you understand how to customize each component.

### 2. Understand Carrier Configuration
Learn how carrier configuration works and how to customize shipping method properties.

**📖 Next:** [Understanding Carrier Configuration](./understanding-carrier-configuration.md)

### 3. Understand Business Logic
Explore how the carrier class handles rate calculation and shipping method behavior.

**📖 Next:** [Understanding Carrier Class](./understanding-carrier-class.md)

### 4. Understand Admin Interface
Learn how system configuration creates admin forms for managing shipping method settings.

**📖 Next:** [Understanding System Configuration](./understanding-system-configuration.md)

You'll have a complete working shipping method after step 1, and steps 2-4 help you understand how to customize and extend it.

## Prerequisites

Before you begin, ensure you have:

- **Bagisto Installation**: A working Bagisto development environment
- **PHP Knowledge**: Familiarity with PHP 8.3+ and Laravel concepts
- **Package Development**: Basic understanding of [Package Development](../package-development/getting-started.md)
- **Development Tools**: Composer, Git, and a code editor

::: tip Quick Start Path
**New to Bagisto?** Start with the [package generator approach](./create-your-first-shipping-method.md#method-1-using-bagisto-package-generator-quick-setup) for your first shipping method.

**Want to understand everything?** Follow the [manual setup approach](./create-your-first-shipping-method.md#method-2-manual-setup-complete-understanding) for complete control and learning.
:::

## What You'll Build

Throughout this guide, you'll create a **Custom Express Shipping** method that includes:

### Core Features
- ✅ **Dual Pricing Models**: Support for both per-order and per-item pricing
- ✅ **Admin Configuration**: Complete settings interface in Bagisto admin
- ✅ **Rate Calculation**: Dynamic pricing based on cart contents
- ✅ **Multi-channel Support**: Different settings per sales channel

## Architecture Overview

```text
Custom Express Shipping Package
├── src/
│   ├── Carriers/
│   │   └── CustomExpressShipping.php    # Rate calculation logic
│   ├── Config/
│   │   ├── carriers.php                 # Shipping method definition
│   │   └── system.php                   # Admin interface configuration
│   └── Providers/
│       └── ServiceProvider.php          # Package registration
├── composer.json                        # Package metadata
└── README.md                            # Documentation
```

::: info Development Time Estimate
- **Basic Implementation**: 1-2 hours (using generator)
- **Custom Logic**: 2-4 hours (manual setup + customization)
- **Advanced Features**: 4-8 hours (API integration, complex rules)
- **Testing & Polish**: 1-2 hours (admin testing, frontend validation)
:::

## Ready to Start?

Choose your learning path and begin building your custom shipping method:

**🚀 [Create Your First Shipping Method →](./create-your-first-shipping-method.md)**

This section covers both package generator and manual approaches, helping you understand the foundations while building a working shipping method.

# Creating Your First Shipping Method

Let's create a custom shipping method using both approaches available in Bagisto. We'll explore the package generator for quick setup and the manual method for complete understanding.

::: info What You'll Learn
This guide covers the complete process of creating a **Custom Express Shipping** method, including:
- Package structure setup (generator vs manual)
- Configuration file creation
- Rate calculation implementation
- Admin interface integration
- Testing and deployment
:::

## Method 1: Using Bagisto Package Generator (Quick Setup)

The fastest way to create a shipping method is using Bagisto's package generator. This tool creates the proper structure and boilerplate code automatically.

### Step 1: Install Package Generator

If you haven't installed the package generator yet:

```bash
composer require bagisto/bagisto-package-generator
```

### Step 2: Generate Shipping Method Package

Navigate to your Bagisto root directory and run:

```bash
php artisan package:make-shipping-method Webkul/CustomExpressShipping
```

**Command Parameters:**
- `Webkul/CustomExpressShipping`: The vendor/package name for your shipping method

### Step 3: Handle Existing Package (If Needed)

If the package directory already exists, use the `--force` flag:

```bash
php artisan package:make-shipping-method Webkul/CustomExpressShipping --force
```

::: tip Package Generator Benefits
The generator automatically creates:
- Proper directory structure following Bagisto conventions
- Carrier configuration file with correct schema
- Base carrier class extending AbstractShipping
- System configuration for admin settings
- Service provider with proper registration
:::

### Step 4: Register the Generated Package

After generating the package, you need to register it with Bagisto:

**Add to composer.json** (in Bagisto root directory):

```json{5}
{
    "autoload": {
        "psr-4": {
            // Other PSR-4 namespaces...
            "Webkul\\CustomExpressShipping\\": "packages/Webkul/CustomExpressShipping/src"
        }
    }
}
```

**Update autoloader:**

```bash
composer dump-autoload
```

**Register service provider** in `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // ... other providers ...
    
    Webkul\CustomExpressShipping\Providers\CustomExpressShippingServiceProvider::class,
];
```

**Clear caches:**

```bash
php artisan optimize:clear
```

### Step 5: Configure Your Shipping Method

Now test the basic configuration that the generator created:

1. **Go to Admin Panel**: Navigate to **Configure → Shipping Methods**
2. **Find Your Method**: Look for "Custom Express Shipping" section
3. **Basic Configuration**: You'll see some basic configuration fields that can be adjusted as per your needs

::: tip Translation Note
You may notice some translation keys are missing as we haven't registered translation files yet. For complete localization setup, refer to the [Localization section in Package Development](../package-development/localization.md). The main purpose here is to understand shipping method functionality.
:::

::: info Generator Creates Basic Configuration
The package generator creates a simple shipping method with:
- **Flat rate pricing**: Single rate for all orders
- **Basic admin fields**: Essential configuration options
- **Standard structure**: Following Bagisto conventions

For advanced features like weight-based pricing, API integration, or complex calculations, you'll need to customize the generated code or use the manual approach below.
:::

## Method 2: Manual Setup (Complete Understanding)

For developers who want to understand every component, let's create the shipping method manually from scratch.

### Step 1: Create Package Directory Structure

Create the complete directory structure for your shipping method package:

```bash
mkdir -p packages/Webkul/CustomExpressShipping/src/{Carriers,Config,Providers}
```

This creates the following structure:

```text
packages
└── Webkul
    └── CustomExpressShipping
        └── src
            ├── Carriers/          # Shipping calculation logic
            ├── Config/            # Configuration files
            └── Providers/         # Service provider
```

### Step 2: Create Carrier Configuration

Create the carriers configuration file that defines your shipping method properties:

**Create:** `packages/Webkul/CustomExpressShipping/src/Config/carriers.php`

```php
<?php

return [
    'custom_express_shipping' => [
        'code'         => 'custom_express_shipping',
        'title'        => 'Express Delivery (1-2 Days)',
        'description'  => 'Premium express shipping with tracking and insurance',
        'active'       => true,
        'default_rate' => '19.99',
        'type'         => 'per_order',
        'class'        => 'Webkul\CustomExpressShipping\Carriers\CustomExpressShipping',
    ]
];
```

::: info Manual Configuration Benefits
**Learning Value**: Understanding each property helps you customize behavior

**Flexibility**: Complete control over configuration structure

**Debugging**: Easier to troubleshoot when you know every line

**Customization**: Add custom properties for advanced features
:::

### Step 3: Create Carrier Class

Create the main carrier class that handles rate calculation:

**Create:** `packages/Webkul/CustomExpressShipping/src/Carriers/CustomExpressShipping.php`

::: tip Don't Get Overwhelmed!
Don't worry about understanding every line of code right now - just go with the flow! We'll cover all these concepts in detail in the following sections:
- **Carrier configuration** and properties
- **Rate calculation logic** and methods
- **System configuration** and admin fields
- **Advanced features** and customizations

Focus on getting your shipping method working first, then dive deeper into each component.
:::

```php
<?php

namespace Webkul\CustomExpressShipping\Carriers;

use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Checkout\Facades\Cart;

class CustomExpressShipping extends AbstractShipping
{
    /**
     * Shipping method code - must match carriers.php key.
     */
    protected $code = 'custom_express_shipping';

    /**
     * Calculate shipping rate for the current cart.
     */
    public function calculate()
    {
        // check if shipping method is available
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();
        
        // create shipping rate object
        $object = new CartShippingRate;
        $object->carrier = 'custom_express_shipping';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'custom_express_shipping_custom_express_shipping';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        
        // calculate rate - start with base rate
        $baseRate = $this->getConfigData('default_rate');
        $finalRate = $baseRate;
        
        // express shipping logic - you can customize this
        if ($this->getConfigData('type') === 'per_unit') {
            // calculate per item
            $totalItems = 0;

            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $totalItems += $item->quantity;
                }
            }

            $finalRate = $baseRate * $totalItems;
        } else {
            // per order pricing (flat rate)
            $finalRate = $baseRate;
        }
        
        // set calculated prices
        $object->price = core()->convertPrice($finalRate);
        $object->base_price = $finalRate;

        return $object;
    }
}
```

### Step 4: Create System Configuration

Create the admin interface configuration for your shipping method:

**Create:** `packages/Webkul/CustomExpressShipping/src/Config/system.php`

```php
<?php

return [
    [
        'key'    => 'sales.carriers.custom_express_shipping',
        'name'   => 'Custom Express Shipping',
        'info'   => 'Configure the Custom Express Shipping method settings.',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Method Title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true
            ],
            [
                'name'          => 'description', 
                'title'         => 'Description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false
            ],
            [
                'name'          => 'default_rate',
                'title'         => 'Base Rate ($)',
                'type'          => 'text',
                'validation'    => 'required|numeric|min:0',
                'channel_based' => true,
                'locale_based'  => false
            ],
            [
                'name'    => 'type',
                'title'   => 'Pricing Type',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Per Order (Flat Rate)',
                        'value' => 'per_order',
                    ],
                    [
                        'title' => 'Per Item',
                        'value' => 'per_unit',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'Enabled',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false
            ]
        ]
    ]
];
```

### Step 5: Create Service Provider

Create the service provider to register your shipping method with Bagisto:

**Create:** `packages/Webkul/CustomExpressShipping/src/Providers/CustomExpressShippingServiceProvider.php`

```php
<?php

namespace Webkul\CustomExpressShipping\Providers;

use Illuminate\Support\ServiceProvider;

class CustomExpressShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // merge carrier configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php',
            'carriers'
        );

        // merge system configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
```

### Step 6: Register Your Package

After creating all the files, you need to register your package with Bagisto:

**Add to composer.json** (in Bagisto root directory):

```json{5}
{
    "autoload": {
        "psr-4": {
            // Other PSR-4 namespaces...
            "Webkul\\CustomExpressShipping\\": "packages/Webkul/CustomExpressShipping/src"
        }
    }
}
```

**Update autoloader:**

```bash
composer dump-autoload
```

**Register service provider** in `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // ... other providers ...
    
    Webkul\CustomExpressShipping\Providers\CustomExpressShippingServiceProvider::class,
];
```

**Clear caches:**

```bash
php artisan optimize:clear
```

## Testing Your Shipping Method

Now let's test your custom express shipping method:

### Step 1: Enable in Admin

1. Go to **Admin Panel → Configure → Shipping Methods**
2. Find **Custom Express Shipping** section
3. Set **Enabled** to **Yes**
4. Configure your rates and settings
5. Click **Save Configuration**

### Step 2: Frontend Testing

1. Add products to cart
2. Proceed to checkout
3. Enter shipping address
4. Verify your express shipping option appears
5. Check that rates calculate correctly

### Step 3: Testing Checklist

::: info Testing Checklist
**Admin Configuration:**
- ✅ Custom Express Shipping appears in carrier settings
- ✅ All form fields render correctly
- ✅ Settings save and persist properly
- ✅ Validation works for required fields

**Frontend Functionality:**
- ✅ Shipping method appears during checkout
- ✅ Rates calculate correctly
- ✅ Method title and description display properly
- ✅ Integration with cart totals works

**Edge Cases:**
- ✅ Method respects enabled/disabled status
- ✅ Handles empty carts gracefully
- ✅ Works with different product types
- ✅ Responds correctly to weight/price thresholds
:::

## Generated vs Manual Package Structure

Both methods create the same final structure:

```text
packages
└── Webkul
    └── CustomExpressShipping
        └── src
            ├── Carriers
            │   └── CustomExpressShipping.php                 # Rate calculation logic
            ├── Config
            │   ├── carriers.php                              # Shipping method definition
            │   └── system.php                                # Admin configuration
            └── Providers
                └── CustomExpressShippingServiceProvider.php  # Registration
```

::: tip Choosing Your Approach
**Use Package Generator When:**
- Quick prototyping or testing
- Following standard Bagisto patterns
- Building simple shipping methods
- Learning Bagisto basics

**Use Manual Setup When:**
- Need complete control over code
- Building complex shipping logic
- Want to understand every component
- Customizing beyond standard patterns
:::

## Your Next Steps

Congratulations! You've successfully created a custom shipping method for Bagisto. Your express shipping method now integrates seamlessly with the checkout process and provides administrators with full configuration control.

**Key Achievements:**
- ✅ Built a complete shipping method from scratch
- ✅ Implemented basic rate calculation logic
- ✅ Created admin configuration interface
- ✅ Integrated with Bagisto's shipping system

### Continue Learning

Now that you have a working shipping method, dive deeper into specific components:

**📖 [Understanding Carrier Configuration →](./understanding-carrier-configuration.md)**
Learn about carrier configuration properties, validation, and advanced options.

**📖 [Understanding Carrier Class →](./understanding-carrier-class.md)**
Learn how to implement the business logic and rate calculation methods for your shipping method.

**📖 [Understanding System Configuration →](./understanding-system-configuration.md)**
Master the admin interface creation with field types, validation, and multi-channel support.

::: tip Production Considerations
**Before deploying to production:**
- Test with various cart configurations
- Verify multi-channel and multi-locale settings
- Set up monitoring for rate calculation errors
- Document configuration options for administrators
:::

Your shipping method is now complete! You can extend it further by adding features like tracking integration, delivery time estimates, or API integrations with shipping carriers.

# Understanding Carrier Configuration

The carrier configuration is the foundation of your shipping method - it defines the basic properties, behavior, and integration points with Bagisto's shipping system. Understanding this configuration deeply will help you build flexible and maintainable shipping solutions.

::: info What You'll Learn
This comprehensive guide covers:
- Complete carrier configuration properties and their purposes
- How configuration affects admin interface and frontend behavior
- Advanced configuration patterns for complex shipping methods
- Best practices for maintainable and extensible configurations
:::

## Carrier Configuration Structure

The carrier configuration file defines all the essential properties of your shipping method. Let's examine each component in detail:

**File:** `packages/Webkul/CustomExpressShipping/src/Config/carriers.php`

```php
<?php

return [
    'custom_express_shipping' => [
        'code'         => 'custom_express_shipping',
        'title'        => 'Express Delivery (1-2 Days)',
        'description'  => 'Premium express shipping with tracking and insurance',
        'active'       => true,
        'default_rate' => '19.99',
        'type'         => 'per_order',
        'class'        => 'Webkul\CustomExpressShipping\Carriers\CustomExpressShipping',
    ]
];
```

## Core Configuration Properties

### Essential Properties

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `code` | string | ✅ | Unique identifier for your shipping method |
| `title` | string | ✅ | Display name shown to customers |
| `description` | string | ❌ | Brief explanation of the shipping service |
| `active` | boolean | ❌ | Whether the method is enabled (default: true) |
| `class` | string | ✅ | Full namespace path to your carrier class |

### Pricing Properties

| Property | Type | Description |
|----------|------|-------------|
| `default_rate` | string/float | Base shipping cost before calculations |
| `type` | string | Pricing model: `per_order` or `per_unit` |

::: details Property Details and Best Practices

**Code Property:**
- Must be unique across all shipping methods
- Use lowercase with underscores (e.g., `custom_express_shipping`)
- Should match your package name for consistency
- Used internally for identification and configuration keys

**Title Property:**
- Customer-facing display name during checkout
- Keep it clear and descriptive (e.g., "Express Delivery (1-2 Days)")
- Can be overridden in admin configuration
- Should indicate delivery speed or special features

**Description Property:**
- Optional but recommended for clarity
- Briefly explains the shipping service benefits
- Shown in admin interface and potentially frontend
- Good for highlighting features like tracking, insurance, etc.

**Class Property:**
- Must point to your actual carrier class
- Include full namespace path
- Bagisto uses this to instantiate your shipping logic
- Case-sensitive and must match exactly
:::

## Advanced Configuration Options

For more sophisticated shipping methods, you can extend the configuration:

```php
<?php

return [
    'custom_express_shipping' => [
        // core properties
        'code'         => 'custom_express_shipping',
        'title'        => 'Express Delivery (1-2 Days)',
        'description'  => 'Premium express shipping with tracking and insurance',
        'active'       => true,
        'class'        => 'Webkul\CustomExpressShipping\Carriers\CustomExpressShipping',
        
        // pricing configuration
        'default_rate'            => '19.99',
        'type'                    => 'per_order',
        'free_shipping_threshold' => '100.00',
        
        // service features
        'supports' => [
            'tracking'           => true,
            'insurance'          => true,
            'signature_required' => false,
            'weekend_delivery'   => true,
        ],
        
        // availability rules
        'availability' => [
            'weight_limit' => 50.0, // kg

            'size_limit' => [
                'length' => 100, // cm
                'width'  => 80,  // cm  
                'height' => 60,  // cm
            ],

            'restricted_postcodes' => ['12345', '67890'],
            'business_days_only'   => false,
        ],
        
        // api configuration (if using external service)
        'api_config' => [
            'endpoint'      => env('EXPRESS_SHIPPING_API_URL'),
            'api_key'       => env('EXPRESS_SHIPPING_API_KEY'),
            'timeout'       => 30,
            'fallback_rate' => '25.00',
        ],
    ]
];
```

::: details Advanced Configuration Benefits
**Structured Approach:**
- All shipping logic parameters in one place
- Easy to modify without touching code
- Environment-specific configurations
- Clear separation of concerns

**Maintainability:**
- Changes don't require code updates
- Easy to add new features
- Configuration validation possibilities
- Better testing and deployment
:::

## Understanding Configuration Flow

### 1. Registration Phase
When Bagisto starts, your service provider merges the carrier configuration:

```php
// in your service provider...
$this->mergeConfigFrom(
    dirname(__DIR__) . '/Config/carriers.php',
    'carriers'
);
```

### 2. Discovery Phase  
Bagisto discovers your shipping method through the merged configuration:

```php
// internally processes...
Config::get('carriers')
```

### 3. Instantiation Phase
When needed, Bagisto creates your carrier instance:

```php
// bagisto uses the 'class' property...
$carrier = new \Webkul\CustomExpressShipping\Carriers\CustomExpressShipping;
```

### 4. Configuration Access
Your carrier class can access configuration values:

```php
// in your carrier class...
$this->getConfigData('default_rate');      // Returns '19.99'
$this->getConfigData('title');             // Returns 'Express Delivery (1-2 Days)'
$this->getConfigData('supports.tracking'); // Returns true
```

## Configuration Best Practices

### 1. Naming Conventions

```php
// ✅ good naming
'premium_express_delivery' => [
    'code'  => 'premium_express_delivery',
    'title' => 'Premium Express (Next Day)',
    'class' => 'Vendor\Package\Carriers\PremiumExpressDelivery',
]

// ❌ avoid
'PED' => [
    'code'  => 'ped',
    'title' => 'PED',
    'class' => 'Vendor\Package\Carriers\PED',
]
```

### 2. Descriptive Titles and Descriptions

```php
// ✅ clear and informative
'title'       => 'Express Delivery (1-2 Business Days)',
'description' => 'Fast shipping with tracking and insurance included',

// ❌ vague
'title'       => 'Fast Shipping',
'description' => 'Quick delivery',
```

### 3. Sensible Defaults

```php
// ✅ production-ready defaults
'active'       => false,       // Start disabled until configured
'default_rate' => '0.00',      // Require explicit rate setting
'type'         => 'per_order', // Most common pricing model

// ❌ risky defaults
'active'       => true,     // Could activate before configuration
'default_rate' => '999.99', // Extremely high fallback
```

### 4. Environment-Aware Configuration

```php
'api_config' => [
    'endpoint'   => env('SHIPPING_API_URL', 'https://api.example.com'),
    'api_key'    => env('SHIPPING_API_KEY'),
    'debug_mode' => env('SHIPPING_DEBUG', false),
    'timeout'    => env('SHIPPING_TIMEOUT', 30),
],
```

## Common Configuration Patterns

Understanding common configuration patterns helps you design shipping methods that are both flexible and maintainable. Here are the most frequently used approaches with detailed explanations and implementation guidance.

### Pattern 1: Multi-Service Carrier

This pattern is ideal when you want to offer multiple shipping speed options from the same carrier company. Each service level has its own configuration while sharing common infrastructure.

```php
return [
    'express_standard' => [
        'code'     => 'express_standard',
        'title'    => 'Express Standard (2-3 Days)',
        'rate'     => '9.99',
        'class'    => 'Vendor\Express\Carriers\ExpressStandard',
        'features' => ['tracking'],
        'days'     => '2-3',
    ],

    'express_priority' => [
        'code'       => 'express_priority', 
        'title'      => 'Express Priority (1-2 Days)',
        'rate'       => '19.99',
        'class'      => 'Vendor\Express\Carriers\ExpressPriority',
        'features'   => ['tracking', 'insurance', 'weekend'],
        'days'       => '1-2',
        'max_weight' => 30.0,
    ],

    'express_overnight' => [
        'code'          => 'express_overnight',
        'title'         => 'Express Overnight',
        'rate'          => '39.99', 
        'class'         => 'Vendor\Express\Carriers\ExpressOvernight',
        'features'      => ['tracking', 'insurance', 'signature'],
        'days'          => '1',
        'max_weight'    => 20.0,
        'business_only' => true,
    ],
];
```

::: details Multi-Service Benefits & Implementation Tips

**Benefits:**
- Customers can choose speed vs. cost
- Clear service differentiation
- Shared backend infrastructure
- Consistent carrier branding

**Implementation Tips:**
- Use shared base class for common functionality
- Differentiate services through configuration, not code
- Implement service-specific validation rules
- Consider cutoff times for same-day processing

**Common Use Cases:**
- FedEx: Ground, 2-Day, Overnight
- UPS: Ground, 3-Day, Next Day
- Custom carrier with multiple speed tiers
:::

### Pattern 2: Feature-Based Configuration

This pattern structures configuration around specific features and services, making it easy to enable/disable capabilities and calculate feature-based pricing.

```php
'premium_shipping' => [
    'code'      => 'premium_shipping',
    'title'     => 'Premium White Glove Service',
    'class'     => 'Vendor\Premium\Carriers\PremiumShipping',
    'base_rate' => '49.99',
    
    'features' => [
        'white_glove' => [
            'fee'       => '25.00',
            'min_value' => 500.00,
        ],

        'assembly' => [
            'fee'   => '75.00',
            'types' => ['furniture', 'equipment'],
        ],

        'appointment' => [
            'fee'   => '15.00',
            'slots' => ['morning', 'afternoon', 'evening'],
        ],

        'packaging' => [
            'fee'      => '12.00',
            'includes' => ['bubble_wrap', 'corner_protection'],
        ],
    ],
    
    'discounts' => [
        'bundle_all'      => 0.25,
        'volume_min'      => 1000.00,
        'volume_discount' => 0.10,
    ],
    
    'availability' => [
        'areas' => ['metro', 'suburban'],
        'hours' => 'weekdays 8-18, saturday 9-15',
    ],
];
```

::: details Feature-Based Benefits & Implementation Tips

**Benefits:**
- Modular feature enabling/disabling
- Clear pricing transparency
- Easy feature expansion
- Customer choice flexibility

**Implementation Tips:**
- Validate feature combinations for compatibility
- Calculate total price by summing enabled features
- Implement feature-specific availability checks
- Provide clear feature descriptions for customers

**Common Use Cases:**
- Furniture delivery with assembly options
- Appliance delivery with installation
- Art/antique shipping with special handling
- Business equipment delivery with setup
:::

### Pattern 3: Region-Specific Configuration

This pattern optimizes shipping for different geographic regions, each with unique pricing, delivery times, and service capabilities.

```php
'international_express' => [
    'code'  => 'international_express',
    'title' => 'International Express Shipping',
    'class' => 'Vendor\International\Carriers\InternationalExpress',
    
    'regions' => [
        'north_america' => [
            'countries'  => ['US', 'CA', 'MX'],
            'rate'       => '29.99',
            'days'       => '3-5',
            'features'   => ['tracking', 'customs_clearance', 'saturday'],
            'max_weight' => 70.0,
            'prohibited' => ['batteries', 'liquids'],
        ],

        'europe' => [
            'countries'   => ['GB', 'DE', 'FR', 'IT', 'ES'],
            'rate'        => '39.99', 
            'days'        => '5-7',
            'features'    => ['tracking', 'customs_clearance'],
            'max_weight'  => 50.0,
            'prohibited'  => ['food', 'plants', 'medicines'],
            'brexit_docs' => true,
        ],

        'asia_pacific' => [
            'countries'   => ['JP', 'AU', 'SG', 'HK'],
            'rate'        => '49.99',
            'days'        => '7-10',
            'max_weight'  => 30.0,
            'prohibited'  => ['electronics', 'food'],
            'local_agent' => true,
        ],
    ],
    
    'currency'      => 'USD',
    'fallback_rate' => '75.00',
];
```

::: details Region-Specific Benefits & Implementation Tips

**Benefits:**
- Accurate regional pricing and delivery estimates
- Compliance with local regulations
- Optimized service levels per region
- Cultural and legal consideration handling

**Implementation Tips:**
- Validate destination countries against region definitions
- Implement currency conversion for international pricing
- Handle customs documentation automatically
- Provide clear restriction information to customers
- Monitor political and security situations for risk assessment

**Common Use Cases:**
- Global e-commerce platforms
- B2B international shipping
- Cross-border marketplace integration
- Compliance-heavy industries (medical, electronics)
:::

### Pattern 4: API-Integrated Configuration

For carriers that integrate with external shipping APIs, this pattern manages API credentials, endpoints, and fallback scenarios.

```php
'fedex_integration' => [
    'code'  => 'fedex_integration',
    'title' => 'FedEx Express',
    'class' => 'Vendor\FedEx\Carriers\FedExIntegration',
    
    'api' => [
        'environment' => env('FEDEX_ENV', 'sandbox'),

        'endpoints'   => [
            'production' => 'https://apis.fedex.com/rate/v1/rates/quotes',
            'sandbox'    => 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes',
        ],

        'credentials' => [
            'api_key' => env('FEDEX_API_KEY'),
            'secret'  => env('FEDEX_SECRET_KEY'),
            'account' => env('FEDEX_ACCOUNT'),
        ],

        'timeout' => 30,
        'retries' => 3,
    ],
    
    'services' => [
        'FEDEX_GROUND'       => 'FedEx Ground',
        'FEDEX_2_DAY'        => 'FedEx 2Day',
        'STANDARD_OVERNIGHT' => 'FedEx Standard Overnight',
        'PRIORITY_OVERNIGHT' => 'FedEx Priority Overnight',
    ],
    
    'cache' => [
        'enabled' => true,
        'ttl'     => 300, // 5 minutes
        'prefix'  => 'fedex_rates_',
    ],
    
    'fallback' => [
        'enabled' => true,

        'rates'   => [
            'ground'    => '8.99',
            'express'   => '24.99',
            'overnight' => '45.99',
        ],

        'message' => 'Estimated shipping cost',
    ],
    
    'features' => [
        'real_time_rates' => true,
        'live_tracking'   => true,
        'insurance'       => true,
        'signature'       => true,
    ],
];
```

::: details API-Integrated Benefits & Implementation Tips

**Benefits:**
- Real-time accurate rates
- Live tracking integration
- Automatic service updates
- Professional carrier integration

**Implementation Tips:**
- Always implement fallback rates for API failures
- Cache responses to reduce API calls and improve performance
- Handle authentication token renewal automatically
- Implement rate limiting to respect API quotas
- Log API interactions for debugging and monitoring

**Common Use Cases:**
- Major carrier integrations (FedEx, UPS, DHL)
- Real-time rate shopping
- Enterprise shipping solutions
- High-volume shipping operations
:::

Each pattern serves different business needs and can be combined or adapted based on your specific requirements. Choose the pattern that best matches your shipping method's complexity and feature requirements.

## What's Next?

Now that you understand carrier configuration, let's explore how to build the business logic:

**📖 [Understanding Carrier Class →](./understanding-carrier-class.md)**
Learn how to implement the business logic and rate calculation methods for your shipping method.

**📖 [Understanding System Configuration →](./understanding-system-configuration.md)**
Learn how to create admin interfaces for your shipping method settings.

Your carrier configuration is now robust and ready for complex shipping scenarios.

# Understanding Carrier Class Implementation

The carrier class contains your shipping method's business logic. It has one main job: implement the `calculate()` method to determine shipping rates.

::: info What You'll Learn
This guide covers:
- Simple carrier class structure
- The `calculate()` method implementation
- Basic pricing patterns
:::

## Basic Carrier Class Structure

Every shipping method extends `AbstractShipping` and implements one key method:

**File:** `packages/Webkul/CustomExpressShipping/src/Carriers/CustomExpressShipping.php`

```php
<?php

namespace Webkul\CustomExpressShipping\Carriers;

use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;

class CustomExpressShipping extends AbstractShipping
{
    protected $code = 'custom_express_shipping';

    public function calculate()
    {
        if (! $this->getConfigData('active')) {
            return false;
        }

        $rate = new CartShippingRate;
        $rate->carrier = $this->getCode();
        $rate->carrier_title = $this->getConfigData('title');
        $rate->method = $this->getCode();
        $rate->method_title = $this->getConfigData('title');
        $rate->price = $this->getConfigData('default_rate');
        $rate->base_price = $rate->price;

        return $rate;
    }
}
```

## Understanding the `calculate()` Method

The `calculate()` method is the only method you need to implement. It either returns a shipping rate or `false`:

```php
public function calculate()
{
    // return false if shipping is not available
    if (! $this->getConfigData('active')) {
        return false;
    }

    // create and return shipping rate
    $rate = new CartShippingRate;
    $rate->carrier = $this->getCode();
    $rate->carrier_title = $this->getConfigData('title');
    $rate->method = $this->getCode();
    $rate->method_title = $this->getConfigData('title');
    $rate->price = $this->getConfigData('default_rate');
    $rate->base_price = $rate->price;

    return $rate;
}
```

## Simple Pricing Examples

Here are common pricing patterns you can implement in your `calculate()` method. Each example shows a complete method implementation:

### Fixed Rate Shipping

Simple flat rate shipping - same price for every order:

```php
public function calculate()
{
    if (! $this->getConfigData('active')) {
        return false;
    }

    $rate = new CartShippingRate;
    $rate->carrier = $this->getCode();
    $rate->carrier_title = $this->getConfigData('title');
    $rate->method = $this->getCode();
    $rate->method_title = $this->getConfigData('title');
    $rate->price = 15.99; // Fixed price
    $rate->base_price = $rate->price;

    return $rate;
}
```

### Weight-Based Pricing

Calculate shipping cost based on cart weight - base rate plus cost per kilogram:

```php
public function calculate()
{
    if (! $this->getConfigData('active')) {
        return false;
    }

    $cart = $this->getCart();
    $price = 5.00 + ($cart->weight * 2.50); // base + per kg

    $rate = new CartShippingRate;
    $rate->carrier = $this->getCode();
    $rate->carrier_title = $this->getConfigData('title');
    $rate->method = $this->getCode();
    $rate->method_title = $this->getConfigData('title');
    $rate->price = $price;
    $rate->base_price = $rate->price;

    return $rate;
}
```

### Free Shipping Example

Offer free shipping when cart total reaches a threshold, otherwise charge standard rate:

```php
public function calculate()
{
    if (! $this->getConfigData('active')) {
        return false;
    }

    $cart = $this->getCart();
    $price = $cart->sub_total >= 100 ? 0 : 9.99; // free over $100

    $rate = new CartShippingRate;
    $rate->carrier = $this->getCode();
    $rate->carrier_title = $this->getConfigData('title');
    $rate->method = $this->getCode();
    $rate->method_title = $this->getConfigData('title');
    $rate->price = $price;
    $rate->base_price = $rate->price;

    return $rate;
}
```

## What's Next?

Now that you understand the carrier class, let's explore system configuration:

**📖 [Understanding System Configuration →](./understanding-system-configuration.md)**
Learn how to create admin interfaces for configuring your shipping method.

Your carrier class is now ready to handle shipping calculations. The next section shows you how to create admin configuration interfaces.

# Understanding System Configuration

Learn how to create admin configuration interfaces for your shipping method, allowing administrators to customize settings without touching code.

::: info What You'll Learn
This guide covers:
- Creating simple configuration fields
- Field types and validation
- Accessing configuration data in your carrier class
:::

## Basic Configuration Structure

System configuration creates admin interface fields for your shipping method:

**File:** `packages/Webkul/CustomExpressShipping/src/Config/system.php`

```php
<?php

return [
    [
        'key'    => 'sales.carriers.custom_express_shipping',
        'name'   => 'Custom Express Shipping',
        'info'   => 'Configure the Custom Express Shipping method settings.',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'Enable Method',
                'type'          => 'boolean',
                'default_value' => true,
            ],
            [
                'name'          => 'title',
                'title'         => 'Method Title',
                'type'          => 'text',
                'default_value' => 'Express Delivery',
            ],
            [
                'name'          => 'default_rate',
                'title'         => 'Shipping Rate',
                'type'          => 'text',
                'default_value' => '19.99',
                'validation'    => 'numeric|min:0',
            ],
        ]
    ]
];
```

## Field Types and Validation

For detailed information about all available field types, validation rules, and advanced configuration options, see:

**📖 [Package Development - System Configuration →](../package-development/system-configuration.md)**
Complete guide to creating admin configuration interfaces with all field types and options.

## Accessing Configuration Data

Once you've defined your configuration fields, you can access their values in your carrier class using the `getConfigData()` method:

### In Your Carrier Class

The most common place to access configuration data is in your carrier's `calculate()` method:
```php
public function calculate()
{
    // get configuration values
    $isActive = $this->getConfigData('active');
    $title = $this->getConfigData('title');
    $rate = $this->getConfigData('default_rate');
    
    // use in your logic
    if (! $isActive) {
        return false;
    }
    
    $shippingRate = new CartShippingRate;
    $shippingRate->price = $rate;
    
    return $shippingRate;
}
```

## What's Next?

Now that you understand system configuration, you have all the pieces to build a complete shipping method:

**📖 [Back to Getting Started ←](./getting-started.md)**
Review the complete shipping method development workflow and see your progress.

Your shipping method now has a complete admin configuration interface. You're ready to build sophisticated shipping solutions!

# Getting Started

Creating custom payment methods in Bagisto allows you to integrate any payment gateway or processor with your store. Whether you need local payment methods, cryptocurrency payments, or specialized payment flows, custom payment methods provide the flexibility your business requires.

For our tutorial, we'll create a **Custom Stripe Payment** method that demonstrates all the essential concepts you need to build any type of payment solution.

::: info What You'll Learn
By the end of this guide, you'll be able to:
- Understand Bagisto's payment architecture
- Create custom payment methods using generator or manual approaches
- Configure admin interfaces for payment settings
:::

## Understanding Bagisto Payment Architecture

Bagisto's payment system is built around a flexible method-based architecture that separates configuration from business logic:

### Core Components

| Component | Purpose | Location |
|-----------|---------|----------|
| **Payment Methods Configuration** | Defines payment method properties | `Config/payment-methods.php` |
| **Payment Classes** | Contains payment processing logic | `Payment/ClassName.php` |
| **System Configuration** | Admin interface forms | `Config/system.php` |
| **Service Provider** | Registers payment method | `Providers/ServiceProvider.php` |

### Key Features

- **Flexible Payment Processing**: Support for redirects, APIs, webhooks, or custom flows
- **Configuration Management**: Admin-friendly settings interface
- **Multi-channel Support**: Different settings per sales channel
- **Security Ready**: Built-in CSRF protection and secure handling
- **Extensible Architecture**: Easy integration with third-party gateways

## Development Workflow

The typical workflow for creating a custom payment method follows these steps:

### 1. Create Your Payment Method
Choose between package generator (quick) or manual setup (educational) to create a complete working payment method.

**📖 [Create Your First Payment Method →](./create-your-first-payment-method.md)**

This section shows you how to build a complete working payment method, then the remaining sections help you understand how to customize each component.

### 2. Understand Payment Configuration
Learn how payment configuration works and how to customize payment method properties.

**📖 Next:** [Understanding Payment Configuration](./understanding-payment-configuration.md)

### 3. Understand Payment Logic
Explore how the payment class handles processing and payment method behavior.

**📖 Next:** [Understanding Payment Class](./understanding-payment-class.md)

You'll have a complete working payment method after step 1, and steps 2-3 help you understand how to customize and extend it.

## Prerequisites

Before you begin, ensure you have:

- **Bagisto Installation**: A working Bagisto development environment
- **PHP Knowledge**: Familiarity with PHP 8.3+ and Laravel concepts
- **Package Development**: Basic understanding of Laravel service providers ([Package Development Guide](../package-development/getting-started.md))
- **Development Tools**: Composer, Git, and a code editor

::: tip Quick Start Path
**New to Bagisto?** Start with the [package generator approach](./create-your-first-payment-method.md#method-1-using-bagisto-package-generator-quick-setup) for your first payment method.

**Want to understand everything?** Follow the [manual setup approach](./create-your-first-payment-method.md#method-2-manual-setup-complete-understanding) for complete control and learning.
:::

## What You'll Build

Throughout this guide, you'll create a **Custom Stripe Payment** method that includes:

### Core Features
- ✅ **Basic Payment Processing**: Without redirect url
- ✅ **Admin Configuration**: Complete settings interface in Bagisto admin
- ✅ **Order Integration**: Seamless integration with Bagisto's order system
- ✅ **Multi-channel Support**: Different value per sales channel

## Architecture Overview

```text
Custom Stripe Payment Package
├── src/
│   ├── Payment/
│   │   └── CustomStripePayment.php     # Payment processing logic
│   ├── Config/
│   │   ├── payment_methods.php         # Payment method definition
│   │   └── system.php                  # Admin interface configuration
│   └── Providers/
│       └── ServiceProvider.php         # Package registration
├── composer.json                       # Package metadata
└── README.md                           # Documentation
```

::: info Development Time Estimate
- **Basic Implementation**: 2-3 hours (using generator)
- **Custom Logic**: 4-6 hours (manual setup + payment integration)
- **Testing & Polish**: 2-4 hours (admin testing, payment flow validation)
:::

## Ready to Start?

Choose your learning path and begin building your custom payment method:

**🚀 [Create Your First Payment Method →](./create-your-first-payment-method.md)**

This section covers both package generator and manual approaches, helping you understand the foundations while building a working payment method.

# Creating Your First Payment Method

Let's create a custom payment method using both approaches available in Bagisto. We'll explore the package generator for quick setup and the manual method for complete understanding.

::: info What You'll Learn
This guide covers the complete process of creating a **Custom Stripe Payment** method, including:
- Package structure setup (generator vs manual)
- Configuration file creation
- Payment processing implementation
- Admin interface integration
:::

## Method 1: Using Bagisto Package Generator (Quick Setup)

The fastest way to create a payment method is using Bagisto's package generator.

### Step 1: Install Package Generator

If you haven't installed the package generator yet:

```bash
composer require bagisto/bagisto-package-generator
```

### Step 2: Generate Payment Method Package

Navigate to your Bagisto root directory and run:

```bash
php artisan package:make-payment-method Webkul/CustomStripePayment
```

### Step 3: Handle Existing Package (If Needed)

If the package directory already exists, use the `--force` flag:

```bash
php artisan package:make-payment-method Webkul/CustomStripePayment --force
```

::: tip Package Generator Benefits
The generator automatically creates:
- Proper directory structure following Bagisto conventions
- Payment method configuration with correct schema
- Base payment class extending AbstractPayment
- System configuration for admin settings
- Service provider with proper registration
:::

### Step 4: Register the Generated Package

After generating the package, you need to register it with Bagisto:

**Add to composer.json** (in Bagisto root directory):

```json{5}
{
    "autoload": {
        ...
        "psr-4": {
            // Other PSR-4 namespaces
            "Webkul\\CustomStripePayment\\": "packages/Webkul/CustomStripePayment/src"
        }
    }
}
```

**Update autoloader:**

```bash
composer dump-autoload
```

**Register service provider** in `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // ... other providers ...
    
    Webkul\CustomStripePayment\Providers\CustomStripePaymentServiceProvider::class,
];
```

**Clear caches:**

```bash
php artisan optimize:clear
```

### Step 5: Configure Your Payment Method

Now test the basic configuration that the generator created:

1. **Go to Admin Panel**: Navigate to **Configuration → Sales → Payment Methods**
2. **Find Your Method**: Look for "Custom Stripe Payment" section
3. **Basic Configuration**: You'll see some basic configuration fields that can be adjusted as per your needs

::: tip Translation Note
You may notice some translation keys are missing as we haven't registered translation files yet. For complete localization setup, refer to the [Localization section in Package Development](../package-development/localization.md). The main purpose here is to understand payment method functionality.
:::

::: info Generator Creates Basic Configuration
The package generator creates a simple payment method with:
- **Basic payment processing**: Simple payment handling logic
- **Basic admin fields**: Essential configuration options
- **Standard structure**: Following Bagisto conventions

For advanced features like webhook handling, refund processing, or complex payment flows, you'll need to customize the generated code or use the manual approach below.
:::

## Method 2: Manual Setup (Complete Understanding)

For developers who want to understand every component, let's create the payment method manually.

### Step 1: Create Package Directory Structure

Create the directory structure for your payment method package:

```bash
mkdir -p packages/Webkul/CustomStripePayment/src/{Payment,Config,Providers}
```

### Step 2: Create Payment Method Configuration

Create the payment methods configuration file:

**Create:** `packages/Webkul/CustomStripePayment/src/Config/payment-methods.php`

```php
<?php

return [
    'custom_stripe_payment' => [
        'code'        => 'custom_stripe_payment',
        'title'       => 'Credit Card (Stripe)',
        'description' => 'Secure credit card payments powered by Stripe',
        'class'       => 'Webkul\CustomStripePayment\Payment\CustomStripePayment',
        'active'      => true,
        'sort'        => 1,
    ],
];
```

### Step 3: Create Payment Class

Create the main payment class:

**Create:** `packages/Webkul/CustomStripePayment/src/Payment/CustomStripePayment.php`

```php
<?php

namespace Webkul\CustomStripePayment\Payment;

use Webkul\Payment\Payment\Payment;

class CustomStripePayment extends Payment
{
    /**
     * Payment method code - must match payment-methods.php key.
     */
    protected $code = 'custom_stripe_payment';

    /**
     * Get redirect URL for payment processing.
     * 
     * Note: You need to create this route in your Routes/web.php file
     * or return null if you don't need a redirect.
     */
    public function getRedirectUrl()
    {
        // return route('custom_stripe_payment.process');
        return null; // No redirect needed for this basic example
    }

    /**
     * Get additional details for frontend display.
     */
    public function getAdditionalDetails()
    {
        return [
            'title' => $this->getConfigData('title'),
            'description' => $this->getConfigData('description'),
            'requires_card_details' => true,
        ];
    }

    /**
     * Get payment method configuration data.
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.custom_stripe_payment.' . $field);
    }
}
```

::: warning Route Configuration
If you uncomment the `getRedirectUrl()` method to return a route, make sure to create the corresponding route in your package's `Routes/web.php` file. For basic payment methods that don't require external redirects, returning `null` is perfectly fine.
:::

### Step 4: Create System Configuration

Create the admin interface configuration:

**Create:** `packages/Webkul/CustomStripePayment/src/Config/system.php`

```php
<?php

return [
    [
        'key'    => 'sales.payment_methods.custom_stripe_payment',
        'name'   => 'Custom Stripe Payment',
        'info'   => 'Custom Stripe Payment Method Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => true,
            ],
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'default_value' => 'Credit Card (Stripe)',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'default_value' => 'Secure credit card payments',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'sort',
                'title'         => 'Sort Order',
                'type'          => 'text',
                'default_value' => '1',
            ],
        ],
    ],
];
```

### Step 5: Create Service Provider

Create the service provider to register your payment method:

**Create:** `packages/Webkul/CustomStripePayment/src/Providers/CustomStripePaymentServiceProvider.php`

```php
<?php

namespace Webkul\CustomStripePayment\Providers;

use Illuminate\Support\ServiceProvider;

class CustomStripePaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // merge payment method configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/payment-methods.php',
            'payment_methods'
        );

        // merge system configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
```

### Step 6: Register Your Package

After creating all the files, you need to register your package with Bagisto:

**Add to composer.json** (in Bagisto root directory):

```json{5}
{
    "autoload": {
        ...
        "psr-4": {
            // Other PSR-4 namespaces
            "Webkul\\CustomStripePayment\\": "packages/Webkul/CustomStripePayment/src"
        }
    }
}
```

**Update autoloader:**

```bash
composer dump-autoload
```

**Register service provider** in `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // ... other providers ...
    
    Webkul\CustomStripePayment\Providers\CustomStripePaymentServiceProvider::class,
];
```

**Clear caches:**

```bash
php artisan optimize:clear
```

## Testing Your Payment Method

Now let's test your custom payment method:

### Step 1: Enable in Admin

1. Go to **Admin Panel → Configuration → Sales → Payment Methods**
2. Find **Custom Stripe Payment** section
3. Set **Enabled** to **Yes**
4. Configure your payment settings
5. Click **Save Configuration**

### Step 2: Frontend Testing

1. Add products to cart
2. Proceed to checkout
3. Enter billing address
4. Verify your payment method appears
5. Test payment processing

## Generated vs Manual Package Structure

Both methods create the same final structure:

```text
packages
└── Webkul
    └── CustomStripePayment
        └── src
            ├── Payment
            │   └── CustomStripePayment.php                 # Payment processing logic
            ├── Config
            │   ├── payment-methods.php                     # Payment method definition
            │   └── system.php                              # Admin configuration
            └── Providers
                └── CustomStripePaymentServiceProvider.php  # Registration
```

::: tip Choosing Your Approach
**Use Package Generator When:**
- Quick prototyping or testing
- Following standard Bagisto patterns
- Building simple payment methods
- Learning Bagisto basics

**Use Manual Setup When:**
- Need complete control over code
- Building complex payment logic
- Want to understand every component
- Customizing beyond standard patterns
:::

## Your Next Steps

Congratulations! You've successfully created a custom payment method for Bagisto. Your payment method now integrates seamlessly with the checkout process.

**Key Achievements:**
- ✅ Built a complete payment method from scratch
- ✅ Implemented basic payment processing logic
- ✅ Created admin configuration interface
- ✅ Integrated with Bagisto's payment system

### Continue Learning

Now that you have a working payment method, dive deeper into specific components:

**📖 [Understanding Payment Configuration →](./understanding-payment-configuration.md)**
Learn about payment method configuration properties and system settings.

**📖 [Understanding Payment Class →](./understanding-payment-class.md)**
Master the payment processing logic and implementation details.

# Understanding Payment Configuration

Now that you've created your payment method, let's understand how the configuration files work and what each property does.

## Payment Method Configuration

In the previous section, we created `config/payment-methods.php`. Let's understand each property:

```php
<?php

return [
    'custom_stripe_payment' => [
        'code'        => 'custom_stripe_payment',
        'title'       => 'Credit Card (Stripe)',
        'description' => 'Secure credit card payments powered by Stripe',
        'class'       => 'Webkul\CustomStripePayment\Payment\CustomStripePayment',
        'active'      => true,
        'sort'        => 1,
    ],
];
```

### Configuration Properties Explained

| Property | Type | Purpose | Description |
|----------|------|---------|-------------|
| **`code`** | String | Unique identifier | Must match the array key and be used consistently across your payment method |
| **`title`** | String | Default display name | Shown to customers during checkout (can be overridden in admin) |
| **`description`** | String | Payment method description | Brief explanation of the payment method |
| **`class`** | String | Payment class namespace | Full path to your payment processing class |
| **`active`** | Boolean | Default status | Whether the payment method is enabled by default |
| **`sort`** | Integer | Display order | Lower numbers appear first in checkout (0 = first) |

::: tip Configuration Key Consistency
The array key (`custom_stripe_payment`) must match the `code` property and be used consistently in:
- Your payment class `$code` property
- System configuration key path
- Route names and identifiers
:::

## System Configuration (Admin Settings)

We also created `system.php` for the admin interface. Let's understand what we built:

```php
<?php

return [
    [
        'key'    => 'sales.payment_methods.custom_stripe_payment',
        'name'   => 'Custom Stripe Payment',
        'info'   => 'Custom Stripe Payment Method Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => true,
            ],
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'default_value' => 'Credit Card (Stripe)',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'default_value' => 'Secure credit card payments',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'sort',
                'title'         => 'Sort Order',
                'type'          => 'text',
                'default_value' => '1',
            ],
        ],
    ],
];
```

### System Configuration Properties Explained

The system configuration creates the admin interface that allows store administrators to manage payment method settings. Each property serves a specific purpose in creating a user-friendly admin experience.

#### Section Properties

These properties define the overall section that appears in the admin configuration panel:

| Property | Purpose | Description |
|----------|---------|-------------|
| **`key`** | Configuration path | `sales.payment_methods.{your_code}` - where settings are stored |
| **`name`** | Admin section title | Displayed in the admin configuration panel |
| **`info`** | Section description | Additional information shown to administrators |
| **`sort`** | Section order | Order in which payment methods appear in admin |

#### Field Properties

These properties define each individual form field that administrators can configure:

| Property | Purpose | Description |
|----------|---------|-------------|
| **`name`** | Field identifier | Used to store and retrieve configuration values |
| **`title`** | Field label | Label displayed in the admin form |
| **`type`** | Input type | `text`, `textarea`, `boolean`, `select`, `password`, etc. |
| **`default_value`** | Default setting | Initial value when first configured |
| **`channel_based`** | Multi-store support | Different values per sales channel |
| **`locale_based`** | Multi-language support | Translatable content per language |
| **`validation`** | Field validation | Rules like `required`, `numeric`, `email` |

### How Configuration is Used

When you call `$this->getConfigData('title')` in your payment class, Bagisto looks up:

```
core()->getConfigData('sales.payment_methods.custom_stripe_payment.title')
```

This retrieves the value from the admin configuration that administrators can modify.

### System Configuration Reference

For detailed information about creating admin interface forms for your payment method, see:

**📖 [Package Development - System Configuration →](../package-development/system-configuration.md)**
Complete guide to creating admin configuration interfaces with all field types and options.

## What's Next?

Now that you understand payment configuration, let's explore the payment class:

**📖 [Understanding Payment Class →](./understanding-payment-class.md)**
Learn how to implement payment processing logic and handle transactions.

**📖 [Back to Getting Started ←](./getting-started.md)**
Review the complete payment method development workflow.

# Understanding Payment Class

When you created your first payment method, you built this `CustomStripePayment` class. Now let's dive deeper into how each part works:

```php
<?php

namespace Webkul\CustomStripePayment\Payment;

use Webkul\Payment\Payment\Payment;

class CustomStripePayment extends Payment
{
    /**
     * Payment method code - must match payment-methods.php key.
     */
    protected $code = 'custom_stripe_payment';

    /**
     * Get redirect URL for payment processing.
     * 
     * Note: You need to create this route in your Routes/web.php file
     * or return null if you don't need a redirect.
     */
    public function getRedirectUrl()
    {
        // return route('custom_stripe_payment.process');
        return null; // No redirect needed for this basic example
    }

    /**
     * Get additional details for frontend display.
     */
    public function getAdditionalDetails()
    {
        return [
            'title' => $this->getConfigData('title'),
            'description' => $this->getConfigData('description'),
            'requires_card_details' => true,
        ];
    }

    /**
     * Get payment method configuration data.
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.custom_stripe_payment.' . $field);
    }
}
```

## Understanding Key Methods

Let's break down each method in your `CustomStripePayment` class and understand what they do and when they're used.

### Payment Method Code

The `$code` property is the foundation of your payment method - it connects all the pieces together.

```php
protected $code = 'custom_stripe_payment';
```

::: info When Do You Need This Property?
Usually, you don't need to explicitly set this property because if your codes are properly set, then config data can get properly. However, if codes are not in convention then you might need this property to override the default behavior.
:::

**Purpose:** This is the unique identifier that ties everything together:
- Must match the key in `payment-methods.php`
- Used in configuration paths
- References your payment method throughout Bagisto

### Redirect URL Handling

This method controls the payment flow - whether customers stay on your site or get redirected elsewhere.

```php
public function getRedirectUrl()
{
    return null; // No redirect needed for this basic example
}
```

::: info Basic Example Note
In this example, we return `null` to keep things simple. At this stage, orders will be placed directly without external redirects. However, in real-life scenarios, you might need to redirect customers to external payment gateways for actual payment processing.
:::

**Purpose:** Determines where to redirect customers for payment processing:
- Return `null` for inline payment forms
- Return a route for external payment pages
- Used for gateways that require external redirects

**When to use redirects:**
- PayPal Checkout
- Bank transfer instructions page
- External payment gateway forms

### Frontend Display Information

This method provides all the data your payment method needs to display correctly on the checkout page.

```php
public function getAdditionalDetails()
{
    return [
        'title' => $this->getConfigData('title'),
        'description' => $this->getConfigData('description'),
        'requires_card_details' => true,
    ];
}
```

**Purpose:** Provides frontend with payment method information:
- `title`: Display name from admin configuration
- `description`: Payment method description
- `requires_card_details`: Tells frontend to show card form
- Custom properties for your specific needs

### Configuration Data Access

This method handles how your payment class retrieves configuration values from the admin panel.

```php
public function getConfigData($field)
{
    return core()->getConfigData('sales.payment_methods.custom_stripe_payment.' . $field);
}
```

::: info When Do You Need This Method?
Usually, you don't need this method because if your payment method code is properly set, then config data can get properly. However, if not in convention then you might need this method to override the default behavior.
:::

**Purpose:** Retrieves admin configuration values:
- Builds the full configuration path
- Accesses values set in admin panel
- Returns the configured value for the specified field

## Best Practices for Payment Classes

Here are some essential practices to follow when building robust payment methods:

::: warning Implementation Note
The methods shown in this section are **demonstration examples** for best practices. In real-world applications, you need to implement these methods according to your specific payment gateway requirements and business logic. Use these examples as reference guides and adapt them to your particular use case.
:::

### Error Handling

Always implement comprehensive error handling in your payment methods:

```php
/**
 * Handle payment errors gracefully.
 */
protected function handlePaymentError(\Exception $e)
{
    // log the error for debugging
    \Log::error('Payment error in ' . $this->code, [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);

    // return user-friendly error message
    return [
        'success' => false,
        'error' => 'Payment processing failed. Please try again or contact support.',
    ];
}
```

### Security Considerations

Always validate and sanitize data before processing payments to protect your application and customers.

```php
/**
 * Validate payment data before processing.
 */
protected function validatePaymentData($data)
{
    $validator = validator($data, [
        'amount' => 'required|numeric|min:0.01',
        'currency' => 'required|string|size:3',
        'customer_email' => 'required|email',
    ]);

    if ($validator->fails()) {
        throw new \InvalidArgumentException($validator->errors()->first());
    }

    return true;
}
```

### Logging and Debugging

Proper logging helps you track payment activities and troubleshoot issues without exposing sensitive information.

```php
/**
 * Log payment activities for debugging and audit.
 */
protected function logPaymentActivity($action, $data = [])
{
    // remove sensitive data before logging
    $sanitizedData = array_diff_key($data, [
        'api_key' => '',
        'secret_key' => '',
        'card_number' => '',
        'cvv' => '',
    ]);

    \Log::info("Payment {$action} for {$this->code}", $sanitizedData);
}
```

### Comprehensive Error Handling

Different payment scenarios require different error handling approaches. Here's how to handle various types of payment errors gracefully:

```php
/**
 * Handle different types of payment errors.
 */
protected function handlePaymentError(\Exception $e)
{
    if ($e instanceof CardException) {
        // card was declined
        $errorMessage = $e->getError()->message;
        
        \Log::warning('Stripe card declined', [
            'error_code' => $e->getError()->code,
            'error_type' => $e->getError()->type,
            'message' => $errorMessage,
        ]);
        
        return [
            'success' => false,
            'error' => $errorMessage,
            'retry_allowed' => true,
        ];
    } elseif ($e instanceof \Stripe\Exception\RateLimitException) {
        // rate limit exceeded
        \Log::error('Stripe rate limit exceeded');
        
        return [
            'success' => false,
            'error' => 'Service temporarily unavailable. Please try again in a moment.',
            'retry_allowed' => true,
        ];
    } elseif ($e instanceof \Stripe\Exception\InvalidRequestException) {
        // invalid request
        \Log::error('Stripe invalid request', ['message' => $e->getMessage()]);
        
        return [
            'success' => false,
            'error' => 'Payment configuration error. Please contact support.',
            'retry_allowed' => false,
        ];
    } else {
        // generic error
        \Log::error('Stripe payment error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return [
            'success' => false,
            'error' => 'Payment processing failed. Please try again.',
            'retry_allowed' => true,
        ];
    }
}
```

## Continue Your Journey

**📖 [Understanding Payment Configuration ←](./understanding-payment-configuration.md)**
Learn about the configuration system that powers your payment method.

**📖 [Back to Creating Your First Payment Method ←](./create-your-first-payment-method.md)**
Review how we built this payment method step by step.

**📖 [Back to Getting Started ←](./getting-started.md)**
Return to the main payment method development guide.

# Getting Started

Creating custom product types in Bagisto allows you to define specialized product behaviors that perfectly match your business needs. Whether you need subscription products, rental items, digital services, or complex product variations, custom product types provide the flexibility to create exactly what your store requires.

For our tutorial, we'll create a **Subscription Product** type that demonstrates all the essential concepts you need to build any type of custom product solution.

::: info What You'll Learn
By the end of this guide, you'll be able to:
- Understand Bagisto's product type architecture
- Create custom product types step by step
- Configure product type properties and behaviors
- Implement custom business logic for specialized products
:::

## Understanding Bagisto Product Type Architecture

Bagisto's product system is built around a flexible type-based architecture that separates product behavior from data storage:

### Core Components

| Component | Purpose | Location |
|-----------|---------|----------|
| **Product Types Configuration** | Defines product type properties | `Config/product-types.php` |
| **Product Type Classes** | Contains business logic for product behavior | `Type/ClassName.php` |
| **`AbstractType` Base Class** | Provides core functionality | Extended by custom classes |
| **Service Provider** | Registers product type | `Providers/ServiceProvider.php` |

### Key Features

- **Flexible Product Behavior**: Custom logic for pricing, inventory, cart operations
- **Type-specific Validation**: Different validation rules per product type
- **Custom Attributes**: Type-specific product attributes and fields
- **Admin Integration**: Seamless integration with product creation interface

## Development Workflow

The typical workflow for creating a custom product type follows these steps:

### 1. Create Your Product Type
Build a complete working product type with all essential components.

**📖 [Create Your First Product Type →](./create-your-first-product-type.md)**

This section shows you how to build a complete working product type, then the remaining sections help you understand how to customize each component.

### 2. Understand Product Type Configuration
Learn how product type configuration works and how to customize product type properties.

**📖 Next:** [Understanding Product Type Configuration](./understanding-product-type-configuration.md)

### 3. Understand AbstractType Methods
Master the key methods available for implementing custom product behavior and business logic.

**📖 Next:** [Understanding AbstractType Class](./understanding-abstract-type-class.md)

### 4. Build Complete Implementation
See how to put it all together in a production-ready subscription product type.

**📖 Next:** [Building Your Subscription Product Type](./building-your-subscription-product-type.md)

You'll have a complete working product type after step 1, and steps 2-4 help you understand how to customize and extend it.

## Prerequisites

Before you begin, ensure you have:

- **Bagisto Installation**: A working Bagisto development environment
- **PHP Knowledge**: Familiarity with PHP 8.3+ and Laravel concepts
- **Package Development**: Basic understanding of Laravel service providers ([Package Development Guide](../package-development/getting-started.md))
- **Development Tools**: Composer, Git, and a code editor

::: tip Quick Start Path
**New to Bagisto?** Start with the [step-by-step creation guide](./create-your-first-product-type.md) for your first product type.

**Want to understand everything?** Follow the complete manual setup to learn every component in detail.
:::

## What You'll Build

Throughout this guide, you'll create a **Subscription Product** type that includes:

### Core Features
- ✅ **Basic Product Behavior**: Subscription-specific logic and validation
- ✅ **Inventory Management**: Service-based products without traditional stock
- ✅ **Admin Configuration**: Complete settings interface in Bagisto admin
- ✅ **Cart Integration**: Subscription-specific cart preparation

## Architecture Overview

```text
Subscription Product Type Package
├── src/
│   ├── Type/
│   │   └── Subscription.php              # Product type logic
│   ├── Config/
│   │   └── product_types.php             # Product type definition
│   └── Providers/
│       └── ServiceProvider.php           # Package registration
├── composer.json                         # Package metadata
└── README.md                             # Documentation
```

::: info Development Time Estimate
- **Basic Implementation**: 1-2 hours (manual setup)
- **Custom Logic**: 2-3 hours (business logic + validation)
- **Testing & Polish**: 1-2 hours (admin testing, cart validation)
:::

## Built-in Product Types Reference

Understanding Bagisto's built-in product types helps you decide what to customize:

### Simple Products
- **Use Case**: Basic products with straightforward pricing and inventory
- **Features**: Standard pricing, inventory tracking, simple cart behavior

### Configurable Products
- **Use Case**: Products with variations (size, color, etc.)
- **Features**: Variant management, attribute-based pricing

### Virtual Products
- **Use Case**: Non-physical products or services
- **Features**: No shipping required, downloadable content

### Grouped Products
- **Use Case**: Related products sold together
- **Features**: Bundle pricing, component selection

## When to Create Custom Product Types

Consider creating a custom product type when:

- ✅ **Unique Behavior**: Your products need behavior not covered by built-in types
- ✅ **Special Pricing**: Complex pricing models or calculations
- ✅ **Custom Attributes**: Type-specific fields and validation rules
- ✅ **Business Rules**: Specific business logic for product handling

## Ready to Start?

Choose your learning path and begin building your custom product type:

**🚀 [Create Your First Product Type →](./create-your-first-product-type.md)**

This section covers the manual setup approach, helping you understand the foundations while building a working subscription product type.

# Creating Your First Product Type

Let's create a custom product type using Bagisto's manual setup approach. We'll build everything from scratch to give you complete understanding of how product types work.

::: info What You'll Learn
This guide covers the complete process of creating a **Subscription Product** type, including:
- Package structure setup (manual approach)
- Configuration file creation
- Product type class implementation
- Admin interface integration
:::

## Manual Setup (Complete Understanding)

Since Bagisto doesn't have a package generator for product types, we'll create everything manually. This approach teaches you every component by building from scratch.

### Step 1: Create Package Structure

Create the package directory structure:

```bash
mkdir -p packages/Webkul/SubscriptionProduct/src/{Type,Config,Providers}
```

### Step 2: Configure Product Type

Create `packages/Webkul/SubscriptionProduct/src/Config/product-types.php`:

```php
<?php

return [
    'subscription' => [
        'key'   => 'subscription',
        'name'  => 'Subscription',
        'class' => 'Webkul\SubscriptionProduct\Type\Subscription',
        'sort'  => 5,
    ],
];
```

### Step 3: Create Product Type Class

Create `packages/Webkul/SubscriptionProduct/src/Type/Subscription.php`:

```php
<?php

namespace Webkul\SubscriptionProduct\Type;

use Webkul\Product\Helpers\Indexers\Price\Simple as SimpleIndexer;
use Webkul\Product\Type\AbstractType;

class Subscription extends AbstractType
{
    /**
     * Returns price indexer class for a specific product type.
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        // SimpleIndexer extends AbstractIndexer, so it handles basic price indexing
        // You can keep this as-is for most custom product types
        return app(SimpleIndexer::class);
    }
}
```




::: tip Implementation Notes
This basic implementation includes the essential `getPriceIndexer()` method that all product types need for proper price indexing. The `SimpleIndexer` class extends `AbstractIndexer` and handles standard price calculations - you can keep this as-is for most custom product types.

You can then override additional methods step by step according to your subscription-based product requirements:

- **isStockable()**: Define if products use inventory tracking
- **showQuantityBox()**: Control quantity input display  
- **haveSufficientQuantity()**: Custom availability logic
- **isSaleable()**: Custom saleable conditions
- **prepareForCart()**: Add subscription-specific cart data

See the [Understanding Abstract Type Class →](./understanding-abstract-type-class.md) section for detailed method explanations.
:::

### Step 4: Create Service Provider

Create `packages/Webkul/SubscriptionProduct/src/Providers/SubscriptionServiceProvider.php`:

```php
<?php

namespace Webkul\SubscriptionProduct\Providers;

use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge product type configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/product-types.php',
            'product_types'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
```

### Step 5: Register Your Package

After creating all the files, you need to register your package with Bagisto:

**Add to composer.json** (in Bagisto root directory):

```json{5}
{
    "autoload": {
        ...
        "psr-4": {
            // Other PSR-4 namespaces
            "Webkul\\SubscriptionProduct\\": "packages/Webkul/SubscriptionProduct/src"
        }
    }
}
```

**Update autoloader:**

```bash
composer dump-autoload
```

**Register service provider** in `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // ... other providers ...
    
    Webkul\SubscriptionProduct\Providers\SubscriptionServiceProvider::class,
];
```

**Clear caches:**

```bash
php artisan optimize:clear
```

### Step 6: Test Your Product Type

After completing the setup, let's test your subscription product type:

### 1. Admin Interface Test

1. **Navigate to Products**: Go to Admin → Catalog → Products
2. **Create New Product**: Click "Add Product"
3. **Select Type**: Choose "Subscription" from the dropdown
4. **Verify Fields**: Check that subscription-specific fields appear
5. **Save Product**: Complete the product creation process

### 2. Frontend Validation

1. **Product Page**: Visit the product page and verify display
2. **Add to Cart**: Test cart functionality with different quantities
3. **Cart Behavior**: Verify subscription data is preserved
4. **Checkout**: Ensure checkout process works correctly

## What You've Built

Congratulations! You've successfully created a basic subscription product type with:

### Completed Components
- ✅ **Product Type Configuration**: Registered subscription type with Bagisto
- ✅ **Product Type Class**: Basic subscription class extending AbstractType
- ✅ **Service Provider**: Properly registered the package with Bagisto
- ✅ **Admin Integration**: Product type appears in admin product creation

### Next Steps for Customization

Your basic product type is now functional! To make it truly subscription-specific, you can:

1. **Override Methods**: Add custom behavior by implementing methods in your Subscription class
2. **Add Validation**: Implement subscription-specific validation rules
3. **Custom Pricing**: Add subscription billing frequency and pricing logic
4. **Inventory Logic**: Implement slot-based or service-based availability

## What's Next?

Congratulations! You've successfully created your first product type. Now you can dive deeper into understanding how each component works:

**📖 [Understanding Product Type Configuration →](./understanding-product-type-configuration.md)**  
Learn how the configuration system defines your product type properties and behavior.

**📖 [Understanding AbstractType Class →](./understanding-abstract-type-class.md)**  
Master the methods available for customizing product behavior and business logic.

**📖 [Building Your Subscription Product Type →](./building-your-subscription-product-type.md)**  
See how to implement a complete, functional subscription product type with real business logic.

Your subscription product type is now ready for enhancement and customization!

# Understanding Product Type Configuration

Product type configuration tells Bagisto how your custom product type should behave. It's a simple array that defines the key properties and connects your configuration to your product type class.

::: info What You'll Learn
- How the configuration file works
- How Bagisto uses this configuration
:::

## Basic Configuration Structure

The `Config/product-types.php` file is a simple PHP array that registers your product type:

```php
<?php

return [
    'subscription' => [
        'key'   => 'subscription',
        'name'  => 'Subscription',
        'class' => 'Webkul\SubscriptionProduct\Type\Subscription',
        'sort'  => 5,
    ],
];
```

## Required Configuration Properties

These four properties are all you need for a basic product type:

| Property | Description | Example |
|----------|-------------|---------|
| `key` | Unique identifier (matches array key) | `'subscription'` |
| `name` | Display name in admin dropdown | `'Subscription'` |
| `class` | Full namespace to your product type class | `'Webkul\SubscriptionProduct\Type\Subscription'` |
| `sort` | Order in dropdown (optional, default: 0) | `5` |

## How Bagisto Uses This Configuration

### 1. Admin Product Creation
When you create a product in admin, Bagisto:
- Reads all registered product types from configuration
- Shows them in the "Product Type" dropdown
- Uses the `name` for display and `sort` for ordering

### 2. Product Type Instantiation
When working with a product, Bagisto:
- Looks up the product's type using the `key`
- Creates an instance of the `class` 
- Calls methods on that instance for product behavior

### 3. Configuration Loading
Your service provider merges your configuration:

```php
public function register(): void
{
    $this->mergeConfigFrom(
        dirname(__DIR__) . '/Config/product-types.php',
        'product_types'  // merges into config ('product_types')
    );
}
```

## Multiple Product Types

You can register multiple product types in one configuration file:

```php
<?php

return [
    'subscription' => [
        'key'   => 'subscription',
        'name'  => 'Subscription',
        'class' => 'Webkul\SubscriptionProduct\Type\Subscription',
        'sort'  => 5,
    ],
    
    'rental' => [
        'key'   => 'rental',
        'name'  => 'Rental Product',
        'class' => 'Webkul\RentalProduct\Type\Rental',
        'sort'  => 6,
    ],
];
```

## What's Next?

Now that you understand how product type configuration works, let's explore the business logic:

**📖 [Understanding AbstractType Class →](./understanding-abstract-type-class.md)**  
Learn about the key methods you can override to implement custom product behavior.

**📖 [Building Your Subscription Product Type →](./building-your-subscription-product-type.md)**  
See how to use configuration and methods together to build a complete subscription product type.

::: tip Key Takeaways

- Configuration is just a simple PHP array
- Only 4 properties are required: `key`, `name`, `class`, `sort`
- Bagisto uses this to show options in admin and instantiate your classes
- Keep it simple - add complexity only when needed

:::

The configuration system is straightforward once you understand these basics. Focus on getting the core properties right first, then enhance as needed.

# Understanding AbstractType Class

The `AbstractType` class is the foundation that all product types in Bagisto extend. Before building advanced subscription features, it's essential to understand the key methods available and how they control product behavior.

::: info What You'll Learn
- Core AbstractType methods and their purposes
- How product availability and cart behavior is controlled
- Which methods to override for custom product types
- Real examples using the subscription product we built
:::

## AbstractType Overview

Every product type in Bagisto extends the `AbstractType` class which provides the core functionality:

```php
<?php

namespace Webkul\Product\Type;

abstract class AbstractType
{
    protected $product;
    protected $isStockable = true;
    protected $showQuantityBox = false;
    protected $haveSufficientQuantity = true;
    protected $canBeMovedFromWishlistToCart = true;
    // ... other properties

    // Key methods you can override:
    public function isSaleable(): bool
    public function isStockable(): bool  
    public function showQuantityBox(): bool
    public function haveSufficientQuantity(int $qty): bool
    public function totalQuantity(): int
    public function prepareForCart(array $data): array
    // ... and more
}
```

## Key Methods to Understand

The AbstractType class provides several important methods that control different aspects of product behavior. Let's explore the most commonly overridden methods and understand when and how to use them in your custom product types.

### Product Availability Control

These methods determine if and how customers can purchase your product:

#### `isSaleable(): bool`

Controls whether the product appears as purchasable:

```php
// Core method signature in AbstractType
public function isSaleable(): bool
{
    // Checks product status and inventory availability
    // Returns true if product can be purchased
}
```

**For subscription products, you might override this to:**

```php
public function isSaleable(): bool
{
    // Check basic conditions first
    if (! parent::isSaleable()) {
        return false;
    }
    
    // Check subscription-specific availability
    // Add your custom subscription-specific availability logic here
}
```

#### `haveSufficientQuantity(int $qty): bool`

Checks if enough quantity is available for purchase:

```php
// Core method signature in AbstractType
public function haveSufficientQuantity(int $qty): bool
{
    // Validates if requested quantity is available
    // Returns true if sufficient quantity exists
}
```

**For subscriptions:**

```php
public function haveSufficientQuantity(int $qty): bool
{
    // Add your custom subscription-specific availability logic here
    // For now, returning true to allow all quantities (you'll customize this based on your subscription slots logic)
    return true;
}
```

### Inventory and Stock Control

These methods control how your product type handles inventory tracking and stock management:

#### `isStockable(): bool`

Determines if the product uses inventory tracking:

```php
// Core method signature in AbstractType
public function isStockable(): bool
{
    // Returns whether product requires inventory management
    // Default is true for most product types
}
```

**For subscription products:**

```php
public function isStockable(): bool
{
    return false; // Subscriptions don't use traditional inventory
}
```

#### `totalQuantity(): int`

Returns total available quantity:

```php
// Core method signature in AbstractType
public function totalQuantity(): int
{
    // Returns total available quantity for the product
    // Usually gets data from inventory or product attributes
}
```

**For subscriptions:**

```php
public function totalQuantity(): int
{
    // Add your custom subscription-specific availability logic here
    // For example, you might have a custom attribute like `subscription_slots`
    return $this->product->subscription_slots ?? 0;
}
```

### User Interface Control

These methods control how your product appears and behaves on the frontend, affecting the user experience and purchase flow.

#### `showQuantityBox(): bool`

Controls whether quantity input appears on product page:

```php
// Core method signature in AbstractType
public function showQuantityBox(): bool
{
    // Returns whether to display quantity input box
    // Default varies by product type
}
```

**For subscriptions:**

```php
public function showQuantityBox(): bool
{
    // Return true to show quantity input, or false if you want fixed quantity purchases
    return true;
}
```

### Pricing and Display Methods

These methods handle product pricing calculations and display formatting, which are essential for any product type that needs custom pricing logic:

#### `getProductPrices(): array`

Returns structured pricing data for the product:

```php
// Core method signature in AbstractType
public function getProductPrices(): array
{
    // Returns structured pricing data with regular and final prices
    // Includes both raw prices and formatted currency strings
}
```

**For subscription products with custom pricing:**

```php
public function getProductPrices(): array
{
    $basePrice = $this->product->price;
    
    // Apply subscription discount if applicable
    $subscriptionDiscount = $this->product->subscription_discount ?? 0;
    $finalPrice = $basePrice - ($basePrice * $subscriptionDiscount / 100);
    
    return [
        'regular' => [
            'price'           => core()->convertPrice($basePrice),
            'formatted_price' => core()->currency($basePrice),
        ],
        'final'   => [
            'price'           => core()->convertPrice($finalPrice),
            'formatted_price' => core()->currency($finalPrice),
        ],
    ];
}
```

#### `getPriceHtml(): string`

Generates the complete price HTML for frontend display:

```php
// Core method signature in AbstractType
public function getPriceHtml(): string
{
    // Generates complete price HTML for frontend display
    // Uses pricing view templates with product and pricing data
}
```

**For subscription products with custom pricing display:**

```php
public function getPriceHtml(): string
{
    // You can customize the pricing view for subscriptions
    return view('subscription::products.prices.subscription', [
        'product' => $this->product,
        'prices'  => $this->getProductPrices(),
        'subscription_info' => [
            'frequency' => $this->product->subscription_frequency,
            'discount' => $this->product->subscription_discount,
        ],
    ])->render();
}
```

### Validation Methods

These methods handle form validation for product-specific data during product creation and updates:

#### `getTypeValidationRules(): array`

Returns validation rules for product type specific fields:

```php
// Core method signature in AbstractType
public function getTypeValidationRules(): array
{
    // Returns array of validation rules for product type specific fields
    // Used during product creation and update processes
}
```

**For subscription products with custom validation:**

```php
public function getTypeValidationRules(): array
{
    return [
        'subscription_frequency'     => 'required|in:weekly,monthly,quarterly,yearly',
        'subscription_discount'      => 'nullable|numeric|min:0|max:100',
        'subscription_duration'      => 'nullable|integer|min:1',
        'subscription_trial_period'  => 'nullable|integer|min:0',
        'subscription_slots'         => 'required|integer|min:1',
    ];
}
```

**For downloadable products (real Bagisto example):**

```php
public function getTypeValidationRules(): array
{
    return [
        'downloadable_links.*.type'       => 'required',
        'downloadable_links.*.file'       => 'required_if:type,==,file',
        'downloadable_links.*.file_name'  => 'required_if:type,==,file',
        'downloadable_links.*.url'        => 'required_if:type,==,url',
        'downloadable_links.*.downloads'  => 'required',
        'downloadable_links.*.sort_order' => 'required',
    ];
}
```

### Admin Interface Customization

These properties and methods control how your product type appears in the admin interface, particularly in the product edit page:

#### `$additionalViews` Property

Specifies additional blade views to include in the product edit page:

```php
// Core property in AbstractType
protected $additionalViews = [];
```

**For subscription products with custom admin fields:**

```php
/**
 * These blade files will be included in product edit page.
 *
 * @var array
 */
protected $additionalViews = [
    'subscription::admin.catalog.products.edit.subscription-settings',
    'subscription::admin.catalog.products.edit.subscription-pricing',
];
```

#### `$skipAttributes` Property

Specifies which attributes to skip for this product type:

```php
// Core property in AbstractType
protected $skipAttributes = [];
```

**For subscription products that don't need certain attributes:**

```php
/**
 * Skip attribute for subscription product type.
 *
 * @var array
 */
protected $skipAttributes = [
    'weight',
    'dimensions',
    'color',
    'size',
];
```

**For digital products example:**

```php
protected $skipAttributes = [
    'weight',
    'height',
    'width',
    'depth',
];
```

::: tip Custom Admin Sections
Use `additionalViews` to add:
- Custom product configuration forms
- Specialized pricing options
- Product type specific settings
- Integration configurations
- Advanced validation options

Use `skipAttributes` to:
- Hide irrelevant attributes for specific product types
- Simplify the admin interface
- Prevent unnecessary data entry
- Focus on product type specific fields

These views are automatically included in the product edit page and have access to the `$product` variable.
:::

### Cart Integration

#### `prepareForCart(array $data): array`

The most important method - processes product data before adding to cart:

```php
// Core method signature in AbstractType
public function prepareForCart(array $data): array
{
    // Processes product data for cart addition
    // Returns array of cart item data or error message
    // Handles pricing, validation, and product-specific logic
}
```

**For subscription products:**

```php
public function prepareForCart(array $data): array
{
    // Validate subscription-specific data first
    // For example, if your form passes a subscription_frequency field that needs validation
    if (empty($data['subscription_frequency'])) {
        return 'Please select subscription frequency.';
    }
    
    // Get base cart data from parent
    $cartData = parent::prepareForCart($data);
    
    // Add subscription-specific information to the cart data
    // Note: We're accessing the first cart item [0] - if you have multiple items, you'll need to loop through them
    $cartData[0]['additional']['subscription_frequency'] = $data['subscription_frequency'];
    $cartData[0]['additional']['subscription_start_date'] = $data['start_date'] ?? now()->addDays(1)->format('Y-m-d');
    
    return $cartData;
}
```



## Exploring More Methods

The methods covered above are the most commonly overridden ones, but the AbstractType class contains many more methods that you can customize based on your specific requirements. We recommend exploring the full AbstractType class to discover additional methods that might be useful for your custom product type implementation.

::: tip Pro Tip
Check out the complete AbstractType class in `packages/Webkul/Product/src/Type/AbstractType.php` to see all available methods and understand their purposes. This will help you identify which methods to override for your specific use case.
:::

## What's Next?

Now that you understand the key `AbstractType` methods, let's put them into practice:

**📖 [Building Your Subscription Product Type →](./building-your-subscription-product-type.md)**  
See how to implement these methods in a complete, functional subscription product type with real business logic.

::: tip Key Takeaways

**Essential Methods:**
- `isSaleable()` - Controls product availability
- `isStockable()` - Determines inventory behavior  
- `showQuantityBox()` - Controls UI elements
- `getProductPrices()` - Handles pricing calculations
- `getPriceHtml()` - Generates price display
- `getTypeValidationRules()` - Defines validation rules
- `prepareForCart()` - Handles cart integration

**Essential Properties:**
- `$additionalViews` - Custom admin interface sections
- `$skipAttributes` - Skip irrelevant attributes for product type

**Override Patterns:**
- Call parent methods first when possible
- Add your custom logic on top
- Handle errors gracefully
- Test thoroughly in different scenarios

:::

Understanding these AbstractType methods is crucial before implementing advanced features. The next section will show you how to use these methods to build sophisticated subscription functionality.

# Building Your Subscription Product Type

Now that you understand the `AbstractType` methods, let's implement a complete subscription product type class. This guide shows you how to build a functional product type that handles subscription-specific logic.

::: info What You'll Learn
- How to implement a complete subscription product type class
- Practical usage of `AbstractType` methods with real business logic
- Adding subscription-specific attributes and handling form data
- Testing your product type implementation
:::

## From Basic to Functional

Remember your basic `Subscription` class? Let's enhance it with the `AbstractType` methods we learned about:

### Your Current Basic Class

```php
<?php

namespace Webkul\SubscriptionProduct\Type;

use Webkul\Product\Type\AbstractType;

class Subscription extends AbstractType
{
    // Basic implementation - just extends `AbstractType`
}
```

### Enhanced Subscription Class

Let's add the key methods to make it functional:

::: tip Need to Understand the Methods?
If you're not familiar with the `AbstractType` methods we're implementing below, check out the **[Key Methods to Understand →](./understanding-abstract-type-class.md#key-methods-to-understand)** section first to learn what each method does and when to use them.
:::

```php
<?php

namespace Webkul\SubscriptionProduct\Type;

use Webkul\Product\Helpers\Indexers\Price\Simple as SimpleIndexer;
use Webkul\Product\Type\AbstractType;

class Subscription extends AbstractType
{
    /**
     * Returns price indexer class for a specific product type.
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        // SimpleIndexer extends AbstractIndexer, so it handles basic price indexing
        // You can keep this as-is for most custom product types
        return app(SimpleIndexer::class);
    }
    
    /**
     * Subscriptions don't use traditional inventory.
     */
    public function isStockable(): bool
    {
        return false;
    }
    
    /**
     * Allow customers to select quantity for multiple subscription slots.
     */
    public function showQuantityBox(): bool
    {
        return true;
    }
    
    /**
     * Check if subscription is available for purchase.
     */
    public function isSaleable(): bool
    {
        // Check basic conditions first
        if (! parent::isSaleable()) {
            return false;
        }
        
        // Add your custom subscription-specific availability logic here
        return true;
    }
    
    /**
     * Check if enough subscription slots are available.
     * Note: Assumes you have a `subscription_slots` attribute on your product.
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        // Add your custom subscription-specific availability logic here
        // For now, returning true to allow all quantities (you'll customize this based on your subscription slots logic)
        return true;
    }
    
    /**
     * Return total available subscription slots.
     * Note: Assumes you have a `subscription_slots` attribute on your product.
     */
    public function totalQuantity(): int
    {
        // Add your custom subscription-specific availability logic here
        // For example, you might have a custom attribute like `subscription_slots`
        return $this->product->subscription_slots ?? 0;
    }
    
    /**
     * Prepare subscription data for cart.
     * Note: Assumes your frontend form sends 'subscription_frequency' in the request data.
     */
    public function prepareForCart($data): array
    {
        // Validate subscription-specific data first
        // For example, if your form passes a subscription_frequency field that needs validation
        if (empty($data['subscription_frequency'])) {
            return 'Please select subscription frequency.';
        }
        
        // Get base cart data from parent
        $cartData = parent::prepareForCart($data);
        
        // Add subscription-specific information to the cart data
        // Note: We're accessing the first cart item [0] - if you have multiple items, you'll need to loop through them
        $cartData[0]['additional']['subscription_frequency'] = $data['subscription_frequency'];
        $cartData[0]['additional']['subscription_start_date'] = $data['start_date'] ?? now()->addDays(1)->format('Y-m-d');
        
        return $cartData;
    }
}
```

## Testing Your Subscription Product Type

Now test your enhanced subscription product type:

### Test in Tinker
```bash
php artisan tinker

# Test your methods
>>> $product = \Webkul\Product\Models\Product::where('type', 'subscription')->first()
>>> $subscription = $product->getTypeInstance()

# Test each method
>>> $subscription->isStockable()        // Should return false
>>> $subscription->showQuantityBox()    // Should return true
>>> $subscription->isSaleable()         // Should return true (if product is active)
>>> $subscription->haveSufficientQuantity(5)  // Should return true

# Test cart preparation
>>> $cartData = $subscription->prepareForCart(['quantity' => 2, 'subscription_frequency' => 'monthly'])
>>> $cartData[0]['additional']  // Should show subscription data
```

### Test in Admin
1. **Go to Admin → Catalog → Products**
2. **Create a new subscription product**
3. **Verify the quantity box appears on frontend**
4. **Test adding to cart with different quantities**

## What You've Accomplished

Congratulations! You've successfully completed the product type development journey and built a complete subscription product type for Bagisto:

### ✅ Complete Product Type System
- **Configuration**: `Config/product-types.php` registers your subscription type
- **Service Provider**: `SubscriptionServiceProvider` loads your configuration  
- **Product Type Class**: `Subscription` implements custom business logic
- **Integration**: Works seamlessly with Bagisto's admin and frontend

### ✅ Key Skills Mastered
- **Product Type Creation**: Built a working product type from scratch
- **Configuration System**: Understood how Bagisto manages product types
- **AbstractType Methods**: Implemented custom business logic using key methods
- **Testing & Debugging**: Validated your implementation with real scenarios

# Getting Started

Welcome to Bagisto theme development! This comprehensive guide will take you through the complete journey of creating custom themes, from basic customizations to professional theme packages.

::: info What You'll Learn
- Understanding Bagisto's theme system and configuration
- Creating basic themes using the resources directory
- Building professional theme packages
- Asset management and bundling with Vite
- Best practices for theme development and distribution
:::

## Prerequisites

To get the most out of this guide, you should have:

### Essential Knowledge
- **HTML/CSS**: For styling and layout
- **Blade Templating**: Laravel's templating engine used by Bagisto
- **Basic PHP**: Understanding of PHP syntax and Laravel concepts

### Helpful Knowledge
- **Laravel Package Development**: For creating distributable themes
- **Tailwind CSS**: Bagisto's utility-first CSS framework
- **JavaScript**: For interactive frontend features
- **Vite/Webpack**: For asset bundling and optimization

### Development Environment
- Working Bagisto installation
- Code editor (VS Code, PHPStorm, etc.)
- Node.js (for asset compilation)
- Composer (for package management)

## Your Theme Development Path

### Step 1: Start with Basic Themes
Begin your journey by learning the fundamentals:

**🏪 [Creating Store Theme →](./creating-store-theme.md)**  
Learn to create custom shop themes using the resources directory approach.

**💼 [Creating Admin Theme →](./creating-admin-theme.md)**  
Customize the admin interface with your own admin theme.

### Step 2: Build Professional Packages
Once you're comfortable with basics, advance to professional development:

**📦 [Custom Theme Package →](./creating-custom-theme-package.md)**  
Learn to create proper Laravel packages for your themes with service providers and distribution support.

### Step 3: Master Asset Management
Complete your theme development skills:

**⚡ [Vite-Powered Theme Assets →](./vite-powered-theme-assets.md)**  
Master Vite configuration for optimized asset compilation and modern development workflows.

### Step 4: Advanced Techniques
Deepen your understanding with advanced topics:

**📄 [Understanding Layouts →](./understanding-layouts.md)**  
Master Bagisto's layout system and component architecture.

**🧩 [Blade Components →](./blade-components.md)**  
Learn to use and customize Bagisto's pre-built components.

## Development Tips

::: tip Best Practices
- **Start Simple**: Begin with the resources directory approach before moving to packages
- **Use Version Control**: Always track your theme changes with Git
- **Test Thoroughly**: Check your themes across different devices and browsers
- **Follow Conventions**: Use Bagisto's naming conventions and file structure
:::

::: warning Common Pitfalls
- **Don't Skip Basics**: Understanding the resources approach helps with package development
- **Avoid Hardcoding**: Use Bagisto's configuration and helper functions
- **Theme Conflicts**: Be careful when overriding core templates
- **Asset Caching**: Clear caches during development to see changes
:::

## What's Next?

Now that you understand Bagisto's theme development approach, start your journey:

**🏪 [Creating Store Theme →](./creating-store-theme.md)**  
Learn to create your first custom store theme using the resources directory.

**👩‍💼 [Creating Admin Theme →](./creating-admin-theme.md)**  
Discover how to customize the admin panel interface with custom themes.

**📦 [Custom Theme Package →](./creating-custom-theme-package.md)**  
Advance to creating professional theme packages for distribution and better organization.

# Creating Store Theme

Learn how to create custom store themes for your Bagisto e-commerce platform. This guide covers the basic approach using Bagisto's resources directory, perfect for getting started with theme development.

::: info What You'll Learn
- Understanding Bagisto's theme configuration system
- Creating themes using the resources directory approach
- Configuring theme settings and activating your custom theme
- Best practices for basic theme development
:::

## Understanding Theme Configuration

Bagisto's theme system is managed through the `config/themes.php` file. This central configuration file defines all available themes and their settings.

### Key Configuration Properties

| Property | Description |
|----------|-------------|
| `shop-default` | Specifies which theme is currently active for the storefront |
| `name` | Display name shown in the admin theme selector |
| `views_path` | Directory path containing Blade template files |
| `assets_path` | Directory path for CSS, JavaScript, and image files |
| `parent` | Optional parent theme for inheritance (advanced usage) |
| `vite` | Asset bundling configuration for development and production |

### Default Theme Configuration

Let's examine the default theme configuration structure:

**Step 1: Locate the configuration file**

Navigate to your Bagisto project root and find the themes configuration:

```text
bagisto-project/
├── app/
├── bootstrap/
├── config/
│   ├── app.php
│   ├── themes.php  ← Theme configuration file
│   └── ...
├── database/
└── ...
```

**Step 2: Understanding the configuration structure**

Open `config/themes.php` to see the default configuration:

```php
<?php

return [
    'shop-default' => 'default',

    'shop' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
```

**Configuration breakdown:**
- `shop-default`: Points to the active theme (`'default'` in this case)
- `shop`: Contains all available store theme definitions
- Each theme has its own configuration block with paths and settings

## Creating Your Custom Theme

Now let's create a custom theme step by step:

### Step 1: Add Theme Configuration

Add your new theme to the `config/themes.php` file:

```php{18-29}
<?php

return [
    'shop-default' => 'default',

    'shop' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'custom-theme' => [
            'name'        => 'Custom Theme',
            'assets_path' => 'public/themes/shop/custom-theme',
            'views_path'  => 'resources/themes/custom-theme/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
```

::: tip Vite Configuration Note
The Vite configuration currently uses the default theme settings. We'll cover custom Vite setup in the [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide.
:::

### Step 2: Create Theme Directory Structure

Create the necessary directories for your theme in the `resources` folder:

```bash
# Create theme directory structure
mkdir -p resources/themes/custom-theme/views/home
```

Your directory structure should look like this:

```text
resources/
└── themes/
    └── custom-theme/
        └── views/
            └── home/
                └── index.blade.php
```

::: warning Directory Structure
The directory structure should follow the same conventions as the `shop` package to ensure compatibility and maintainability.
:::

### Step 3: Create Your First Template

Create a home page template at `resources/themes/custom-theme/views/home/index.blade.php`:

```blade
<x-shop::layouts>
    <x-slot:title>
        Custom Theme Home
    </x-slot>

    <div class="container mx-auto mt-8 px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome to Our Custom Theme
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                This is your custom Bagisto store theme in action!
            </p>
        </div>
    </div>
</x-shop::layouts>
```

::: tip Layout Usage
We're using the default `<x-shop::layouts>` component since we haven't created a custom layout yet. Custom layouts will be covered in the [Layouts and Views](./understanding-layouts.md) guide.
:::

::: warning Styling Limitations & Development Approach
When using the default shop layout, you're inheriting the existing Tailwind CSS compilation that's optimized for the default theme. Some custom Tailwind classes might not be available since they weren't included in the original build process.

**This basic approach is intentional** - we're focusing on core theme concepts first before diving into complex tooling. In real-world theme development, you would typically set up your own complete development environment including:

- **Custom Tailwind CSS configuration** with your own design tokens
- **Vue.js or React** for interactive components  
- **Custom build processes** with Vite or Webpack
- **SCSS/PostCSS** preprocessing
- **Asset optimization** and bundling

We'll gradually progress through these advanced concepts in the upcoming guides. For now, stick to the existing Tailwind classes or use custom CSS to avoid compilation issues. The [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide will show you how to set up a complete modern development workflow.
:::

### Step 4: Clear Application Cache

Clear Bagisto's cache to recognize your new theme:

```bash
php artisan optimize:clear
```

### Step 5: Activate Your Theme

1. Log in to your Bagisto admin panel
2. Navigate to **Settings → Channels**
3. Edit your desired channel
4. Change the **Theme** dropdown to "Custom Theme"
5. Click **Save**

### Step 6: View Your Custom Theme

Visit your store's homepage to see your custom theme in action! You should see your new custom design instead of the default Bagisto theme.

## Testing Your Theme

To ensure your theme works correctly:

1. **Check different pages**: Navigate through various store pages to ensure consistent styling
2. **Test responsiveness**: View your theme on different device sizes
3. **Verify functionality**: Ensure all store features work correctly with your theme
4. **Browser testing**: Test across different browsers for compatibility

## What's Next?

Congratulations! You've successfully created your first custom store theme. Here are your next steps:

**💼 [Creating Admin Theme →](./creating-admin-theme.md)**  
Learn to customize the admin panel interface with custom admin themes.

**📦 [Custom Theme Package →](./creating-custom-theme-package.md)**  
Advance to creating professional theme packages for distribution and better organization.

**⚡ [Vite-Powered Theme Assets →](./vite-powered-theme-assets.md)**  
Master modern asset compilation and optimization for your themes.

# Creating Admin Theme

Learn how to create custom admin themes for your Bagisto administration panel. This guide covers the basic approach using Bagisto's resources directory to customize the backend interface.

::: info What You'll Learn
- Understanding Bagisto's admin theme configuration system
- Creating admin themes using the resources directory approach
- Customizing the admin panel interface and dashboard
- Best practices for admin theme development
:::

## Understanding Admin Theme Configuration

Bagisto's admin theme system is managed through the same `config/themes.php` file as store themes. The admin section defines themes specifically for the backend administration interface.

### Key Configuration Properties

| Property | Description |
|----------|-------------|
| `admin-default` | Specifies which theme is currently active for the admin panel |
| `name` | Display name shown in the admin theme selector |
| `views_path` | Directory path containing Blade template files |
| `assets_path` | Directory path for CSS, JavaScript, and image files |
| `parent` | Optional parent theme for inheritance (advanced usage) |
| `vite` | Asset bundling configuration for development and production |

### Default Admin Theme Configuration

Let's examine the default admin theme configuration structure:

**Step 1: Locate the configuration file**

Navigate to your Bagisto project root and find the themes configuration:

```text
bagisto-project/
├── app/
├── bootstrap/
├── config/
│   ├── app.php
│   ├── themes.php  ← Theme configuration file
│   └── ...
├── database/
└── ...
```

**Step 2: Understanding the admin configuration structure**

Open `config/themes.php` and look for the admin section:

```php
<?php

return [
    'admin-default' => 'default',

    'admin' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/admin-themes/default/views',

            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
```

**Configuration breakdown:**
- `admin-default`: Points to the active admin theme (`'default'` in this case)
- `admin`: Contains all available admin theme definitions
- Each theme has its own configuration block with paths and settings

## Creating Your Custom Admin Theme

Now let's create a custom admin theme step by step:

### Step 1: Add Admin Theme Configuration

Add your new admin theme to the `config/themes.php` file:

```php{18-29}
<?php

return [
    'admin-default' => 'default',

    'admin' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/admin-themes/default/views',

            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'custom-admin-theme' => [
            'name'        => 'Custom Admin Theme',
            'assets_path' => 'public/themes/admin/custom-admin-theme',
            'views_path'  => 'resources/admin-themes/custom-admin-theme/views',

            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
```

::: tip Vite Configuration Note
The Vite configuration currently uses the default theme settings. We'll cover custom Vite setup in the [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide.
:::

### Step 2: Create Admin Theme Directory Structure

Create the necessary directories for your admin theme in the `resources` folder:

```bash
# Create admin theme directory structure
mkdir -p resources/admin-themes/custom-admin-theme/views/dashboard
```

Your directory structure should look like this:

```text
resources/
└── admin-themes/
    └── custom-admin-theme/
        └── views/
            └── dashboard/
                └── index.blade.php
```

::: warning Directory Structure
The directory structure should follow the same conventions as the `admin` package to ensure compatibility and maintainability.
:::

### Step 3: Create Your First Admin Template

Create a dashboard template at `resources/admin-themes/custom-admin-theme/views/dashboard/index.blade.php`:

```blade
<x-admin::layouts>
    <x-slot:title>
        Custom Admin Dashboard
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                🎛️ Custom Admin Dashboard
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Welcome to your customized Bagisto admin panel!
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="text-xl font-semibold text-blue-800 mb-2">
                        📊 Analytics
                    </h3>

                    <p class="text-blue-600">
                        Enhanced dashboard analytics and reporting
                    </p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <h3 class="text-xl font-semibold text-green-800 mb-2">
                        🛍️ Orders
                    </h3>

                    <p class="text-green-600">
                        Streamlined order management interface
                    </p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">
                        👥 Customers
                    </h3>
                    
                    <p class="text-purple-600">
                        Enhanced customer management tools
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
```

::: tip Layout Usage
We're using the default `<x-admin::layouts>` component since we haven't created a custom admin layout yet. Custom layouts will be covered in the [Layouts and Views](./understanding-layouts.md) guide.
:::

::: warning Styling Limitations & Development Approach
When using the default admin layout, you're inheriting the existing CSS compilation optimized for the default admin theme. Some custom styling classes might not be available since they weren't included in the original build process.

**This basic approach is intentional** - we're focusing on core admin theme concepts first before diving into complex development tooling. In real-world admin theme development, you would typically set up your own complete development environment including:

- **Custom CSS framework configuration** with your own admin design tokens
- **Vue.js or React** for interactive admin components  
- **Custom build processes** with Vite or Webpack for admin assets
- **SCSS/PostCSS** preprocessing for admin styling
- **Asset optimization** and bundling for admin interfaces

We'll gradually progress through these advanced concepts in the upcoming guides. For now, stick to the existing CSS classes or use custom CSS to avoid compilation issues. The [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide will show you how to set up a complete modern admin development workflow.
:::

### Step 4: Clear Application Cache

Clear Bagisto's cache to recognize your new admin theme:

```bash
php artisan optimize:clear
```

### Step 5: Activate Your Admin Theme

Update the `admin-default` value in your `config/themes.php` file:

```php{4}
<?php

return [
    'admin-default' => 'custom-admin-theme', // Changed from 'default'
    
    'admin' => [
        // ...existing themes
    ],
];
```

### Step 6: View Your Custom Admin Theme

Log in to your Bagisto admin panel and navigate to the dashboard. You should see your new custom admin theme design instead of the default admin interface.

## Testing Your Admin Theme

To ensure your admin theme works correctly:

1. **Check different admin pages**: Navigate through various admin sections to ensure consistent styling
2. **Test responsiveness**: View your admin theme on different screen sizes
3. **Verify functionality**: Ensure all admin features work correctly with your theme
4. **Browser testing**: Test across different browsers for compatibility

## What's Next?

Congratulations! You've successfully created your first custom admin theme. Here are your next steps:

**📦 [Custom Theme Package →](./creating-custom-theme-package.md)**  
Advance to creating professional theme packages for distribution and better organization.

**⚡ [Vite-Powered Theme Assets →](./vite-powered-theme-assets.md)**  
Master modern asset compilation and optimization for your themes.

**📄 [Understanding Layouts →](./understanding-layouts.md)**  
Learn about Bagisto's layout system and how to customize it effectively.

# Creating Custom Theme Package

Learn how to convert your basic custom theme into a professional package structure. This guide shows you how to move the store theme we created earlier into a standalone package for better organization and distribution.

::: info What You'll Learn
- Converting basic themes to package structure
- Understanding Bagisto's package development approach
- Creating service providers for theme packages
- Publishing theme views and assets from packages
- Best practices for theme package organization
:::

## Prerequisites

Before starting this guide, make sure you have completed the [Creating Store Theme](./creating-store-theme.md) tutorial. We'll be converting that basic theme into a package structure.

::: tip Package Benefits
- **Better Organization**: Keep your theme separate from core files
- **Easy Distribution**: Share your theme as a reusable package
- **Version Control**: Manage theme updates independently
- **Professional Structure**: Follow Laravel package conventions
:::

## Understanding Package Structure

Bagisto follows Laravel's package development conventions. A theme package contains all theme-related files in a self-contained structure that can be easily distributed and maintained.

### Package vs Basic Theme Comparison

| Basic Theme (Resources) | Package Theme |
|------------------------|---------------|
| `resources/themes/custom-theme/` | `packages/Webkul/CustomTheme/` |
| Direct file editing | Service provider publishing |
| Limited reusability | Easy distribution |
| Mixed with core files | Self-contained structure |

## Creating Your Theme Package

Let's convert our basic custom theme into a professional package structure.

### Step 1: Create Package Directory Structure

Create the package directory structure in your Bagisto project:

```bash
# Create package directory structure
mkdir -p packages/Webkul/CustomTheme/src/Providers
mkdir -p packages/Webkul/CustomTheme/src/Resources/views
```

Your package structure should look like this:

```text
packages/
└── Webkul/
    └── CustomTheme/
        └── src/
            ├── Providers/
            │   └── CustomThemeServiceProvider.php
            └── Resources/
                └── views/
                    └── home/
                        └── index.blade.php
```

::: warning Directory Structure
The package structure should follow Laravel package conventions with proper PSR-4 namespace organization for maintainability.
:::

### Step 2: Create the Service Provider

Create the service provider at `packages/Webkul/CustomTheme/src/Providers/CustomThemeServiceProvider.php`:

```php
<?php

namespace Webkul\CustomTheme\Providers;

use Illuminate\Support\ServiceProvider;

class CustomThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('themes/custom-theme/views'),
        ], 'custom-theme-views');
    }
}
```

### Step 3: Move Your Theme Files

Move your existing theme files from the basic theme location to your package:

::: warning No Existing Theme Files?
If you jumped directly to this section and don't have existing theme files from the [Creating Store Theme](./creating-store-theme.md) tutorial, you'll need to either:

1. **Go back and complete the basic theme tutorial first** (recommended for understanding)
2. **Create the files directly in your package** using the examples below

This guide assumes you have basic theme files to move from the previous tutorial.
:::

**Copy your theme template:**

Take the home page template you created in the basic theme tutorial and move it to:
`packages/Webkul/CustomTheme/src/Resources/views/home/index.blade.php`

::: tip Moving Existing Files
If you have an existing basic theme, you can simply copy the entire `views` directory from `resources/themes/custom-theme/views/` to `packages/Webkul/CustomTheme/src/Resources/views/` to move all your existing customizations.
:::

**Example template content:**

If you want to add some changes or don't have existing theme files, you can replace the content with the example below:

```blade
<x-shop::layouts>
    <x-slot:title>
        Custom Theme Home
    </x-slot>

    <div class="container mx-auto mt-8 px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-6">
                🎨 Custom Theme Package
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                This theme is now powered by a professional package structure!
            </p>
            
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 max-w-md mx-auto">
                <h3 class="text-xl font-semibold text-blue-800 mb-2">
                    📦 Package Benefits
                </h3>

                <p class="text-blue-600">
                    Better organization, easy distribution, and professional development workflow.
                </p>
            </div>
        </div>
    </div>
</x-shop::layouts>
```

### Step 4: Configure Package Autoloading

Update your root `composer.json` file to include the package namespace and regenerate the autoloader:

```json{5}
"autoload": {
    ...
    "psr-4": {
        // Other PSR-4 namespaces
        "Webkul\\CustomTheme\\": "packages/Webkul/CustomTheme/src"
    }
}
```

After updating the `composer.json` file, run the following command to register your package:

```bash
composer dump-autoload
```

::: warning Important Step
This command regenerates the autoloader files to include your new package namespace. Without this step, Laravel won't be able to find your package classes.
:::

### Step 5: Register the Service Provider

Add your service provider to `bootstrap/providers.php`:

```php{8}
<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // Other service providers...
    
    Webkul\CustomTheme\Providers\CustomThemeServiceProvider::class,
];
```

### Step 6: Update Theme Configuration

Ensure your `config/themes.php` file has the correct configuration for your package theme:

::: info Optional Step
If you completed the [Creating Store Theme](./creating-store-theme.md) tutorial, you should already have this configuration. However, if you jumped directly to this section or want to verify your setup, make sure your theme configuration matches the example below.
:::

```php{18-29}
<?php

return [
    'shop-default' => 'custom-theme',

    'shop' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'custom-theme' => [
            'name'        => 'Custom Theme Package',
            'assets_path' => 'public/themes/shop/custom-theme',
            'views_path'  => 'resources/themes/custom-theme/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
```

### Step 7: Publish Theme Views

Publish your package views to the application:

```bash
php artisan vendor:publish --provider="Webkul\CustomTheme\Providers\CustomThemeServiceProvider"
```

If you need to overwrite existing files, use the `--force` flag:

```bash
php artisan vendor:publish --provider="Webkul\CustomTheme\Providers\CustomThemeServiceProvider" --force
```

::: tip When to Use --force
Use the `--force` flag when you're updating existing theme files or when the destination directory already contains published files from a previous run.
:::

### Step 8: Clear Cache and Test

Clear the application cache and test your package theme:

```bash
php artisan optimize:clear
```

Visit your store's homepage to see your package theme in action!

::: tip Verification Steps
1. **Check Admin Panel**: Go to Settings → Channels and verify "Custom Theme Package" appears in the theme dropdown
2. **Verify File Structure**: Confirm your views are published to `resources/themes/custom-theme/views/`
3. **Test Homepage**: Visit your store frontend to see the updated theme
:::

## Package Development Workflow

Now that your theme package is set up, here's how to work with it effectively during development:

### Adding New Views

To add new views to your package:

1. **Create the view file** in your package:
   ```text
   packages/Webkul/CustomTheme/src/Resources/views/catalog/products/index.blade.php
   ```

::: warning Directory Structure
The directory structure should follow the same paths as the corresponding views in the default Bagisto theme. For example, if you want to customize a product page, maintain the same folder hierarchy: `catalog/products/index.blade.php`.
:::

2. **Republish the package views**:
   ```bash
   php artisan vendor:publish --provider="Webkul\CustomTheme\Providers\CustomThemeServiceProvider" --force
   ```

### Updating Existing Views

When you modify views in your package:

1. **Edit the package file** (not the published file)
2. **Republish to apply changes**:
   ```bash
   php artisan vendor:publish --provider="Webkul\CustomTheme\Providers\CustomThemeServiceProvider" --force
   ```

::: warning Development Workflow
Always edit files in your package directory (`packages/Webkul/CustomTheme/src/Resources/views/`), not in the published location (`resources/themes/custom-theme/views/`). The published files will be overwritten when you republish.
:::

## Alternative Development Workflows

Publishing your package views repeatedly during development can be tedious. Here are two efficient alternatives to streamline your development process:

### Option 1: Develop in Resources First

This approach lets you develop your theme directly in the published location, then move everything to your package at once.

**Development Process:**
1. Develop all your changes directly in `resources/themes/custom-theme/views/`
2. Test and iterate on your customizations without republishing
3. Once satisfied, copy all files to your package: `packages/Webkul/CustomTheme/src/Resources/views/`
4. Republish once to ensure everything works from the package

**Benefits:**
- No constant republishing during development
- Faster iteration and testing
- Simple file management

### Option 2: Symlink Approach (Advanced)

Create symbolic links to enable real-time development without any republishing.

**Setup Commands:**
```bash
# Remove the published views directory
rm -rf resources/themes/custom-theme/views

# Create symlink from package to resources (use absolute path)
ln -s $(pwd)/packages/Webkul/CustomTheme/src/Resources/views resources/themes/custom-theme/views
```

**Benefits:**
- Real-time changes without republishing
- Direct development in package files
- Automatic synchronization

::: warning Symlink Requirements
- Requires file system permissions to create symbolic links
- Works best on Unix-like systems (Linux, macOS)
- May require additional setup on Windows systems
- Ensure you're running commands from your Bagisto project root
:::

**Choose the workflow that best fits your development style and system requirements.**

## Testing Your Package Theme

To ensure your package theme works correctly:

1. **Check view publishing**: Verify files are published to `resources/themes/custom-theme/`
2. **Test theme activation**: Ensure the theme appears in admin theme selector
3. **Verify functionality**: Navigate through different store pages
4. **Test republishing**: Make changes and republish to confirm workflow

## What's Next?

Congratulations! You've successfully converted your basic theme into a professional package structure. Here are your next steps:

 **⚡ [Vite-Powered Theme Assets →](./vite-powered-theme-assets.md)**  
Learn to set up modern asset compilation and optimization for your theme package.

**📄 [Understanding Layouts →](./understanding-layouts.md)**  
Master custom layouts and advanced view organization in your theme packages.

**🧩 [Blade Components →](./blade-components.md)**  
Learn to use Bagisto's pre-built components within your theme package structure.

# Vite-Powered Theme Assets

Learn how to set up modern asset compilation for your custom theme package using Vite. This guide builds upon the [Custom Theme Package](./creating-custom-theme-package.md) we created earlier, adding professional asset bundling with CSS, JavaScript, and image optimization.

::: info What You'll Learn
- Setting up Vite for theme package asset compilation
- Configuring Tailwind CSS for your custom theme
- Managing CSS, JavaScript, and static assets
- Integrating compiled assets with your theme package
- Development and production build workflows
:::

## Prerequisites

Before starting this guide, make sure you have completed the [Custom Theme Package](./creating-custom-theme-package.md) tutorial. We'll be adding modern asset bundling to the `CustomTheme` package we created.

::: tip Why Asset Bundling?
- **Modern Development**: Use the latest CSS and JavaScript features
- **Optimization**: Minified and optimized assets for production
- **Developer Experience**: Hot reload, source maps, and fast compilation
- **Professional Workflow**: Industry-standard build tools and processes
:::

## Understanding Asset Management

Assets in web development refer to files such as stylesheets, scripts, and images that enhance functionality, design, and interactivity. For theme packages, proper asset management ensures:

- **CSS**: Tailwind CSS and custom styles for theme appearance
- **JavaScript**: Interactive components and dynamic behavior  
- **Images**: Optimized graphics, icons, and media content
- **Fonts**: Custom typography and icon fonts

::: info Laravel Vite Integration
Bagisto uses Laravel's Vite integration for modern asset compilation. For detailed information about Laravel's frontend tooling, visit the [Laravel documentation](https://laravel.com/docs/12.x/frontend#bundling-assets).
:::

## Setting Up Your Theme Package Assets

Let's add asset compilation to the `CustomTheme` package we created in the previous tutorial. We'll create a proper asset structure and configure modern build tools.

### Step 1: Create Asset Directory Structure

Add the assets directory to your existing theme package:

```bash
# Navigate to your theme package
cd packages/Webkul/CustomTheme

# Create asset directories
mkdir -p src/Resources/assets/{css,js,images,fonts}
```

Your package structure will now look like this:

```text
packages/Webkul/CustomTheme/
├── src/
│   ├── Providers/
│   │   └── CustomThemeServiceProvider.php
│   └── Resources/
│       ├── views/
│       │   └── home/
│       │       └── index.blade.php
│       └── assets/
│           ├── css/
│           ├── js/
│           ├── images/
│           └── fonts/
```

::: tip What's Next
In the following steps, we'll create the configuration files (`package.json`, `vite.config.js`, etc.) and the actual asset files (`app.css`, `app.js`) to complete our setup.
:::

::: warning Package Context
Make sure you're working within your `CustomTheme` package directory. All commands and file paths in this guide assume you're in `packages/Webkul/CustomTheme/`.
:::

### Step 2: Create Base Asset Files

Create your main CSS and JavaScript files for the theme:

**Create `src/Resources/assets/css/app.css`:**

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**Create `src/Resources/assets/js/app.js`:**

```javascript
import.meta.glob(["../images/**", "../fonts/**"]);
```

## Configure Asset Compilation

Now let's set up the build tools to compile and optimize your theme assets.

### Step 3: Create Configuration Files

Create the following configuration files in your package root (`packages/Webkul/CustomTheme/`):

#### Setting Up Node Dependencies

Create `package.json` with the following content:

::: tip Don't Get Overwhelmed!
Don't worry about understanding every dependency listed below. While we could walk you through installing each package step-by-step, copying this complete configuration file is much easier for beginners. These are standard packages used in most modern Laravel projects with Vite and Tailwind CSS.
:::

```json
{
    "name": "custom-theme",
    "private": true,
    "description": "Custom Theme Package for Bagisto",
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "autoprefixer": "^10.4.14",
        "axios": "^1.1.2",
        "laravel-vite-plugin": "^0.7.2",
        "postcss": "^8.4.23",
        "tailwindcss": "^3.3.2",
        "vite": "^4.0.0",
        "vue": "^3.2.47"
    },
    "dependencies": {
        "@vee-validate/i18n": "^4.9.1",
        "@vee-validate/rules": "^4.9.1",
        "mitt": "^3.0.0",
        "vee-validate": "^4.9.1",
        "vue-flatpickr": "^2.3.0"
    }
}
```

::: tip Package Scripts
- `npm run dev`: Starts development server with hot reload
- `npm run build`: Builds optimized assets for production
:::

Now install the dependencies:

```bash
# Make sure you're in your package directory
cd packages/Webkul/CustomTheme

# Install dependencies
npm install
```

#### Setting Up Vite

Create `vite.config.js` configured for your `CustomTheme` package:

::: tip Don't Get Overwhelmed!
This Vite configuration might look complex, but you don't need to understand every detail right now. This setup is based on Bagisto's shop package configuration with paths adjusted for your custom theme package. You can always refer to `packages/Webkul/Shop/vite.config.js` in your Bagisto installation to see how the core shop package handles Vite configuration.
:::

```javascript
import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig(({ mode }) => {
    const envDir = "../../../";

    Object.assign(process.env, loadEnv(mode, envDir));

    return {
        build: {
            emptyOutDir: true,
        },

        envDir,

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5173,
            cors: true,
        },

        plugins: [
            laravel({
                hotFile: "../../../public/custom-theme-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/custom-theme/build",
                input: [
                    "src/Resources/assets/css/app.css",
                    "src/Resources/assets/js/app.js",
                ],
                refresh: true,
            }),
        ],

        experimental: {
            renderBuiltUrl(filename, { hostId, hostType, type }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },
    };
});
```

#### Setting Up Tailwind CSS

Create `tailwind.config.js` to scan your theme files:

::: tip Comprehensive Configuration!
This Tailwind configuration includes all the settings used in Bagisto's shop package, giving you access to custom breakpoints, colors, and fonts that match Bagisto's design system. The `content` array tells Tailwind where to find your CSS classes, and the custom theme extensions provide Bagisto-specific styling. You can reference `packages/Webkul/Shop/tailwind.config.js` in your Bagisto installation to see the original configuration this is based on.
:::

```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/Resources/**/*.blade.php", 
        "./src/Resources/**/*.js",
        "../../../resources/themes/custom-theme/**/*.blade.php" // Include published views
    ],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1440px",
            },

            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            colors: {
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
            },

            fontFamily: {
                poppins: ["Poppins"],
                dmserif: ["DM Serif Display"],
            },
        }
    },

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
```

#### Setting Up PostCSS

Create `postcss.config.js` for CSS processing:

::: tip Simple CSS Processing!
This PostCSS configuration is straightforward - it just tells PostCSS to use Tailwind CSS and Autoprefixer plugins. PostCSS processes your CSS and Autoprefixer automatically adds vendor prefixes for browser compatibility. You don't need to modify this configuration for basic theme development.
:::

```javascript
module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
}
```

## Integrate Assets with Your Theme

Now let's update your theme to use the compiled assets and enhance the styling.

### Step 4: Update Theme Configuration

Update your theme configuration in `config/themes.php` to include asset settings:

```php{7-9}
'custom-theme' => [
    'name'        => 'Custom Theme Package',
    'assets_path' => 'public/themes/shop/custom-theme',
    'views_path'  => 'resources/themes/custom-theme/views',

    'vite'        => [
        'hot_file'                 => 'custom-theme-vite.hot',
        'build_directory'          => 'themes/custom-theme/build',
        'package_assets_directory' => 'src/Resources/assets',
    ],
],
```

### Step 5: Configure Bagisto Vite

Add your theme package to `config/bagisto-vite.php`:

```php
'custom-theme' => [
    'hot_file'                 => 'custom-theme-vite.hot',
    'build_directory'          => 'themes/custom-theme/build',
    'package_assets_directory' => 'src/Resources/assets',
],
```

### Step 6: Set Up Development Workflow (Recommended)

For efficient development with hot reload, you should work directly from your package files rather than published views. This ensures your changes are immediately reflected without republishing.

**Option A: Symbolic Link Approach (Linux/Mac - Recommended)**

Create a symbolic link to work directly from your package:

```bash
# Remove the published views directory (backup first if you have changes)
rm -rf resources/themes/custom-theme

# Create symbolic link to your package views (from Bagisto root directory)
ln -s $(pwd)/packages/Webkul/CustomTheme/src/Resources/views resources/themes/custom-theme
```

**Option B: Direct Package Development (All Platforms)**

You can also work directly in your package and republish as needed:

```bash
# After making changes, republish views
php artisan vendor:publish --provider="Webkul\CustomTheme\Providers\CustomThemeServiceProvider" --force
```

::: tip Why Symbolic Links?
- **Hot Reload Works**: Changes in package files are immediately reflected
- **No Republishing**: Skip the republish step during development
- **Faster Workflow**: Edit once, see changes instantly
- **Better for Teams**: All developers work from the same source files
:::

::: warning Platform Considerations
- **Linux/Mac**: Symbolic links work seamlessly
- **Windows**: May require administrator privileges or WSL for symbolic links
- **Alternative**: Use direct package development with occasional republishing
:::

### Step 7: Test Your Asset Compilation

Now let's test that your asset compilation is working properly:

```bash
# Make sure you're in your package directory
cd packages/Webkul/CustomTheme

# Start the development server
npm run dev
```

You should see output similar to:

```
VITE v4.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
➜  press h to show help, q to quit
```

::: tip Testing Your Setup
1. **Keep the dev server running** in your terminal
2. **Visit your Bagisto store** in the browser
3. **Open browser dev tools** (F12) and check the Network tab
4. **Look for your theme assets** being loaded from the Vite dev server
5. **Test hot reload** by making a small change to your `app.css` file
:::

::: warning Hot Reload Troubleshooting
If hot reload isn't working:
- **Check your workflow**: Are you using symbolic links or working from published views?
- **Verify paths**: Ensure Vite is watching the correct directories
- **Restart dev server**: Sometimes a restart resolves connection issues
- **Check browser console**: Look for WebSocket connection errors
:::

::: warning General Troubleshooting
If you encounter other issues:
- Ensure your theme is set as active in Bagisto admin
- Check that your theme configuration in `config/themes.php` is correct
- Verify the Vite configuration paths match your package structure
- Make sure all dependencies were installed successfully with `npm install`
:::

Now that your assets are compiled and configured, you can start building your theme with modern Tailwind CSS classes and JavaScript features. The shop layout will automatically include your compiled assets when your theme is active.

## Working with Vite and Shop Assets

When you first set up your theme package with the minimal assets we created, you'll notice that your store looks broken or unstyled. This is expected! Here's why and how to fix it:

### Understanding Asset Inheritance

Your custom theme package currently has only minimal assets:
- Basic Tailwind CSS directives in `app.css`
- Empty JavaScript file in `app.js`

However, since you're extending the **shop layout** (`<x-shop::layouts>`), you need access to all the shop's base styles, components, and functionality to maintain a working storefront.

### Including Shop Assets in Your Theme

To have full control while building upon Bagisto's foundation, the simplest approach is to copy the complete shop assets folder to your theme:

**Copy Complete Shop Assets:**

```bash
# Copy entire shop assets folder (from Bagisto root directory)
cp -r packages/Webkul/Shop/src/Resources/assets/* packages/Webkul/CustomTheme/src/Resources/assets/

# Copy entire shop views folder
cp -r packages/Webkul/Shop/src/Resources/views/* packages/Webkul/CustomTheme/src/Resources/views/
```

This copies all shop resources including:
- All CSS files and styles
- All JavaScript files and functionality  
- Images, fonts, and other static assets
- Complete Blade template structure
- All shop views and components

**Update your home view to showcase your custom theme:**

Now update `packages/Webkul/CustomTheme/src/Resources/views/home/index.blade.php` with custom content:

```blade
<x-shop::layouts>
    <x-slot:title>
        Custom Theme Home
    </x-slot>

    {{-- Hero Section --}}
    <div class="hero-section bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">
                🎨 Custom Theme Package
            </h1>
            
            <p class="text-xl mb-8 opacity-90">
                Professional theme development with modern asset bundling
            </p>
            
            <button class="bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                Explore Features
            </button>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="container mx-auto mt-16 px-4 py-8">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    📦 Package Structure
                </h3>
                <p class="text-gray-600">
                    Professional Laravel package organization with proper namespace and autoloading.
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h3 class="text-xl font-semibold text-green-600 mb-4">
                    ⚡ Modern Assets
                </h3>

                <p class="text-gray-600">
                    Vite-powered asset compilation with Tailwind CSS and JavaScript bundling.
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h3 class="text-xl font-semibold text-purple-600 mb-4">
                    🚀 Production Ready
                </h3>

                <p class="text-gray-600">
                    Optimized builds, hot reload development, and professional workflow.
                </p>
            </div>
        </div>
    </div>

    {{-- Feature Showcase --}}
    <div class="bg-gray-50 py-16 mt-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    Complete Shop Foundation
                </h2>

                <p class="text-gray-600 max-w-2xl mx-auto">
                    Your custom theme now includes all shop assets and views, giving you complete control to customize any part of the storefront.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl font-semibold mb-4">Full Asset Control</h3>

                    <ul class="space-y-2 text-gray-600">
                        <li>✅ Complete CSS foundation with Tailwind</li>
                        <li>✅ All JavaScript functionality included</li>
                        <li>✅ Shop components and layouts</li>
                        <li>✅ Images, fonts, and static assets</li>
                        <li>✅ Hot reload development workflow</li>
                    </ul>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="font-semibold mb-3">Theme Package Structure</h4>
                    
                    <pre class="text-sm text-gray-600 overflow-x-auto"><code>packages/Webkul/CustomTheme/
├── src/Resources/
│   ├── assets/     (complete shop assets)
│   └── views/      (complete shop views)
├── package.json
├── vite.config.js
└── tailwind.config.js</code></pre>
                </div>
            </div>
        </div>
    </div>
</x-shop::layouts>
```

### Why This Approach Works

1. **Complete Control**: You have the full shop assets in your package to modify directly
2. **No Import Issues**: Avoids complex import paths and potential build problems
3. **Easy Customization**: Modify styles and functionality directly in your files
4. **Simple Configuration**: Keep your Vite config clean and straightforward
5. **Version Control**: All your customizations are clearly visible in your package
6. **No Dependencies**: Your theme doesn't depend on external asset paths

### Benefits of Copying Shop Assets

- **Self-Contained**: Your theme package contains everything it needs
- **Easier Debugging**: All assets are in your package directory
- **Cleaner Builds**: Vite processes only your package assets
- **Better Performance**: No need to resolve external imports during build
- **Flexible Customization**: Start with shop foundation and customize anything
- **Complete Control**: Modify any part of the shop's styling and functionality

::: tip Ready to Go!
After copying the shop assets and views, you can immediately start the development server:

```bash
cd packages/Webkul/CustomTheme
npm run dev
```

Your store will now work perfectly with:
- All shop functionality intact
- Complete view structure for customization
- All assets ready for modification
- Custom home page showcasing your theme

You can customize any part of it directly in your package files.
:::

::: tip Customization Strategy
Now that you have the complete shop foundation, you can:
- **Override specific components** by creating your own Blade templates
- **Customize styles** by modifying the copied CSS files directly
- **Add new functionality** through your JavaScript files
- **Create new components** using Bagisto's design system as a base
:::

::: warning Asset Order Matters
Always load shop assets before your custom assets to ensure:
- Base functionality is available
- Your overrides take precedence
- Components work as expected
:::

## Development Workflow

Here's how to work efficiently with assets in your theme package:

### Asset Development Process

1. **Start Development Server**:
   ```bash
   cd packages/Webkul/CustomTheme
   npm run dev
   ```

2. **Make Changes**: Edit your CSS and JavaScript files in `src/Resources/assets/`

3. **Hot Reload**: Changes appear instantly in your browser (if using dev server)

4. **Build for Production**:
   ```bash
   npm run build
   ```

### Working with Different Asset Types

**CSS Development:**
- Use Tailwind classes in your Blade templates
- Add custom CSS in `src/Resources/assets/css/app.css`
- Tailwind will purge unused styles automatically

**JavaScript Development:**
- Add interactive features in `src/Resources/assets/js/app.js`
- Import additional modules as needed
- Use modern JavaScript features (ES6+)

**Images and Fonts:**
- Place files in `src/Resources/assets/images/` and `src/Resources/assets/fonts/`
- Vite will optimize and copy them to the build directory
- Reference them with relative paths in your CSS

::: tip Development Tips
- Keep the dev server running for instant feedback
- Use browser dev tools to debug compiled CSS
- Check the network tab to verify assets are loading correctly
:::

## Asset Loading and Development

Your assets are now properly configured and will be automatically loaded by Bagisto's shop layout when your theme is active.

::: tip Automatic Asset Loading (Shop Layout Only)
Bagisto automatically includes your theme's compiled CSS and JavaScript when you use the **shop layout** (`<x-shop::layouts>`). This is based on the configuration in `config/themes.php` and `config/bagisto-vite.php`. 

**Note**: This automatic loading only works with Bagisto's built-in shop layout. If you create custom layouts, you'll need to manually include assets using `@bagistoVite`.
:::

### Custom Asset Loading (Required for Custom Layouts)

If you create your own custom layouts instead of using `<x-shop::layouts>`, you **must** manually load your theme assets:

```blade
{{-- In your custom layout file --}}
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Custom Theme' }}</title>
    
    {{-- Manually load theme assets --}}
    @bagistoVite([
        'src/Resources/assets/css/app.css',
        'src/Resources/assets/js/app.js'
    ])
</head>
<body>
    {{ $slot }}
</body>
</html>
```

For specific pages or components that need additional assets:

```blade
@push('styles')
    @bagistoVite([
        'src/Resources/assets/css/app.css',
        'src/Resources/assets/js/app.js'
    ])
@endpush
```

::: warning When Manual Loading is Required
- **Custom Layouts**: Required when not using `<x-shop::layouts>`
- **Specific Pages**: For pages that need additional or different assets
- **Component Libraries**: When building reusable components with their own assets
- **Admin Themes**: Admin layouts don't automatically load shop theme assets

**Parameters**:
- The first argument is an array of asset paths relative to `src/Resources/assets/`
- Most themes using `<x-shop::layouts>` rely on automatic loading
:::

## Production Deployment

When deploying your theme package:

1. **Build Production Assets**:
   ```bash
   cd packages/Webkul/CustomTheme
   npm run build
   ```

2. **Commit Built Assets**: Include the `public/themes/custom-theme/build/` directory in your repository

3. **Server Setup**: Ensure your web server serves static files from the build directory

4. **Cache Busting**: Vite automatically handles asset versioning for cache invalidation

## What's Next?

Congratulations! You now have a professional theme package with modern asset compilation. Here are your next steps:

**📄 [Understanding Layouts →](./understanding-layouts.md)**  
Learn to create complex layouts and component systems for your theme.

**🧩 [Blade Components →](./blade-components.md)**  
Master the usage of Bagisto's pre-built components with your compiled assets.

**📄 [Package Development →](../package-development/getting-started)**  
Explore advanced package development techniques and best practices.

# Understanding Layouts

Learn how Bagisto's layout system works and how to effectively use layouts in your custom themes. This guide explains both admin and shop layouts, building upon the theme development concepts from our previous tutorials.

::: info What You'll Learn
- How Bagisto's layout components work
- Using admin layouts for backend interfaces
- Working with shop layouts for storefront themes
- Layout customization and best practices
- Connecting layouts with your custom theme package
:::

## Introduction

Layouts in Bagisto are fundamental building blocks that provide consistent structure across your application. They act as templates that wrap your content, ensuring unified design and user experience throughout your theme.

**Key Benefits:**
- **Consistency**: Unified design across all pages
- **Maintainability**: Changes in one place affect the entire application
- **Developer Experience**: Pre-built components with sensible defaults
- **Flexibility**: Customizable props and slots for different use cases

::: tip Connecting to Theme Development
If you're building a custom theme package (covered in our [Custom Theme Package](./creating-custom-theme-package.md) guide), understanding layouts is crucial for creating professional themes that integrate seamlessly with Bagisto's architecture.
:::

::: info Laravel Blade Foundation
Bagisto layouts are built on Laravel's Blade templating system. For comprehensive details about Blade components and layouts, visit the [Laravel documentation](https://laravel.com/docs/12.x/blade).
:::

## Admin Layout

The `<x-admin::layouts>` component provides the foundation for all admin panel pages, including navigation, header, sidebar, and content areas.

### Basic Admin Layout Usage

When building admin interfaces for your packages, use the admin layout to maintain consistency with Bagisto's admin panel design:

```blade
<x-admin::layouts>
    <!-- Page title (appears in browser tab and page header) -->
    <x-slot:title>
        @lang('blog::app.admin.index.page-title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-4 justify-between max-sm:flex-wrap">
        <p class="py-[11px] text-xl text-gray-800 dark:text-white font-bold">
            @lang('blog::app.admin.index.page-title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            {{-- Action buttons (Add, Export, etc.) --}}
            <button class="primary-button">
                @lang('blog::app.admin.index.create-btn')
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="mt-8">
        <!-- Your admin content goes here -->
    </div>
</x-admin::layouts>
```

### Admin Layout Features

The admin layout automatically provides:
- **Navigation**: Admin sidebar with menu items
- **Header**: Top navigation with user menu and notifications
- **Responsive Design**: Mobile-friendly layout that adapts to screen size
- **Dark Mode**: Built-in dark mode support
- **Breadcrumbs**: Automatic breadcrumb generation based on routes

::: tip Admin Layout Best Practices
- Always use the title slot for SEO and user experience
- Follow Bagisto's admin design patterns for consistency
- Use the provided CSS classes for styling (e.g., `primary-button`)
- Keep the layout structure clean and semantic
:::

### Example: Custom Package Admin Page

Here's how you might use the admin layout in your custom theme package:

```blade
{{-- File: packages/Webkul/CustomTheme/src/Resources/views/admin/index.blade.php --}}
<x-admin::layouts>
    <x-slot:title>
        Custom Theme Settings
    </x-slot:title>

    <div class="flex gap-4 justify-between max-sm:flex-wrap">
        <h1 class="py-[11px] text-xl text-gray-800 dark:text-white font-bold">
            Custom Theme Configuration
        </h1>

        <div class="flex gap-x-2.5 items-center">
            <button class="secondary-button">
                Reset to Defaults
            </button>
            
            <button class="primary-button">
                Save Settings
            </button>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="mt-8 bg-white dark:bg-gray-900 rounded-lg shadow p-6">
        <!-- Your theme settings form -->
    </div>
</x-admin::layouts>
```

## Shop Layout

The `<x-shop::layouts>` component is the foundation for all storefront pages, providing header, navigation, footer, and content structure that customers see.

### Basic Shop Layout Usage

When building storefront pages for your custom theme, the shop layout provides everything needed for a complete customer experience:

```blade
<x-shop::layouts>
    <!-- Page title (SEO and browser tab) -->
    <x-slot:title>
        @lang('blog::app.shop.blogs.page-title')
    </x-slot:title>

    {{-- Page Content --}}
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-[26px] font-medium">
                @lang('blog::app.shop.blogs.page-title')
            </h1>
        </div>

        {{-- Your page content --}}
        <div class="grid gap-6">
            <!-- Blog posts, products, or other content -->
        </div>
    </div>
</x-shop::layouts>
```

### Shop Layout Configuration

The shop layout accepts several props to customize which sections are included:

```blade
<x-shop::layouts
    :has-header="false"
    :has-feature="false"  
    :has-footer="false"
>
    <!-- Content when header/footer are disabled -->
</x-shop::layouts>
```

### Layout Props Reference

| Prop Name | Description | Default Value | Use Case |
|-----------|-------------|---------------|-----------|
| **`has-header`** | Include site header with navigation and search | `true` | Disable for popup pages or custom headers |
| **`has-feature`** | Show featured content section | `true` | Disable for minimal pages |
| **`has-footer`** | Include site footer with links and info | `true` | Disable for checkout or modal pages |

::: tip Shop Layout in Custom Themes
If you're building a custom theme package (from our [Custom Theme Package](./creating-custom-theme-package.md) tutorial), the shop layout automatically loads your theme's compiled assets when configured properly. This works seamlessly with the asset compilation setup from our [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide.
:::

### Example: Custom Theme Home Page

Here's how you might use the shop layout in your custom theme package:

```blade
{{-- File: packages/Webkul/CustomTheme/src/Resources/views/home/index.blade.php --}}
<x-shop::layouts>
    <x-slot:title>
        Custom Theme Home
    </x-slot:title>

    {{-- Hero Section --}}
    <div class="hero-section bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">
                Welcome to Our Store
            </h1>
            
            <p class="text-xl mb-8 opacity-90">
                Professional theme with modern design
            </p>
            
            <a href="{{ route('shop.search.index') }}" 
               class="bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                Start Shopping
            </a>
        </div>
    </div>

    {{-- Featured Products --}}
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">
            Featured Products
        </h2>
        
        <!-- Product grid -->
    </div>
</x-shop::layouts>
```

### Shop Layout Features

The shop layout automatically provides:
- **Header**: Logo, navigation menu, search, and cart
- **Mobile Navigation**: Responsive mobile menu
- **SEO**: Proper meta tags and structured data
- **Asset Loading**: Automatic inclusion of theme CSS/JS
- **Responsive Design**: Mobile-first responsive layout

::: warning Asset Loading Behavior
The shop layout automatically loads your active theme's compiled assets. This means:
- ✅ **Custom themes**: Your CSS/JS will be loaded automatically
- ✅ **Asset compilation**: Works with Vite dev server and production builds
- ⚠️ **Custom layouts**: If you create your own layout, you'll need to manually include `@bagistoVite` directive

For details on asset loading, see our [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide.
:::

### Creating Minimal Pages

For pages that need minimal structure (like popups or standalone pages), disable unnecessary sections:

```blade
<x-shop::layouts
    :has-header="false"
    :has-footer="false"
>
    <x-slot:title>
        Minimal Page
    </x-slot:title>

    {{-- Standalone content without header/footer --}}
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full">
            <!-- Popup or standalone content -->
        </div>
    </div>
</x-shop::layouts>
```

## Custom Layouts and Best Practices

### When to Create Custom Layouts

While Bagisto's built-in layouts cover most use cases, you might need custom layouts for:

- **Unique Design Requirements**: When your theme needs a completely different structure
- **Specialized Pages**: Landing pages, maintenance pages, or custom applications
- **Third-party Integrations**: External widgets or embedded applications
- **Performance Optimization**: Minimal layouts for specific high-performance pages

### Creating Custom Layouts

If you need complete control over the layout structure, you can create your own master layout:

```blade
{{-- File: packages/Webkul/CustomTheme/src/Resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    
    {{-- Load theme assets manually --}}
    @bagistoVite([
        'src/Resources/assets/css/app.css',
        'src/Resources/assets/js/app.js'
    ])
    
    {{-- Additional meta tags --}}
    @stack('meta')
    
    {{-- Additional styles --}}
    @stack('styles')
</head>
<body class="{{ $bodyClass ?? '' }}">
    {{-- Custom header --}}
    @if($hasHeader ?? true)
        @include('custom-theme::layouts.header')
    @endif
    
    {{-- Main content --}}
    <main class="main-content">
        {{ $slot }}
    </main>
    
    {{-- Custom footer --}}
    @if($hasFooter ?? true)
        @include('custom-theme::layouts.footer')
    @endif
    
    {{-- Additional scripts --}}
    @stack('scripts')
</body>
</html>
```

Then use your custom layout:

```blade
<x-custom-theme::layouts.master 
    :title="'Custom Page'"
    :has-header="true"
    :has-footer="false"
    body-class="custom-page"
>
    <!-- Your page content -->
</x-custom-theme::layouts.master>
```

### Layout Development Tips

**1. Asset Management**
- Use `@bagistoVite` for loading compiled assets in custom layouts
- Leverage `@stack` directives for flexible script/style injection
- Consider performance implications of loading assets

**2. Responsive Design**
- Test layouts across different screen sizes
- Use Bagisto's responsive utilities and breakpoints
- Ensure mobile navigation works properly

**3. SEO Considerations**
- Always include proper `<title>` tags
- Add meta descriptions and Open Graph tags
- Implement structured data where appropriate

**4. Internationalization**
- Use Laravel's `@lang` directive for translatable content
- Support RTL languages if needed
- Consider cultural design differences

::: tip Layout Performance
- **Shop Layout**: Automatically optimized for SEO and performance
- **Admin Layout**: Includes admin-specific optimizations and features
- **Custom Layouts**: Require manual optimization but offer complete control
:::

## Integration with Theme Development

### Connecting with Previous Tutorials

This layout system integrates seamlessly with our previous guides:

**🎨 [Custom Theme Package](./creating-custom-theme-package.md)**
- Layouts work automatically with your theme package structure
- Service provider handles layout registration and publishing

**⚡ [Vite-Powered Theme Assets](./vite-powered-theme-assets.md)**  
- Shop layout automatically loads your compiled theme assets
- Custom layouts require manual asset inclusion with `@bagistoVite`

### Next Steps in Theme Development

Now that you understand layouts, you can:

1. **Create Complex Views**: Build sophisticated pages using layout slots and props
2. **Customize Components**: Override specific layout components for your theme
3. **Optimize Performance**: Choose the right layout for each page's requirements
4. **Build Reusable Patterns**: Create your own layout components for consistency

::: warning Translation Requirements
Notice that the examples use translation strings (e.g., `@lang('blog::app.admin.index.page-title')`). You'll need to:

1. **Create language files** in your package: `src/Resources/lang/en/app.php`
2. **Register translations** in your service provider
3. **Publish language files** for customization

See our package development guides for details on handling translations.
:::

## What's Next?

Understanding layouts is crucial for effective theme development. Here are your next steps:

**🧩 [Blade Components →](./blade-components.md)**  
Learn to use Bagisto's pre-built components that work seamlessly with your layouts.

**🛠️ [Package Development →](../package-development/getting-started)**  
Explore advanced package development techniques for creating custom functionality.

**📚 [Back to Getting Started →](./getting-started.md)**  
Review the complete theme development journey and explore other paths.

# Blade Components

Comprehensive guide to Bagisto's pre-built Blade components for both shop and admin interfaces. These components provide consistent UI elements with Vue.js integration for enhanced performance and user experience.

::: info What You'll Learn
- Using shop components for customer-facing interfaces
- Implementing admin components for backend functionality
- Component customization and styling options
- Integration with your custom theme packages
- Best practices for component usage
:::

## Introduction

Bagisto provides an extensive collection of reusable Blade components for both **Shop** and **Admin** packages. These components integrate Vue.js functionality within Blade templates to deliver optimal performance and user experience.

**Key Benefits:**
- **Consistent UI**: Unified design across all interfaces
- **Performance**: Vue.js integration for reactive functionality
- **Customization**: Flexible props and slots for customization
- **Accessibility**: Built-in accessibility features
- **Responsive**: Mobile-first responsive design

::: tip Integration with Theme Development
These components work seamlessly with the custom themes covered in our previous guides:
- [Custom Theme Package](./creating-custom-theme-package.md) - Package structure and development
- [Understanding Layouts](./understanding-layouts.md) - Layout integration
- [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) - Asset management with components
:::

::: info Laravel Blade Foundation
For comprehensive details about Blade components and templating, visit the [Laravel Blade documentation](https://laravel.com/docs/12.x/blade#introduction).
:::

## Shop Components

Shop components are reusable Blade components used to build the shop. They manage product listings, shopping carts, checkout processes, and user interactions, providing a seamless shopping experience for customers.

### Shop Accordion

Bagisto provides a collapsible accordion UI element, allowing users to toggle the visibility of content sections. It is commonly used for organizing and presenting information in a compact and intuitive manner.

| Props           | Type    | Default | Description                                                 |
| --------------- | ------- | ------- | ------------------------------------------------------------|
| **`is-active`** | Boolean | `false` | Determines the initial state of the accordion. When set to `true`, the accordion section will be expanded by default; otherwise, it will be collapsed. |

| Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`header`**  | Used to customize the header section of the accordion. |
  | **`content`** | Used to customize the content section of the accordion. |

You can customize the appearance of the accordion `header` and `content` by passing additional CSS classes to the header and content slots, respectively.

Let's assume you want to use the **`accordion`** component, you can call it like this:

```html
<!-- Shop Accordion -->
<x-shop::accordion 
    title="Shop Accordion" 
    class="last:border-b-0"
>
    <x-slot:header class="!py-2.5">
        Accordion Header
    </x-slot>

    <x-slot:content class="!p-0">
        Accordion Content
    </x-slot>
</x-shop::accordion>
```

### Shop Breadcrumbs

The breadcrumbs component generates `breadcrumb` navigation for the application.

| Props       | Type      | Description                                                                 |
| ---------- | --------- | --------------------------------------------------------------------------- |
| **`name`** | `String`  | Specifies the name of the current page or resource.                          |
| **`entity`** | `Mixed`  | Optional. Represents the entity associated with the current page. It could be an object, ID, or other data used to retrieve additional information for breadcrumb customization. |

Let's assume you want to use the **`breadcrumbs`** component. You can call it like this:

```html
<!-- Shop Accordion -->
<x-shop::breadcrumbs
    name="addresses.edit"
    :entity="$address"
/>
```

By using the breadcrumbs component with these props, you can effectively enhance navigation within your shop application, providing users with clear paths to navigate through different sections or resources.

### Shop Button

The `button` component in Bagisto provides a versatile button element that supports loading state with a spinner animation. It offers flexibility in styling and functionality to suit various use cases within your application.

  | Prop            | Type          | Default Value | Description                                                            |
| ---------------   | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`title`**       | `String`      | None          | Title text displayed on the button.                                     |
| **`loading`**     | `Boolean`     | `false`       | Indicates whether the button is in a loading state.                      |
| **`button-type`**  | `String`      | `'button'`    | Specifies the type of button (`'button'`, `'submit'`, `'reset'`, etc.). |
| **`button-class`** | `String`      | `''`          | Additional classes for custom styling.                                  |

You can customize the appearance of the button by passing additional props `loading`  `buttonType`  `buttonClass` respectively.

Let's assume you want to use the **`button`** component. You can call it like this:
 
```html
<!-- Shop Button -->
<x-shop::button
    type="submit"
    class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
    button-type="secondary-button"
    :loading="false"
    :title="trans('Button')"
    :disabled="true"
    ::loading="true"
/>
```

### Shop Data Grid

The `datagrid` component in Bagisto applications provides a flexible and customizable data grid interface for displaying tabular data. It includes features such as `sorting`, `filtering`, `pagination`, and `mass actions` to manage data efficiently.

You can customize the appearance of the `DataGrid` by referring to the [DataGrid Customization](/package-development/datagrid).

Let's assume you want to use the **`datagrid`** component. You can call it like this.

```html
<!-- Shop Datagrid -->
<x-shop::datagrid :src="route('shop.customers.account.orders.index')" />
```

### Shop Drawer

The `drawer` component in Bagisto provides a versatile drawer that can be positioned on the top, bottom, left, or right side of the screen. It allows you to create interactive drawers that can contain various content such as headers, body, and footer sections. The drawer can be toggled open or closed, providing a clean and efficient way to display additional information or functionality.

| Props            | Type          | Default Value | Description                                                            |
| --------------  | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`is-active`** | `Boolean`     | `false`       | Determines whether the drawer is initially active.                      |
| **`position`** | `String`      | `'left'`      | Specifies the position of the drawer (`top`, `bottom`, `left`, or `right`). |
| **`width`**    | `String`      | `'500px'`     | Specifies the width of the drawer.                                      |

| Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`toggle`** | Slot for the toggle button or element. |
  | **`header`** | Slot for the header content. |
  | **`content`** | Slot for the main content. |
  | **`footer`** |  Slot for the footer content. |

You can customize the appearance of the Drawer by passing additional CSS.

* To customize the header section, you can target the `header` slot with your own CSS classes or styles.
* Similarly, you can customize the content section using the `content` slot.
* Similarly, you can customize the content section using the `footer` slot.

Let's assume you want to use the **`drawer`** component. You can call it like this.

```html
<!-- Shop Drawer -->
<x-shop::drawer
    position="left"
    width="100%"
>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot>

    <x-slot:header class="bg-red-100"> <!-- Pass your custom css to customize header -->
        Drawer Header
    </x-slot>

    <x-slot:content class="!p-5">
        Drawer Content
    </x-slot>
</x-shop::drawer>
```

### Shop Dropdown

The `dropdown` component in Bagisto provides a customizable dropdown menu that can be positioned at different locations relative to its toggle button. It enables you to create dropdown menus with various content sections such as toggle button, content, and menu items.

| Prop              | Type      | Default Value | Description                                                            |
| ----------------- | --------- | ------------- | ---------------------------------------------------------------------- |
| **`close-on-click`**| `Boolean` | `true`        | Determines whether the dropdown should close when clicking outside the menu. |
| **`position`**    | `String`  | `'bottom-left'`| Specifies the position of the dropdown menu relative to the toggle button (`top-left`, `top-right`, `bottom-left`, `bottom-right`). |

  | Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`toggle`** | Slot for the toggle button or element.. |
  | **`content`** | Slot for the main content. |
  | **`menu`** | Slot for the menu items.. |

To customize the content section, you can target the `content` slot with your own CSS classes or styles.

Let's assume you want to use the **`dropdown`** component. You can call it like this.

```html
<!-- Shop Dropdown -->
<x-shop::dropdown position="bottom-left">
    <x-slot:toggle>
        Dropdown Toggle
    </x-slot>

    <x-slot:content class="!m-0">
        Dropdown Content
    </x-slot>

    <x-slot:menu>
        <x-shop::dropdown.menu.item>
            Menu Item 1
            Menu Item 2
        </x-shop::dropdown.menu.item>
    </x-slot>
</x-shop::dropdown>
```

### Shop Flat-Picker

The `datetime-picker` and `date-picker` components provide `date` and `time` picker functionality within Bagisto applications. These components are based on the Flatpickr library and offer customizable options for selecting dates and times.

It can be configured with various props to customize its behavior according to application requirements.

| Prop          | Type             | Default Value | Description                                                             |
| ------------- | ---------------- | ------------- | ----------------------------------------------------------------------- |
| **`name`**    | `String`         | None          | Name attribute for the input field.                                      |
| **`value`**   | `String`         | None          | Initial value of the date picker.                                        |
| **`allow-input`** | `Boolean`      | `true`      | Determines whether manual input is allowed in the input field.           |
| **`disable`** | `Array`          | `[]`          | Array of dates to disable in the date picker.                            |
                                                                                                                              
Let's assume you want to use the **`flat-picker`** component. You can call it like this.

```html
<!-- Shop Date picker -->
<x-shop::flat-picker.date ::allow-input="false">
    <input
        type="date"
        name="date"
        class="mb-4"
        :allowInput="true"
        :disable="disabledDates"
        placeholder="date"
    />
</x-shop::flat-picker.date>
```

### Shop Media (Image)

The Media component in Bagisto provides a user interface for managing and displaying images/videos, allowing users to upload, edit, and delete images.:

| Prop Name         | Type       | Default Value | Description                                                      |
|-------------------|-------------|---------------|------------------------------------------------------------------|
| `name`            | `String`    |               | The name of the input field.                                      |
| `allow-multiple` | `Boolean`   | `false`       | Whether to allow uploading multiple images.                       |
| `show-placeholders` | `Boolean` | `true`        | Whether to show placeholder images when no images are uploaded.   |
| `uploaded-images` | `Array`     | `[]`          | Array of uploaded images.                                         |
| `uploaded-videos` | `Array`     | `[]`          | Array of uploaded videos.                                         |
| `width`         | `String`    | `'100%'`      | Width of the image container.                                     |
| `height`        | `String`    | `'auto'`      | Height of the image container.                                    |

Let's assume you want to use the **`Image/Video`** component, You can call it like this.

```html
<!-- Image Component -->
<x-shop::media.images.lazy
    class="h-[110px] max-w-[110px] rounded-xl max-md:h-20 max-md:max-w-20"
    ::src="item.base_image.small_image_url"
    ::alt="item.name"
    width="110"
    height="110"
    ::key="item.id"
    ::index="item.id"
/>
```

### Shop Modal

The `modal` component in Bagisto provides a flexible way to create modal dialogs. It allows you to display content in a layer that floats above the rest of the page.

| Props         | Type      | Default Value | Description                           |
|--------------|-----------|---------------|---------------------------------------|
| `is-active`  | Boolean   | `false`       | Controls the visibility of the modal.  |

| Slot          | Description                                                 |
|---------------|-------------------------------------------------------------|
| **`toggle`**  | Used for the element that toggles the visibility of the modal. |
| **`header`**  | Allows customization of the modal header content.           |
| **`content`** | Provides a slot for the main body content of the modal.      |
| **`footer`**  | Allows customization of the footer content within the modal. |

You can customize the appearance of the Modal by passing additional CSS.

* To customize the header section, you can target the `header` slot with your own CSS classes or styles.
* Similarly, you can customize the content section using the `content` slot.
* Similarly, you can customize the content section using the `footer` slot.

Let's assume you want to use the **`modal`** component, You can call it like this.

```html
<!-- Shop Modal-->
<x-shop::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot>

    <x-slot:header>
        Modal Header
    </x-slot>

    <x-slot:content>
        Modal Content
    </x-slot>
</x-shop::modal>
```

### Shop Quantity Changer

The Quantity Changer component, provides a simple interface for users to increase or decrease a quantity value. 

| Props          | Type    | Default Value | Description                       |
| -------------- | ------- | ------------- | --------------------------------- |
| **`name`**     | String  | `''`          | The name attribute for the hidden input field. |
| **`value`**    | Number  | `1`           | The initial quantity value.       |

Let's assume you want to use the **`Quantity Changer`** component on shop. You can call it like this.

```html
<!-- Shop Quantity changer -->
<x-shop::quantity-changer
    name="quantity"
    value="1"
    class="gap-x-4 rounded-xl px-7 py-4"
/>
```

### Shop Shimmer

Prebuilt `shimmer` effects are available in Bagisto. You can easily use them.

Let's assume you want to use the **`shimmer`** You can call it like this.

```html
<!-- Shop shimmer -->
<x-shop::shimmer.datagrid />
```

### Shop Table

The Table component provides a structured way to display tabular data in Bagisto. You can customize the appearance of the table elements using CSS. Below are some common customization options:

| Styling        | Description                                                                                               |
| -------------- | --------------------------------------------------------------------------------------------------------- |
| **`Table`**    | Apply custom styles to the `table` element to change its appearance, such as borders, padding, and background color. |
| **`Cell`**     | Customize the appearance of `th` and `td` elements using CSS, such as font size, text alignment, and background color. |
| **`Row`**      | Apply styles to `tr` elements to change their appearance, such as background color, hover effects, and borders. |
| **`Header`**   | Customize the appearance of the header cells within the `thead` section using `th` elements. Apply styles such as font weight, text color, and background color. |

Let's assume you want to use the **`Table`** component on shop. You can call it like this.

```html
<!-- Shop Table -->
<x-shop::table>
    <x-shop::table.thead>
        <x-shop::table.thead.tr>
            <x-shop::table.th>
                Heading 1
            </x-shop::table.th>

            <x-shop::table.th>
                Heading 2
            </x-shop::table.th>

            <x-shop::table.th>
                Heading 3
            </x-shop::table.th>

            <x-shop::table.th>
                Heading 4
            </x-shop::table.th>
        </x-shop::table.thead.tr>
    </x-shop::table.thead>

    <x-shop::table.tbody>
        <x-shop::table.tbody.tr>
            <x-shop::table.td>
                Column 1
            </x-shop::table.td>

            <x-shop::table.td>
                Column 2
            </x-shop::table.td>

            <x-shop::table.td>
                Column 3
            </x-shop::table.td>

            <x-shop::table.td>
                Column 4
            </x-shop::table.td>
        </x-shop::table.thead.tr>
    </x-shop::table.tbody>
</x-shop::table>
```

### Shop Tabs 

The Tabs component allows users to navigate between different content sections using tabs. It consists of two main parts: the `tabs` component for managing the tabs and the `tab-item` component for defining individual tab items.

| Prop          | Type             | Default Value | Description                                                             |
| ------------- | ---------------- | ------------- | ----------------------------------------------------------------------- |
| **`position`**| `String`         | `'left'`      | Specifies the position of the tabs (`left`, `right`, `center`).         |

#### Tab Item Component Props

The `tab-item` component represents an individual tab within the `tabs` component:

| Prop             | Type          | Default Value | Description                                                            |
| ---------------- | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`title`**      | `String`      | None          | Title of the tab.                                                       |
| **`is-selected`**| `Boolean`     | `false`       | Indicates whether the tab is selected (`true`) or not (`false`). Default is `false`. |

You can customize the tabs and their content as per your requirements. 

Let's assume you want to use the **`tabs`** component on shop. You can call it like this.

```html
<!-- Shop Tab -->
<x-shop::tabs position="center">
    <x-shop::tabs.item
        class="container"
        :title="Tab-1"
        :is-selected="true"
    >
        <div class="container mt-[60px] max-1180:px-5">
            <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            </p>
        </div>
    </x-shop::tabs.item>

    <x-shop::tabs.item
        class="container"
        :title="Tab-2"
    >
        <div class="container mt-[60px] max-1180:px-5">
            <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            </p>
        </div>
    </x-shop::tabs.item>
</x-shop::tabs>
```

### Shop Tinymce

The `tinymce` component wraps the Tinymce editor and provides additional functionalities like AI content generation.

| Props          | Type    | Default Value | Description                                                      |
| -------------- | ------- | ------------- | ---------------------------------------------------------------- |
| **`selector`** | String  | `''`          | The CSS selector for the textarea element to initialize as TinyMCE. |
| **`field`**    | Object  | `{}`          | Vue Formulate field object.                                      |
| **`prompt`**   | String  | `''`          | The prompt to be used for AI content generation.                 |  

Let's assume you want to use the **`tinymce`** component on admin and shop. You can call it like this.

```html
<!-- Shop Tinymce -->
<x-shop::form.control-group.control
    type="textarea"
    id="content"
    name="content"
    rules="required"
    :value="old('content')"
    :label="content"
    :placeholder="Content"
    :tinymce="true"
/>
```

## Admin Components

Admin components are reusable Blade components used to build the Admin.

### Admin Accordion

Bagisto provides a collapsible accordion UI element, allowing users to toggle the visibility of content sections. It is commonly used for organizing and presenting information in a compact and intuitive manner.

| Props           | Type    | Default | Description                                                 |
| --------------- | ------- | ------- | ------------------------------------------------------------|
| **`is-active`** | Boolean | `false` | Determines the initial state of the accordion. When set to `true`, the accordion section will be expanded by default; otherwise, it will be collapsed. |

| Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`header`**  | Used to customize the header section of the accordion. |
  | **`content`** | Used to customize the content section of the accordion. |

You can customize the appearance of the accordion `header` and `content` by passing additional CSS classes to the header and content slots, respectively.

Let's assume you want to use the **`accordion`** component, you can call it like this:

```html
<!-- Admin Accordion -->
<x-admin::accordion 
    title="Admin Accordion" 
    class="px-5"
>
    <x-slot:header class="bg-gray-200">
        Accordion Header
    </x-slot>

    <x-slot:content class="bg-green-200">
        Accordion Content
    </x-slot>
</x-admin::accordion>
```

### Admin Button

The `button` component in Bagisto provides a versatile button element that supports loading state with a spinner animation. It offers flexibility in styling and functionality to suit various use cases within your application.

  | Prop            | Type          | Default Value | Description                                                            |
| ---------------   | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`title`**       | `String`      | None          | Title text displayed on the button.                                     |
| **`loading`**     | `Boolean`     | `false`       | Indicates whether the button is in a loading state.                      |
| **`button-type`**  | `String`      | `'button'`    | Specifies the type of button (`'button'`, `'submit'`, `'reset'`, etc.). |
| **`button-class`** | `String`      | `''`          | Additional classes for custom styling.                                  |

You can customize the appearance of the button by passing additional props `loading`  `buttonType`  `buttonClass` respectively.

Let's assume you want to use the **`button`** component. You can call it like this:
 
```html
<!-- Admin Button -->
<x-admin::button
    type="submit"
    class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
    button-type="secondary-button"
    :loading="false"
    :title="trans('Button')"
    :disabled="true"
    ::loading="true"
/>
```

### Admin Charts

The `charts-bar` and `charts-line` components in Bagisto provide easy-to-use chart components for displaying bar and line charts respectively. These components are based on the Chart.js library and offer customization options for labels, datasets, and aspect ratio to create visually appealing charts in your Bagisto application.

| Prop                | Type          | Default Value | Description                                                            |
| ------------------- | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`labels`**         | `Array` (required) | None          | An array of labels for the x-axis of the chart.                        |
| **`datasets`**       | `Array` (required) | None          | An array of datasets containing data points for the chart.             |
| **`aspectRatio`**    | `Number`      | `3.23`        | Optional. Aspect ratio of the chart (width / height).                   |

You can customize the appearance of the bar chart by providing different datasets with colors, labels, and data points. Additionally, you can adjust the aspect ratio of the chart by setting the aspect-ratio prop.

Let's assume you want to use the **`charts`** component. You can call it like this.

```html
<!--
    Chart | Line Chart Component

    Note: To use charts, you need to require the Chart.js library.
-->
<x-admin::charts.line
    ::labels="chartLabels"
    ::datasets="chartDatasets"
/>

<!--
    Chart | Bar Chart Component

    Note: To use charts, you need to require the Chart.js library.
-->
<x-admin::charts.bar
    ::labels="chartLabels"
    ::datasets="chartDatasets"
    ::aspect-ratio="1.41"
/>
```

### Admin Data Grid

The `datagrid` component in Bagisto applications provides a flexible and customizable data grid interface for displaying tabular data. It includes features such as `sorting`, `filtering`, `pagination`, and `mass actions` to manage data efficiently.

You can customize the appearance of the `DataGrid` by referring to the [DataGrid Customization](/package-development/datagrid).

Let's assume you want to use the **`datagrid`** component. You can call it like this.

```html
<!-- Admin Datagrid -->
<x-admin::datagrid :src="route('admin.catalog.products.index')" />
```

### Admin Drawer

The `drawer` component in Bagisto provides a versatile drawer that can be positioned on the top, bottom, left, or right side of the screen. It allows you to create interactive drawers that can contain various content such as headers, body, and footer sections. The drawer can be toggled open or closed, providing a clean and efficient way to display additional information or functionality.

| Props            | Type          | Default Value | Description                                                            |
| --------------  | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`is-active`** | `Boolean`     | `false`       | Determines whether the drawer is initially active.                      |
| **`position`** | `String`      | `'left'`      | Specifies the position of the drawer (`top`, `bottom`, `left`, or `right`). |
| **`width`**    | `String`      | `'500px'`     | Specifies the width of the drawer.                                      |

| Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`toggle`** | Slot for the toggle button or element. |
  | **`header`** | Slot for the header content. |
  | **`content`** | Slot for the main content. |
  | **`footer`** |  Slot for the footer content. |

You can customize the appearance of the Drawer by passing additional CSS.

* To customize the header section, you can target the `header` slot with your own CSS classes or styles.
* Similarly, you can customize the content section using the `content` slot.
* Similarly, you can customize the content section using the `footer` slot.

Let's assume you want to use the **`drawer`** component. You can call it like this.

```html
<!-- Admin Drawer -->
<x-admin::drawer     
    position="left"
    width="100%"
>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot>

    <x-slot:header class="bg-red-100">  <!-- Pass your custom css to customize header -->
        Drawer Header
    </x-slot>

    <x-slot:content class="!p-5"> <!-- Pass your custom css to customize header -->
        Drawer Content
    </x-slot>
</x-admin::drawer>
```

### Admin Dropdown

The `dropdown` component in Bagisto provides a customizable dropdown menu that can be positioned at different locations relative to its toggle button. It enables you to create dropdown menus with various content sections such as toggle button, content, and menu items.

| Prop              | Type      | Default Value | Description                                                            |
| ----------------- | --------- | ------------- | ---------------------------------------------------------------------- |
| **`close-on-click`**| `Boolean` | `true`        | Determines whether the dropdown should close when clicking outside the menu. |
| **`position`**    | `String`  | `'bottom-left'`| Specifies the position of the dropdown menu relative to the toggle button (`top-left`, `top-right`, `bottom-left`, `bottom-right`). |

  | Slots           | Description                                                      |
  | ------------- | ------------------------------------------------------------------------ |
  | **`toggle`** | Slot for the toggle button or element.. |
  | **`content`** | Slot for the main content. |
  | **`menu`** | Slot for the menu items.. |

To customize the content section, you can target the `content` slot with your own CSS classes or styles.

Let's assume you want to use the **`dropdown`** component. You can call it like this.

```html
<!-- Admin Dropdown -->
<x-admin::dropdown position="bottom-left"> 
    <x-slot:toggle>
        Dropdown Toggle
    </x-slot>

    <x-slot:content class="!p-0">
        Dropdown Content
    </x-slot>

    <x-slot:menu>
        <x-admin::dropdown.menu.item
            Menu Item 1
            Menu Item 2
        >
        </x-admin::dropdown.menu.item>
    </x-slot>
</x-admin::dropdown>
```

### Admin Flat-Picker

The `datetime-picker` and `date-picker` components provide `date` and `time` picker functionality within Bagisto applications. These components are based on the Flatpickr library and offer customizable options for selecting dates and times.

It can be configured with various props to customize its behavior according to application requirements.

| Prop          | Type             | Default Value | Description                                                             |
| ------------- | ---------------- | ------------- | ----------------------------------------------------------------------- |
| **`name`**    | `String`         | None          | Name attribute for the input field.                                      |
| **`value`**   | `String`         | None          | Initial value of the date picker.                                        |
| **`allow-input`** | `Boolean`      | `true`      | Determines whether manual input is allowed in the input field.           |
| **`disable`** | `Array`          | `[]`          | Array of dates to disable in the date picker.                            |
                                                                                                                              
Let's assume you want to use the **`flat-picker`** component. You can call it like this.

```html
<!-- Admin DateTime Picker -->
<x-admin::flat-picker.date ::allow-input="false">
    <input
        type="datetime"
        name="datetime"
        class="mb-4"
        :value="selectedDateTime"
        :disable="disabledDates"
        placeholder="datetime"
    />
</x-admin::flat-picker.date>
```

### Admin Media (Image/Video)
 
The Media component in Bagisto provides a user interface for managing and displaying images/videos, allowing users to upload, edit, and delete images.:

| Props               | Type        | Default Value | Description                                                      |
|---------------------|-------------|---------------|------------------------------------------------------------------|
| **`name`**          | `String`    |               | The name of the input field.                                      |
| **`allow-multiple`** | `Boolean`   | `false`       | Whether to allow uploading multiple images.                       |
| **`show-placeholders` | `Boolean` | `true`        | Whether to show placeholder images when no images are uploaded.   |
| **`uploaded-images` | `Array`     | `[]`          | Array of uploaded images.                                         |
| **`uploaded-videos` | `Array`     | `[]`          | Array of uploaded videos.                                         |
| **`width`**         | `String`    | `'100%'`      | Width of the image container.                                     |
| **`height`**        | `String`    | `'auto'`      | Height of the image container.                                    |

Let's assume you want to use the **`Image/Video`** component, You can call it like this.

```html
<!-- Image Component -->
<x-admin::media.images
    name="images"
    allow-multiple="true"
    show-placeholders="true"
    :uploaded-images="$product->images"
/>

<!-- Video Component -->
<x-admin::media.videos
    name="videos[files]"
    :allow-multiple="true"
    :uploaded-videos="$product->videos"
/>
```

### Admin Modal

The `modal` component in Bagisto provides a flexible way to create modal dialogs. It allows you to display content in a layer that floats above the rest of the page.

| Props         | Type      | Default Value | Description                           |
|--------------|-----------|---------------|---------------------------------------|
| `is-active`  | Boolean   | `false`       | Controls the visibility of the modal.  |

| Slot          | Description                                                 |
|---------------|-------------------------------------------------------------|
| **`toggle`**  | Used for the element that toggles the visibility of the modal. |
| **`header`**  | Allows customization of the modal header content.           |
| **`content`** | Provides a slot for the main body content of the modal.      |
| **`footer`**  | Allows customization of the footer content within the modal. |

You can customize the appearance of the Modal by passing additional CSS.

* To customize the header section, you can target the `header` slot with your own CSS classes or styles.
* Similarly, you can customize the content section using the `content` slot.
* Similarly, you can customize the content section using the `footer` slot.

Let's assume you want to use the **`modal`** component, You can call it like this.

```html
<!-- Admin Modal -->
<x-admin::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot>

    <x-slot:header>
        Modal Header
    </x-slot>

    <x-slot:content>
        Modal Content
    </x-slot>
</x-admin::modal>
```

### Admin Quantity Changer

The Quantity Changer component, provides a simple interface for users to increase or decrease a quantity value. 

| Props          | Type    | Default Value | Description                       |
| -------------- | ------- | ------------- | --------------------------------- |
| **`name`**     | String  | `''`          | The name attribute for the hidden input field. |
| **`value`**    | Number  | `1`           | The initial quantity value.       |

Let's assume you want to use the **`Quantity Changer`** component on shop. You can call it like this.

```html
<!-- Admin Quantity changer -->
<x-admin::quantity-changer
    name="quantity"
    value="1"
    class="w-max gap-x-4 rounded-l px-4 py-1"
/>
```

### Admin SEO

The `seo` component, assists in managing SEO-related metadata for your pages. It dynamically updates the meta title and description based on user input and provides a preview of the generated SEO metadata.

| Props          | Type    | Default Value | Description                 |
| -------------- | ------- | ------------- | --------------------------- |
| **`slug`**     | String  | `''`          | URL slug for the page.      |

Let's assume you want to use the **`seo`** component. You can call it like this, It offers a convenient way to generate and display SEO-friendly content for web pages.

```html 
<x-admin::seo slug="page" />
```

### Admin Shimmer

Prebuilt `shimmer` effects are available in Bagisto. You can easily use them.

Let's assume you want to use the **`shimmer`** You can call it like this.

```html
<!-- Admin shimmer -->
<x-admin::shimmer.datagrid />
```

### Admin Table

The Table component provides a structured way to display tabular data in Bagisto. You can customize the appearance of the table elements using CSS. Below are some common customization options:

| Styling        | Description                                                                                               |
| -------------- | --------------------------------------------------------------------------------------------------------- |
| **`Table`**    | Apply custom styles to the `table` element to change its appearance, such as borders, padding, and background color. |
| **`Cell`**     | Customize the appearance of `th` and `td` elements using CSS, such as font size, text alignment, and background color. |
| **`Row`**      | Apply styles to `tr` elements to change their appearance, such as background color, hover effects, and borders. |
| **`Header`**   | Customize the appearance of the header cells within the `thead` section using `th` elements. Apply styles such as font weight, text color, and background color. |

Let's assume you want to use the **`Table`** component on shop. You can call it like this.

```html
<!-- Admin Table -->
<x-admin::table>
    <x-admin::table.thead>
        <x-admin::table.thead.tr>
            <x-admin::table.th>
                Heading 1
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 2
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 3
            </x-admin::table.th>

            <x-admin::table.th>
                Heading 4
            </x-admin::table.th>
        </x-admin::table.thead.tr>
    </x-admin::table.thead>

    <x-admin::table.tbody>
        <x-admin::table.tbody.tr>
            <x-admin::table.td>
                Column 1
            </x-admin::table.td>

            <x-admin::table.td>
                Column 2
            </x-admin::table.td>

            <x-admin::table.td>
                Column 3
            </x-admin::table.td>

            <x-admin::table.td>
                Column 4
            </x-admin::table.td>
        </x-admin::table.thead.tr>
    </x-admin::table.tbody>
</x-admin::table>
```

### Admin Tabs 

The Tabs component allows users to navigate between different content sections using tabs. It consists of two main parts: the `tabs` component for managing the tabs and the `tab-item` component for defining individual tab items.

| Prop          | Type             | Default Value | Description                                                             |
| ------------- | ---------------- | ------------- | ----------------------------------------------------------------------- |
| **`position`**| `String`         | `'left'`      | Specifies the position of the tabs (`left`, `right`, `center`).         |

#### Tab Item Component Props

The `tab-item` component represents an individual tab within the `tabs` component:

| Prop             | Type          | Default Value | Description                                                            |
| ---------------- | ------------- | ------------- | ---------------------------------------------------------------------- |
| **`title`**      | `String`      | None          | Title of the tab.                                                       |
| **`is-selected`**| `Boolean`     | `false`       | Indicates whether the tab is selected (`true`) or not (`false`). Default is `false`. |

You can customize the tabs and their content as per your requirements. 

Let's assume you want to use the **`tabs`** component on shop. You can call it like this.

```html
<!-- Shop Tab -->
<x-admin::tabs position="center">
    <x-shop::tabs.item
        class="container"
        :title="Tab-1"
        :is-selected="true"
    >
        <div class="container mt-[60px] max-1180:px-5">
            <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            </p>
        </div>
    </x-shop::tabs.item>

    <x-admin::tabs.item
        class="container"
        :title="Tab-2"
    >
        <div class="container mt-[60px] max-1180:px-5">
            <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            </p>
        </div>
    </x-admin::tabs.item>
</x-admin::tabs>
```

### Admin Tinymce

The `tinymce` component wraps the Tinymce editor and provides additional functionalities like AI content generation.

| Props          | Type    | Default Value | Description                                                      |
| -------------- | ------- | ------------- | ---------------------------------------------------------------- |
| **`selector`** | String  | `''`          | The CSS selector for the textarea element to initialize as TinyMCE. |
| **`field`**    | Object  | `{}`          | Vue Formulate field object.                                      |
| **`prompt`**   | String  | `''`          | The prompt to be used for AI content generation.                 |  

Let's assume you want to use the **`tinymce`** component on admin and shop. You can call it like this.

```html
<!-- Admin Tinymce -->
<x-admin::form.control-group.control
    type="textarea"
    id="content"
    name="content"
    rules="required"
    :value="old('content')"
    :label="Content"
    :placeholder="Content"
    :tinymce="true"
    :prompt="core()->getConfigData('general.magic_ai.content_generation.category_description_prompt')"
/>
```

## Component Integration Best Practices

### Using Components in Custom Themes

When building custom themes with the components covered in our previous guides, these Blade components integrate seamlessly:

**Theme Package Integration:**
```blade
{{-- In your custom theme views --}}
<x-shop::layouts>
    <x-slot:title>Custom Product Page</x-slot>
    
    {{-- Use components within your theme --}}
    <x-shop::accordion>
        <x-slot:header>Product Details</x-slot:header>
        <x-slot:content>
            <!-- Product information -->
        </x-slot:content>
    </x-shop::accordion>
    
    <x-shop::quantity-changer
        name="quantity"
        value="1"
    />
</x-shop::layouts>
```

**Asset Compilation:**
Components automatically work with your theme's compiled assets from the [Vite-Powered Theme Assets](./vite-powered-theme-assets.md) guide.

### Customization Strategies

**1. CSS Customization:**
```blade
<x-shop::button
    class="custom-primary-button hover:bg-blue-600"
    :title="'Custom Styled Button'"
/>
```

**2. Slot Customization:**
```blade
<x-shop::modal>
    <x-slot:header class="bg-gradient-to-r from-purple-500 to-pink-500 text-white">
        Custom Header with Gradient
    </x-slot:header>
</x-shop::modal>
```

**3. Component Extension:**
Create your own components that extend Bagisto's components:
```blade
{{-- resources/views/components/custom-product-card.blade.php --}}
<x-shop::accordion>
    <x-slot:header>
        {{ $product->name }}
    </x-slot:header>
    <x-slot:content>
        <!-- Custom product content -->
    </x-slot:content>
</x-shop::accordion>
```

::: tip Performance Considerations
- Components include Vue.js integration for optimal performance
- Use shimmer components during loading states
- Leverage built-in responsive design features
- Components are optimized for accessibility
:::

::: warning Component Compatibility
When using components in custom themes:
- Ensure your theme includes necessary CSS classes
- Test components across different screen sizes
- Verify JavaScript functionality with your asset compilation
- Check compatibility with custom styling approaches
:::

## What's Next?

Congratulations! You've completed the theme development journey. Here are your next steps:

**🛠️ [Package Development →](../package-development/getting-started)**  
Build advanced packages that include custom components and functionality.

**🚀 [Performance Optimization →](../performance/introduction)**  
Learn to optimize your themes for better performance and user experience.

**📚 [Back to Getting Started →](./getting-started.md)**  
Review the complete theme development guide or explore different development paths.

# Email Template

Email templates are a crucial part of your Bagisto theme, providing branded communication with your customers. This guide shows you how to customize email templates as part of your theme development workflow, building on the concepts covered in previous theme development sections.

## Understanding Email Templates in Themes

Email templates in Bagisto follow the same theming principles as your storefront views. Just like regular theme views, email templates can be customized and organized within your theme structure to maintain consistency with your brand identity.

### Email Template Architecture

Bagisto's email system uses Laravel's Mailable classes combined with Blade templates, following the same view resolution patterns as your theme:

```
Theme Structure for Emails:
└── resources/themes/your-theme/
    └── views/
        └── emails/
            ├── ...
            ├── ...
            ├── ...
            └── layouts.blade.php
```

::: tip Theme Integration
Email templates follow the same view resolution hierarchy as your theme views. When you customize an email template, it becomes part of your theme package, ensuring consistency across all customer touchpoints.
:::

## Email Template Flow

Email templates in Bagisto work through a system of Mailable classes and Blade views, similar to how your theme handles regular page views. Let's examine how this works:

### Mailable Classes

Bagisto uses mail notification classes located in namespaces like `Webkul\Shop\Mail`. These classes extend Laravel's `Mailable` class and define the email's structure, recipient, and view template.

Here's an example from the order created notification:

```php{42}
<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\Order;
use Webkul\Shop\Mail\Mailable;

class CreatedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Order $order) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->order->customer_email,
                    $this->order->customer_full_name
                ),
            ],
            subject: trans('shop::app.emails.orders.created.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.orders.created',
        );
    }
}
```

### View Resolution

The `view: 'shop::emails.orders.created'` follows the same theme resolution pattern you learned about in [Creating Store Theme](/theme-development/creating-store-theme). Bagisto will look for this view in:

1. **Your active theme**: `resources/themes/your-theme/views/emails/orders/created.blade.php`
2. **Default theme**: `resources/themes/default/views/emails/orders/created.blade.php`  
3. **Package views**: `packages/Webkul/Shop/src/Resources/views/emails/orders/created.blade.php`

### Email Layout System

Email templates use a layout component system similar to your theme layouts:

```blade
@component('shop::emails.layout')
    ...
@endcomponent
```

The layout component (`shop::emails.layout`) provides the base HTML structure, styling, and branding for all emails, similar to how your theme's master layout works.

## Customizing Email Templates

Now let's learn how to customize email templates as part of your theme development. This process follows the same patterns you've learned for customizing other theme views.

### Method 1: Theme-Based Customization (Recommended)

This approach integrates email customization into your theme structure, making it part of your overall theme package.

#### Step 1: Create Email Views in Your Theme

Create the email template structure within your theme directory:

```bash
# Create email directories in your theme
mkdir -p resources/themes/your-theme/views/emails
```

#### Step 2: Override Specific Email Templates

Let's customize the order created email. Create the file:

**`resources/themes/your-theme/views/emails/orders/created.blade.php`**

::: warning Path Matching Requirement
Notice the path we have used: `emails/orders/created.blade.php`. This path is exactly the same as in the shop package (`packages/Webkul/Shop/src/Resources/views/emails/orders/created.blade.php`). We must follow the exact same path structure for view overriding to work. If you use a different path, the view will not be able to override the default template.
:::

```blade{1,8,15-20}
@component('shop::emails.layout')
    <p>This is customized order email.</p>
@endcomponent
```

#### Step 3: Customize the Email Layout

Create a custom email layout for your theme:

**`resources/themes/your-theme/views/emails/layouts.blade.php`**

::: warning Path Matching Requirement
Notice the path we have used: `emails/layouts.blade.php`. This path must match the layout reference used in email templates. The layout component `@component('shop::emails.layout')` will look for this file in the exact path structure for view overriding to work.
:::

::: info Layout Override
We are assuming you will change the layout to match your theme design, so we keep it as it is for now. You can customize the HTML structure, styling, and branding according to your theme requirements.
:::

```blade{5-10,15-25}
<!-- ... existing layout code ... -->
```

### Method 2: Package-Based Customization

If you're developing a theme package (as covered in [Creating Custom Theme Package](/theme-development/creating-custom-theme-package)), email template customization follows the same principles as Method 1.

::: tip Package Structure
Simply place your email templates in your package's `src/Resources/views/emails/` directory following the same path structure:
- `src/Resources/views/emails/orders/created.blade.php`
- `src/Resources/views/emails/layouts.blade.php`
:::

The view registration and override mechanics work exactly the same way - Laravel's view resolution will automatically find your package's email templates when the corresponding views are requested.

For detailed package development setup, refer to the [Creating Custom Theme Package](/theme-development/creating-custom-theme-package) guide.

# Introduction

Bagisto is engineered for **speed**, **scalability**, and **efficiency**, delivering exceptional e-commerce performance even under heavy traffic loads. This comprehensive guide covers advanced performance optimization techniques, intelligent caching strategies, and scalability solutions to maximize your store's potential.

::: info Performance Impact
Modern e-commerce requires [Core Web Vitals](https://web.dev/vitals/) optimization. Bagisto prioritizes excellent [LCP](https://web.dev/lcp/) (Largest Contentful Paint) and [CLS](https://web.dev/cls/) (Cumulative Layout Shift) scores for superior user experience.
:::

## Performance Architecture

### Multi-Layer Caching Strategy
Bagisto implements a sophisticated caching ecosystem designed for maximum performance:

| **Cache Layer** | **Purpose** | **Performance Gain** |
|---|---|---|
| **Full Page Cache (FPC)** | Complete page rendering cache | 80-90% faster page loads |
| **Varnish Cache** | HTTP reverse proxy caching | Sub-100ms response times |
| **Database Query Cache** | Optimized query result storage | 60-70% database load reduction |
| **Asset Bundling** | Static resource optimization | 50% fewer HTTP requests |

### High-Performance Runtime
- **Laravel Octane Integration**: Supercharged application performance with Swoole/RoadRunner
- **Asynchronous Processing**: Non-blocking operations for critical user interactions
- **Memory-Resident Applications**: Persistent application state for lightning-fast responses

### Enterprise Search & Indexing
- **Elasticsearch Integration**: Advanced full-text search with sub-second response times
- **Smart Product Indexing**: Optimized catalog browsing and filtering
- **Real-time Search Suggestions**: Enhanced customer discovery experience

## Performance Optimization Guides

### Core Performance Components

::: tip Quick Start
Start with [Elasticsearch](./configure-elasticsearch) for enhanced search performance, then progress through each optimization layer for maximum impact.
:::

#### **[Configure Elasticsearch](./configure-elasticsearch)**
Advanced search engine for lightning-fast product discovery
- **Production-ready setup** with real-world examples
- **Index optimization** strategies for large catalogs
- **Performance tuning** and monitoring techniques

#### **[Configure Full Page Cache (FPC)](./configure-fpc)**
Complete page rendering cache for maximum speed
- **Zero-configuration setup** for instant performance gains
- **Smart cache invalidation** maintaining data freshness
- **Admin panel integration** with visual cache management

#### **[Configure Varnish](./configure-varnish)**
Enterprise-grade reverse proxy caching
- **HTTP/2 optimization** for modern web standards
- **Custom VCL configuration** for Bagisto-specific caching
- **SSL termination** and security hardening

### Advanced Performance Solutions

#### **[Configure Laravel Octane](./configure-laravel-octane)**
High-performance application runtime with persistent memory
- **Swoole integration** for production environments
- **Development workflow** optimization
- **Performance monitoring** and debugging tools

#### **[Configure Load Balancing](./configure-load-balancing)**
High-availability deployment for enterprise traffic
- **AWS Application Load Balancer** configuration
- **Multi-instance deployment** strategies  
- **Database replication** and failover setup

## Quick Wins

### Immediate Performance Improvements
1. **Configure Elasticsearch** - Enhanced search performance and user experience
2. **Enable Full Page Cache** - 80%+ speed improvement in 5 minutes
3. **Deploy Varnish Caching** - Enterprise-grade HTTP acceleration
4. **Set up Laravel Octane** - 3-5x application throughput increase

### Progressive Enhancement
1. **Configure Load Balancing** - High-availability and traffic distribution
2. **Implement Asset Optimization** - 50% reduction in load times
3. **Optimize Database Queries** - Advanced indexing and query tuning
4. **Implement CDN Integration** - Global content delivery optimization

::: warning Production Deployment
Always test performance optimizations in staging environments before production deployment. Monitor key metrics during rollout to ensure optimal results.
:::

By implementing these performance optimizations systematically, you'll transform your Bagisto store into a **high-performance e-commerce powerhouse** capable of handling enterprise-level traffic while delivering exceptional user experiences.

# Configure Elasticsearch

Elasticsearch is a powerful distributed search and analytics engine that enhances Bagisto's search capabilities with fast, scalable product indexing and advanced search features.

::: info What You'll Learn
- How to install and verify Elasticsearch
- Configure Elasticsearch connections in Bagisto
- Index products for improved search performance
- Verify your Elasticsearch setup
:::

This guide covers configuring Elasticsearch for indexing products from your Bagisto database, enabling lightning-fast search functionality for your e-commerce store.

## Environment Setup

Before configuring Elasticsearch with Bagisto, ensure you have [Elasticsearch installed](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html) on your system.

::: warning Prerequisites
- Elasticsearch 7.0+ (recommended: 8.x)
- PHP 8.3+ with cURL extension
- Sufficient memory allocation (minimum 2GB for Elasticsearch)
:::

### Verify Installation

Elasticsearch runs on port `9200` by default. Test your installation by visiting:

```
http://localhost:9200
```

**Expected Response:**
```json
{
  "name" : "webkul-pc",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "suPotT8zQjCOlq9dteWKyQ",
  "version" : {
    "number" : "8.6.2",
    "build_flavor" : "default",
    "build_type" : "deb",
    "build_hash" : "2d58d0f136141f03239816a4e360a8d17b6d8f29",
    "build_date" : "2023-02-13T09:35:20.314882762Z",
    "build_snapshot" : false,
    "lucene_version" : "9.4.2",
    "minimum_wire_compatibility_version" : "7.17.0",
    "minimum_index_compatibility_version" : "7.0.0"
  },
  "tagline" : "You Know, for Search"
}
```

**Alternative CLI Check:**
```bash
curl -X GET 'http://localhost:9200'
```

## Configuration Setup

Configure Elasticsearch connections in your Bagisto application using the `config/elasticsearch.php` file.

::: code-group

```php [Bagisto 2.1.0+]
<?php
// config/elasticsearch.php

return [
    /**
     * Here you can specify the connection to use when building a client.
     */
    'connection' => 'default',

    /**
     * These are the available connections parameters that you can use to connect
     */
    'connections' => [
        'default' => [
            'hosts' => [
                env('ELASTICSEARCH_HOST', 'http://localhost:9200'),
            ],

            'user' => env('ELASTICSEARCH_USER', null),
            'pass' => env('ELASTICSEARCH_PASS', null),
        ],

        /**
         * You can connect with API key authentication by setting the `api` key
         * instead of the `user` and `pass` keys.
         */
        'api' => [
            'hosts' => [
                env('ELASTICSEARCH_HOST', null),
            ],

            'key' => env('ELASTICSEARCH_API_KEY', null),
        ],

        /**
         * You can connect to Elastic Cloud with the Cloud ID using the `cloud` key.
         */
        'cloud' => [
            'id' => env('ELASTICSEARCH_CLOUD_ID', null),

            /**
             * If you are authenticating with API KEY then set user and pass as null
             */
            'api_key' => env('ELASTICSEARCH_API_KEY', null),

            /**
             * If you are authenticating with username and password then set api_key as null
             */
            'user' => env('ELASTICSEARCH_USER', null),
            'pass' => env('ELASTICSEARCH_PASS', null),
        ],
    ],

    /**
     * CA Bundle
     *
     * If you have the http_ca.crt certificate copied during the start of Elasticsearch
     * then the path here
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/connecting.html#auth-http
     */
    'caBundle' => null,

    /**
     * Retries
     *
     * By default, the client will retry n times, where n = number of nodes in
     * your cluster. If you would like to disable retries, or change the number,
     * you can do so here.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/set-retries.html
     */
    'retries' => null,
];
```

```properties [.env Configuration]
# Basic Elasticsearch Configuration
ELASTICSEARCH_HOST=http://localhost:9200
ELASTICSEARCH_USER=
ELASTICSEARCH_PASS=

# For API Key Authentication
ELASTICSEARCH_API_KEY=your_api_key_here

# For Elasticsearch Cloud
ELASTICSEARCH_CLOUD_ID=your_cloud_id
```

```php [Bagisto 2.0.0]
// config/elasticsearch.php
'hosts' => [
    [
        'host' => env('ELASTICSEARCH_HOST', 'localhost'),
        'port' => env('ELASTICSEARCH_PORT', 9200),
    ]
]
```

```properties [.env for 2.0.0]
ELASTICSEARCH_PORT=9200
ELASTICSEARCH_HOST=localhost
```

:::

### Configuration Options

| Option | Description | Default |
|--------|-------------|---------|
| `hosts` | Elasticsearch server endpoints | `http://localhost:9200` |
| `user/pass` | Basic authentication credentials | `null` |
| `api_key` | API key for authentication | `null` |
| `cloud.id` | Elasticsearch Cloud identifier | `null` |
| `caBundle` | SSL certificate bundle path | `null` |
| `retries` | Connection retry attempts | Auto (node count) |

::: tip Authentication Methods
Choose one authentication method:
- **None**: For local development
- **Basic Auth**: Username/password
- **API Key**: Recommended for production
- **Cloud**: For Elasticsearch Service
:::

### Apply Configuration

After updating your `.env` file, clear cache configuration:

```bash
php artisan optimize:clear
```

## Indexing Products

Once configured, Bagisto automatically indexes new products when they're created. For existing products, manual indexing is required.



### Index Existing Products

Run the indexer command to process all existing products:

```bash
php artisan indexer:index
```

::: warning Queue Driver Configuration
If your `QUEUE_CONNECTION` in `.env` is set to `database`, `redis`, or any driver other than `sync`, you must run the queue worker to process indexing jobs:

```bash
php artisan queue:listen
```

Without the queue worker running, products will not be indexed properly.
:::

::: details What happens during indexing?
- Products are read from the `products` table
- Data is transformed into Elasticsearch-compatible format
- Documents are bulk-inserted into the search index
- Search capabilities become available immediately
:::

### Automatic Indexing

New products are automatically indexed when:
- Products are created via admin panel
- Products are imported via CSV
- Product data is updated through API

::: warning Performance Note
Large product catalogs may take several minutes to index. Consider running indexing during off-peak hours for production stores.
:::

## Verification

### Check Index Status

Verify your products have been indexed successfully:

**Browser Method:**
```
http://localhost:9200/_cat/indices?v
```

**CLI Method:**
```bash
curl -X GET 'http://localhost:9200/_cat/indices?v'
```

**Expected Output:**
```
health status index    uuid                   pri rep docs.count docs.deleted store.size pri.store.size
yellow open   products AbcDef1234567890       1   1      1500           0      2.5mb          2.5mb
```

### Search Test

Configure Elasticsearch in your Bagisto admin panel and test frontend search:

**Admin Configuration:**
1. Go to **Admin Panel → Configuration → Catalog → Products**
2. Set the following options to **Elasticsearch**:
   - **Search Engine**
   - **Admin Search Mode** 
   - **Storefront Search Mode**
3. Save the configuration

**Frontend Testing:**
- Visit your store's frontend
- Use the search functionality to look for products
- Results should appear faster with improved relevance

**Alternative CLI Test:**
```bash
curl -X GET "localhost:9200/products/_search?q=product_name:sample"
```

::: tip Success Indicators
- ✅ Index appears in the indices list
- ✅ `docs.count` matches your product count
- ✅ Admin panel search settings saved successfully
- ✅ Frontend search returns faster, more relevant results
:::

## Troubleshooting

### Common Issues

| Problem | Solution |
|---------|----------|
| Connection refused | Check if Elasticsearch is running on port 9200 |
| Memory errors | Increase Elasticsearch heap size |
| Slow indexing | Reduce batch size in indexer configuration |
| Missing products | Re-run `php artisan indexer:index` |

### Performance Tips

- **Memory**: Allocate at least 2GB RAM to Elasticsearch
- **Storage**: Use SSD storage for better performance  
- **Network**: Keep Elasticsearch on the same server as Bagisto
- **Monitoring**: Use Elasticsearch monitoring tools in production

::: warning Production Considerations
- Enable authentication in production environments
- Configure SSL/TLS for secure connections
- Set up regular backup and monitoring
- Consider using Elasticsearch Service for managed hosting
:::

# Configure Full Page Cache (FPC)

Bagisto's Full Page Cache delivers lightning-fast page loading, improved SEO, enhanced scalability, and reduced server load for superior eCommerce performance.

::: info What You'll Learn
- How to enable and configure Full Page Cache
- Understand cache invalidation strategies
- Implement custom cache listeners
- Optimize cache performance for your store
:::

## Overview

Full Page Cache stores complete HTML pages in memory, serving them instantly without re-executing server-side logic. This dramatically reduces database queries, template rendering, and resource-intensive operations for **significantly faster page load times**.

::: tip Built on Spatie
Bagisto uses the proven [Spatie Laravel Responsecache Package](https://github.com/spatie/laravel-responsecache) for reliable cache management.
:::

## Configuration

Before enabling Full Page Cache, ensure your environment meets the minimum requirements and that you have a backup of your `.env` and configuration files. Proper configuration is essential for optimal performance and to avoid serving outdated content.

### Enable Full Page Cache

Add the following configuration to your `.env` file:

::: code-group

```properties [Environment Setup]
# Enable Full Page Cache
RESPONSE_CACHE_ENABLED=true

# Optional: Set cache lifetime (in minutes)
RESPONSE_CACHE_LIFETIME=10080  # 1 week

# Optional: Set cache driver
RESPONSE_CACHE_DRIVER=file     # file, redis, memcached, dynamodb
```

```bash [Quick Enable]
# Add to your .env file
echo "RESPONSE_CACHE_ENABLED=true" >> .env
```

:::

### Configure Cache Settings

Customize cache behavior in `config/responsecache.php`:

```php
// config/responsecache.php

return [
    // Enable/disable cache
    'enabled' => env('RESPONSE_CACHE_ENABLED', false),
    
    // Cache lifetime in minutes
    'cache_lifetime_in_minutes' => env('RESPONSE_CACHE_LIFETIME', 60 * 24 * 7), // 1 week
    
    // Cache store to use
    'cache_store' => env('RESPONSE_CACHE_DRIVER', 'file'),
    
    // Add cache headers for debugging
    'add_cache_time_header' => env('APP_DEBUG', false),
    'cache_time_header_name' => 'laravel-responsecache',
];
```

## Supported Features

Bagisto FPC supports advanced features such as automatic cache invalidation, selective cache clearing, cache warming, and integration with multiple cache drivers. It is designed to work seamlessly with Bagisto's event system, ensuring that only relevant pages are cached and updated as your catalog changes.

### Cached Pages

Full Page Cache optimally works with these page types:

| Page Type | Performance Gain | SEO Impact |
|-----------|------------------|------------|
| **Home Page** | 🚀 Excellent | ⭐ High |
| **Category Pages** | 🚀 Excellent | ⭐ High |
| **Product Pages** | 🚀 Excellent | ⭐ Very High |
| **CMS Pages** | 🚀 Excellent | ⭐ High |

::: tip CMS Page Caching
CMS pages (About Us, Privacy Policy, Terms & Conditions, etc.) are ideal candidates for Full Page Cache since they rarely change and benefit significantly from caching. This improves load times for important informational pages that customers frequently visit.
:::

::: warning Dynamic Content
Pages with user-specific content (cart, wishlist, account) are automatically excluded from caching to ensure personalized experiences.
:::

### Cache Drivers

Choose the best cache driver for your infrastructure:

| Driver | Performance | Scalability | Setup Complexity |
|--------|-------------|-------------|------------------|
| **File** | ⭐⭐⭐ | ⭐⭐ | 🟢 Easy |
| **Redis** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | 🟡 Moderate |
| **Memcached** | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | 🟡 Moderate |
| **DynamoDB** | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | 🔴 Complex |

::: tip Recommendation
- **Development**: Use `file` driver
- **Production**: Use `redis` for best performance
- **Enterprise**: Consider `dynamodb` for global scale
:::

## Cache Management

Full Page Cache can be managed easily using artisan commands and configuration options. You can clear the entire cache, target specific URLs, or automate cache clearing through event listeners. This ensures your store always serves up-to-date content while maintaining high performance.

### Clear All Cache

Remove all cached responses:

```bash
php artisan responsecache:clear
```

### Clear Specific URL

Target a specific page for cache removal:

```bash
php artisan responsecache:clear --url=https://yourstore.com/products/sample-product
```

::: warning Cache Clearing
Always clear cache after:
- Product updates
- Category changes
- Price modifications
- Inventory updates
:::

## Cache Invalidation

Bagisto's Full Page Cache (FPC) system uses event-driven cache invalidation to ensure data consistency while maintaining optimal performance. Here's how real-world cache invalidation works with actual Bagisto examples:

### Product Cache Invalidation

When products are updated in Bagisto, the FPC system automatically invalidates related cache entries using sophisticated relationship mapping:

::: code-group

```php [Product Listener - Real Bagisto Implementation]
<?php
namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product
{
    ...
    /**
     * Update or create product page cache
     *
     * @param \Webkul\Product\Contracts\Product $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $urls = $this->getForgettableUrls($product);
        
        ResponseCache::forget($urls);
    }
    ...
}
```

```php [Event Registration - Bagisto FPC]
<?php

namespace Webkul\FPC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ...
        'catalog.product.update.after' => [
            'Webkul\FPC\Listeners\Product@afterUpdate',
        ],
        ...
    ];
}
```

:::

## Performance Optimization

### Granular Cache Strategy

::: tip Performance Benefits

- **Selective Clearing**: Only affected pages are invalidated, not entire cache
- **Relationship Mapping**: Complex product relationships are handled automatically
- **Batch Operations**: Multiple URLs are cleared in single operations
- **Memory Efficient**: Uses product IDs and relationships rather than loading full objects
:::

## Performance Benefits

Implementing Full Page Cache in Bagisto provides significant advantages:

| Metric | Improvement | Impact |
|--------|-------------|---------|
| **Page Load Time** | 60-80% reduction | 🚀 Excellent |
| **Server Load** | 70-90% reduction | ⚡ Outstanding |
| **Database Queries** | 95%+ reduction | 💾 Exceptional |
| **SEO Rankings** | Faster site speed | 📈 Improved |

### Performance Monitoring

Monitor your cache effectiveness:

```bash
# Check cache statistics (Redis example)
redis-cli info stats

# Look for:
# - keyspace_hits: successful cache retrievals
# - keyspace_misses: cache misses requiring generation
```

### Production Checklist

::: warning Production Considerations
- ✅ Configure appropriate cache lifetime
- ✅ Set up cache invalidation events
- ✅ Monitor cache hit rates
- ✅ Implement cache warming for critical pages
- ✅ Configure proper cache drivers
- ✅ Set up monitoring and alerting
:::

::: tip Developer Note
Always ensure proper cache invalidation strategies are in place when implementing Full Page Cache to prevent serving stale content to your users.
:::

# Configure Varnish

Bagisto introduces Varnish Cache integration to deliver lightning-fast page loading, improved SEO, scalability, and reduced server load for enhanced eCommerce performance.

Varnish acts as a reverse proxy and caches full HTML pages. When a request is made for a cached page, Varnish serves it directly without executing backend logic. This reduces database queries, template rendering, and other resource-intensive operations, resulting in faster page loads.

Varnish also supports ESI (Edge Side Includes) and AJAX-based dynamic views, allowing dynamic content (like carts, dropdowns, or login blocks) to load fresh while the rest of the page remains cached.

## How to Enable Varnish Integration in Bagisto

### Install the Package

Install the package via Composer:

```bash
composer require bagisto/bagisto-varnish
```

### Register Service Provider

Register the service provider in `bootstrap/providers.php`:

```php
'providers' => [
    Webkul\Varnish\Providers\VarnishServiceProvider::class,
],
```

### Publish Configuration

Publish assets and configuration:

```bash
php artisan vendor:publish --provider="Webkul\Varnish\Providers\VarnishServiceProvider"
```

### Configure Alias

Configure the alias in `config/varnish.php`:

```php
return [
    'aliases' => [
        'Varnish' => \Webkul\Varnish\Facades\VarnishCache::class,
    ],
];
```

## Full Page Cache Supported Pages

- Home Page
- Category Page
- Product Page
- CMS Pages

## Varnish Server Configuration

### Install Varnish

Install Varnish 6.x on your server.

### Configure VCL File

Replace `/etc/varnish/default.vcl` with the provided file:

```
Varnish/vcls/6.0.vcl
```

### Restart Varnish

Restart Varnish:

```bash
sudo systemctl restart varnish
```

## Theme Integration

### Define Dynamic Views / Fragments

In `config/esi_views.php`, define a key and its Blade view path:

```php
return [
    'customer-desktop-dropdown' =>
        'varnish::shop.components.layouts.header.desktop.customer-dropdown',
];
```

**Configuration:**
- **Key** → Used in ESI or AJAX call (`customer-desktop-dropdown`)
- **Path** → Full Blade view path (`varnish::...`)

### ESI Include

```html
<esi:include src="/esi?tag=customer-desktop-dropdown" />
```

**ESI Characteristics:**
- Injects content at the Varnish level (server-side)
- Appears immediately on page load
- May affect LCP/FCP if backend is slow

### AJAX Dynamic View (Recommended for LCP)

```html
<x-varnish::dynamic-view view="customer-desktop-dropdown" />
```

**AJAX Benefits:**
- Loads via AJAX after user interaction
- Improves LCP/FCP
- Ideal for non-critical dropdowns, modals, and menus

## Cache-Control Headers

### Non-Cacheable Routes

```http
Cache-Control: no-cache, no-store, must-revalidate
```

### Cacheable Routes

```http
Cache-Control: public, max-age=604800, s-maxage=604800
```
*(Example: 7 days)*

### Middleware for Cache Headers

```php
Route::get('/', [HomeController::class, 'index'])
    ->middleware('cache.response');
```

## Clearing Cache with Artisan Commands

To clear Varnish cache using Artisan commands:

```bash
php artisan varnish:purge
```

- Clears all cache entries
- Optionally, provide URLs to purge specific pages:

```bash
php artisan varnish:purge --url=http://example.com
```

Replace `http://example.com` with the URL you want to clear from cache.

## Cache Invalidation

### Automatic Cache Purging

The Varnish package automatically purges cache when:

- Products, categories, CMS pages, orders, reviews, refunds, or themes are updated

### Manual Cache Purging in Code

You can trigger cache invalidation manually using:

```php
VarnishCache::forget($urls);
```

# Configure Laravel Octane

[Laravel Octane](https://laravel.com/docs/12.x/octane) is a performance-boosting package designed to enhance the speed, efficiency, and scalability of Laravel applications, including Bagisto.

::: info What You'll Learn
- Install Laravel Octane in your Bagisto application
- Configure Octane for optimal performance
- Run and manage the Octane server
:::

## Benefits for Bagisto

- **Performance**: Drives remarkable improvements in page load times, ensuring a seamless and responsive shopping experience
- **Scalability**: Provides the scalability required to accommodate the growth of e-commerce businesses
- **Foundation**: Forms the foundation for optimizing Bagisto's performance and meeting the demands of modern e-commerce

## Prerequisites

::: warning Requirements
- Swoole PHP extension must be installed on your system
- PHP 8.3 or higher
- Existing Bagisto installation
:::

Verify Swoole is installed:

```bash
php --ri swoole
```

## Installation

### Install Laravel Octane

Navigate to your Bagisto directory and install Octane:

```bash
# Navigate to your Bagisto project
cd /path/to/your/bagisto

# Install Laravel Octane
composer require laravel/octane

# Install Octane with Swoole server
php artisan octane:install --server=swoole
```

## Configuration

### Environment Configuration

Add Octane settings to your `.env` file:

```properties
# Laravel Octane Configuration
OCTANE_SERVER=swoole
OCTANE_HOST=0.0.0.0
OCTANE_PORT=8000
OCTANE_WORKERS=4
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=1000
```

### Basic Octane Configuration

The installation command creates `config/octane.php`. Key settings for Bagisto:

```php
// config/octane.php
return [
    'server' => env('OCTANE_SERVER', 'swoole'),
    
    'servers' => [
        'swoole' => [
            'host' => env('OCTANE_HOST', '0.0.0.0'),
            'port' => env('OCTANE_PORT', 8000),
            'workers' => env('OCTANE_WORKERS', 4),
            'task_workers' => env('OCTANE_TASK_WORKERS', 6),
            'max_requests' => env('OCTANE_MAX_REQUESTS', 1000),
        ],
    ],
    
    // Warm services on worker start
    'warm' => [
        ...Octane::defaultServicesToWarm(),
    ],
];
```

## Running Octane

### Development Mode

Start Octane with auto-reload for development:

```bash
php artisan octane:start --watch
```

Access your Bagisto application at: `http://localhost:8000`

### Production Mode

Start Octane for production without file watching:

```bash
php artisan octane:start --workers=8 --task-workers=6
```

### Server Management

::: code-group

```bash [Start Server]
# Start with default settings
php artisan octane:start

# Start with custom workers
php artisan octane:start --workers=4 --task-workers=6
```

```bash [Stop/Restart]
# Stop the server
php artisan octane:stop

# Restart the server
php artisan octane:restart

# Reload workers (without downtime)
php artisan octane:reload
```

:::

## Performance Settings

### Worker Configuration

Optimize workers based on your server specs:

| Server Configuration | Workers | Task Workers |
|---------------------|---------|--------------|
| **2 CPU cores, 4GB RAM** | 4 | 4 |
| **4 CPU cores, 8GB RAM** | 8 | 6 |
| **8 CPU cores, 16GB RAM** | 16 | 12 |

### Environment Optimization

```properties
# Optimized settings for production
OCTANE_WORKERS=8
OCTANE_TASK_WORKERS=6
OCTANE_MAX_REQUESTS=2000
OCTANE_HOST=0.0.0.0
OCTANE_PORT=8000
```

## Monitoring

### Check Server Status

```bash
# View server status
php artisan octane:status

# Monitor memory usage
ps aux | grep octane
```

### Performance Monitoring

Expected performance improvements:

| Metric | Before Octane | With Octane |
|--------|---------------|-------------|
| **Requests/second** | 50-100 | 500-1000+ |
| **Response time** | 200-500ms | 50-100ms |
| **Memory efficiency** | Variable | Stable |

## Common Issues

### Troubleshooting

| Problem | Solution |
|---------|----------|
| **Port already in use** | Change `OCTANE_PORT` or stop conflicting services |
| **Memory leaks** | Reduce `OCTANE_MAX_REQUESTS` to restart workers more frequently |
| **Slow startup** | Ensure database connections are properly configured |

### Debug Commands

```bash
# Test if Swoole is working
php -m | grep swoole

# Check Octane installation
php artisan octane:install --server=swoole

# View worker processes
ps aux | grep "artisan octane"
```

::: tip Quick Start
1. Install: `composer require laravel/octane`
2. Setup: `php artisan octane:install --server=swoole`
3. Configure: Update `.env` with Octane settings
4. Run: `php artisan octane:start --watch`
5. Access: Visit `http://localhost:8000`
:::

::: warning Production Notes
- Use a process manager (like Supervisor) to keep Octane running
- Configure a reverse proxy (Nginx) for production deployments
- Monitor memory usage and restart workers periodically
- Test thoroughly before deploying to production
:::

# Configure Load Balancing

This guide provides a step-by-step approach to deploying Bagisto on AWS with an Application Load Balancer (ALB) for improved scalability and high availability. By setting up a dedicated database server, integrating Amazon S3 for media storage, and configuring an AMI-based auto-scaling setup, you can ensure that Bagisto can handle increased traffic efficiently.

::: info What You'll Build
- Dedicated MySQL server for better performance
- Bagisto on multiple EC2 instances behind a Load Balancer
- Amazon S3 for storage instead of local file storage
- AMI to easily scale new instances
- Application Load Balancer (ALB) for traffic distribution
- SSL and domain configuration for secure access
:::

This setup ensures a scalable, fault-tolerant, and high-performance Bagisto application.

## Bagisto with ALB Setup on AWS

### Dedicated Database Server Setup

#### Launch EC2 Instance for MySQL Database

- Launch an EC2 instance for MySQL database
- Assign an Elastic IP to the EC2 instance

#### Install and Configure MySQL Server

SSH into the EC2 instance and update the system:

```bash
sudo apt-get update
```

Install MySQL server:

```bash
sudo apt-get install mysql-server
```

Secure the MySQL installation and create the database:

```sql
sudo mysql -u root -p
CREATE DATABASE bagistodb;
CREATE USER 'bagistouser'@'localhost' IDENTIFIED BY '<your-db-password>';
GRANT ALL ON bagistodb.* TO 'bagistouser'@'localhost' WITH GRANT OPTION;
SET GLOBAL log_bin_trust_function_creators = 1;
FLUSH PRIVILEGES;
EXIT;
```

#### Update MySQL Bind Address

Modify the bind address from `127.0.0.1` to `0.0.0.0` in `/etc/mysql/mysql.conf.d/mysqld.cnf`.

#### Restart MySQL Server

Restart the MySQL service:

```bash
sudo systemctl restart mysql
```

#### Open MySQL Port (3306) in Security Group

Modify the security group for this EC2 instance to allow inbound traffic on port 3306.

#### Verify Database Connectivity

Test connectivity using:

```bash
telnet <EC2-ip> 3306
```

### S3 Bucket Configuration for Bagisto

#### Create S3 Bucket

Follow the documentation [S3 Bucket and Policy Setup for Bagisto](https://bagisto.com/en/s3-bucket-and-policy-setup-for-bagisto-amazon-s3-extension) to create an S3 bucket.

#### Configure IAM Role for S3 Access

Create an IAM role with the required permissions to allow Bagisto to interact with S3. Use the Access Key ID, Secret Key, Bucket Name, Region, and Bucket URL.

### Bagisto Server Setup

#### Launch EC2 Instance for Bagisto Application

Launch a new EC2 instance for the Bagisto application.

#### Install MySQL Client

SSH into the EC2 instance and install the MySQL client:

```bash
sudo apt-get install mysql-client-8.0
```

#### Install Bagisto

Follow the steps from the [Bagisto setup guide on AWS](https://cloudkul.com/blog/how-to-setup-bagisto-on-aws/) but skip step 7 (Install MySQL Server) as it is already done in the dedicated DB server.

Use the latest [composer commands](https://getcomposer.org/download/).

#### Configure Bagisto Database Connection

Use the following database details:

::: code-group

```properties [Database Configuration]
DB_HOST=<public IP of dedicated DB server>
DB_DATABASE=bagistodb
DB_USERNAME=bagistouser
DB_PASSWORD=<your-db-password>
```

```php [Manual Configuration]
// If configuring manually
Host: <public IP of dedicated DB server>
User: bagistouser
Dbname: bagistodb
Password: <your-db-password>
```

:::

#### Install S3 Integration Module

Install and configure the S3 module for Bagisto using the IAM credentials.

#### Access Bagisto Admin Panel

Visit the admin panel at: `http://<this-ec2-public-ip>/admin` to complete configurations.

### Create AMI for Bagisto Application

#### Create AMI from Bagisto EC2 Instance

Go to **Instances > Actions > Image and templates > Create Image**.

![Create Image](/images/configure-load-balancing/create-image-ec2.png)

Enter the image name and click **Create Image**.

#### Wait for AMI to Be Available

Monitor the status of the AMI in the AMI section.

#### Launch Instances from AMI

Once the AMI is available, go to AMIs and launch as many instances as required in the target group.

### Configure Target Group

#### Create Target Group

Go to Target Groups and click Create Target Group. Choose Instances as the target type and give it a name.

![Target Group](/images/configure-load-balancing/target-group.png)

#### Register Instances in the Target Group

Choose the instances to be added to the target group:

![Create Target Group](/images/configure-load-balancing/create-target-group.png)

#### Configure Load Balancing Algorithm

After creating the target group, go to Attributes and choose the desired load-balancing algorithm (default: round-robin). Enable stickiness.

### Create and Configure Application Load Balancer (ALB)

#### Create ALB

Go to **Load Balancers** and click **Create Load Balancer**.

Choose **Application Load Balancer** and provide the following configurations:

- **Internet-facing**
- Select all **Availability Zones**
- Choose the target group created earlier and configure the listener on **port 80 (HTTP)**

![Create Load Balancer](/images/configure-load-balancing/create-load-balancer.png)

![Create Load Balancer](/images/configure-load-balancing/load-balancer-port.png)

![Create Load Balancer](/images/configure-load-balancing/load-balancer-port-80.png)

#### Adjust Security Groups

Modify the security groups to allow necessary traffic (e.g., HTTP/HTTPS).

#### Verify Load Balancer Configuration

- Copy the **DNS name** of the load balancer and verify it
- Configure the **domain name** (CNAME) to point to this load balancer DNS for production use

#### Set Up SSL on Load Balancer

Configure the ALB listener to use HTTPS (port 443), ensuring that an SSL certificate is installed.

![Load Balancer Result](/images/configure-load-balancing/load-balancer-result.png)

### Verify and Test the Entire Setup

#### Test Load Balancer

Confirm that the load balancer is distributing traffic across the registered instances.

#### Verify Domain Configuration

Ensure the domain is resolving correctly to the load balancer.

#### Test Bagisto Functionality

Access the Bagisto application and verify that:
- S3 integration is working
- Application can connect to the MySQL database
- All features are functional

::: tip Success
**Bagisto with ALB Setup on AWS is Successfully Configured!**

The Bagisto application is now running behind an Application Load Balancer (ALB) with a dedicated MySQL database and S3 integration for storage. The load balancer is efficiently distributing traffic across multiple instances, ensuring high availability and scalability.
:::

# Digging Deeper

Welcome to the "Digging Deeper" section of Bagisto documentation! This advanced section covers sophisticated techniques for extending, customizing, and optimizing your Bagisto installation beyond basic theme and package development.

::: info Prerequisites
This section assumes you have:
- Completed the [Package Development](../package-development/getting-started) guides
- Understanding of [Theme Development](../theme-development/getting-started) concepts  
- Solid knowledge of Laravel concepts (events, validation, service providers)
- Experience with PHP and object-oriented programming
:::

## What You'll Master

This section focuses on advanced Bagisto customization techniques that allow you to:

### Core System Extensions
- **Override Core Models**: Safely extend Bagisto's core functionality without modifying source code
- **Event Listeners**: Hook into Bagisto's event system to add custom functionality at specific points
- **View Render Events**: Dynamically inject content into existing templates without file modifications

### Advanced Development Patterns
- **Helper Functions**: Leverage and create custom helper functions for common development tasks
- **Validation Systems**: Implement custom validation rules and integrate them with Bagisto's validation framework
- **Security Best Practices**: Follow security guidelines for production Bagisto applications

### Data Management & Integration
- **Data Import Systems**: Build custom data importers for bulk operations and third-party integrations
- **Email Template Customization**: Create and modify email templates for various Bagisto events

## Why These Techniques Matter

Unlike basic package development, these advanced techniques allow you to:

- **Maintain Upgradability**: Extend functionality without breaking future Bagisto updates
- **Build Production-Ready Solutions**: Implement enterprise-level features and integrations
- **Create Reusable Components**: Develop systems that can be shared across multiple projects
- **Handle Complex Business Logic**: Solve sophisticated e-commerce requirements

## Learning Path

We recommend following this progression:

1. **Start with Events**: Understanding Bagisto's event system is foundational for most advanced techniques
2. **Master Model Overriding**: Learn to safely extend core functionality
3. **Explore View Rendering**: Add dynamic content without template modifications
4. **Build Custom Validators**: Implement business-specific validation rules
5. **Create Data Importers**: Handle bulk operations and external integrations

::: tip Integration with Previous Learning
These advanced techniques build upon concepts from:
- [Package Development](../package-development/getting-started) - Service providers, routing, and views
- [Theme Development](../theme-development/getting-started) - Blade templates and asset management
- [Performance Optimization](../performance/introduction) - Efficient coding practices
:::

## Advanced vs. Basic Development

| Basic Development | Advanced Development |
|------------------|---------------------|
| Creating new packages | Extending core functionality |
| Building custom themes | Overriding system behavior |
| Adding new routes/views | Hooking into existing workflows |
| Static customizations | Dynamic, event-driven solutions |

Ready to dive deep into Bagisto's advanced capabilities? Let's start with understanding how Bagisto's event system works and how you can leverage it for powerful customizations!

# Understanding Core Class

Bagisto provides a comprehensive helper system built around the Core class to streamline development and provide easy access to essential functionality. These helpers eliminate the need for repetitive code patterns and offer a consistent API for common operations across channels, currencies, locales, and configuration management.

## Introduction

The Core class (`Webkul\Core\Core`) serves as Bagisto's central utility hub, providing a unified interface for accessing system-wide functionality. It acts as a facade that abstracts complex operations into simple, memorable method calls.

### Architecture Overview

The Core class is designed around Bagisto's multi-tenant architecture, where:

- **Channels** represent different storefronts or websites
- **Locales** handle internationalization and language settings  
- **Currencies** manage multi-currency operations
- **Configuration** provides centralized settings management

### Accessing the Core Class

Bagisto provides a global `core()` helper function that returns the Core class instance:

```php
// Direct access to Core class methods
$version = core()->version();
$currentChannel = core()->getCurrentChannel();
$allCurrencies = core()->getAllCurrencies();
```

::: tip Global Helper Function
The `core()` function is globally available throughout your Bagisto application, making it accessible in controllers, views, service providers, and custom classes without any imports.
:::

### Core Class Benefits

- **Consistency**: Standardized API across all Bagisto operations
- **Simplicity**: Complex operations reduced to single method calls
- **Context Awareness**: Automatically handles current channel/locale context
- **Performance**: Optimized caching and lazy loading for frequently accessed data

### Internal Architecture

The Core class maintains several protected properties for caching and performance:

```php
protected $currentChannel;    // Cached current channel
protected $defaultChannel;    // Cached default channel  
protected $currentCurrency;   // Cached current currency
protected $baseCurrency;      // Cached base currency
protected $currentLocale;     // Cached current locale
protected $guestCustomerGroup; // Cached guest customer group
protected $exchangeRates = []; // Cached exchange rates
protected $taxCategoriesById = []; // Cached tax categories
protected $singletonInstances = []; // Cached singleton instances
```

::: tip Caching Strategy
The Core class implements intelligent caching to minimize database queries. Once a channel, currency, or locale is loaded, it's stored in memory for the request lifecycle.
:::

## Core Helper Methods

The following sections detail the various helper methods available through the Core class, organized by functionality area.

### System Information

#### Get the version number of Bagisto

To get the version number of your Bagisto application, you can use the `core()->version()` method:

```php
$version = core()->version();
// Returns: "v2.4.0"
```

### Channel Management

Channels in Bagisto represent different storefronts or websites, enabling multi-channel retailing from a single installation.

#### Get all channels

The `core()->getAllChannels()` method retrieves all available channels in the application, useful for channel selection interfaces and multi-channel operations:

```php
$channels = core()->getAllChannels();
// Returns: Collection of channel models
```

#### Get current channel model

The `core()->getCurrentChannel()` method retrieves the current channel being accessed:

```php
$currentChannel = core()->getCurrentChannel();
// Returns: Current channel model with all properties
```

#### Set the current channel

Dynamically change the active channel:

```php
core()->setCurrentChannel($channel);
```

#### Get current channel code

Retrieve just the code of the currently active channel:

```php
$channelCode = core()->getCurrentChannelCode();
// Returns: "default", "mobile", etc.
```

#### Get default channel model

Access the default channel configuration:

```php
$defaultChannel = core()->getDefaultChannel();
```

#### Get default channel code

Retrieve the default channel code from configuration:

```php
$defaultCode = core()->getDefaultChannelCode();
```

#### Get default locale code from default channel

Get the locale code for the default language in the default channel:

```php
$defaultLocaleCode = core()->getDefaultLocaleCodeFromDefaultChannel();
```

#### Get channel from request

Retrieve the channel based on the current HTTP request:

```php
$requestedChannel = core()->getRequestedChannel();
```

#### Get channel code from request

Get just the channel code from the current request:

```php
$requestedCode = core()->getRequestedChannelCode();
```

#### Get channel name

Retrieve the display name of the current channel:

```php
$channelName = core()->getChannelName($channel);
// Note: Requires passing a channel object as parameter
```

::: info Parameter Required
Unlike other channel methods, `getChannelName()` requires a channel object as a parameter. It handles translation fallbacks automatically.
:::  

### Locale Management

Locales handle internationalization, language settings, and regional preferences.

#### Get all locales

Retrieve all available locales in the system:

```php
$locales = core()->getAllLocales();
// Returns: Collection of locale models
```

#### Get current locale

Get the currently active locale:

```php
$currentLocale = core()->getCurrentLocale();
```

#### Get locale from request

Retrieve the locale from the current request:

```php
$requestedLocale = core()->getRequestedLocale();
```

#### Get locale code from request

Get the locale code with optional fallback:

```php
$localeCode = core()->getRequestedLocaleCode($localeKey = 'locale', $fallback = true);
```

Parameters:
- `$localeKey`: The key to look for in the request (default: 'locale')
- `$fallback`: Whether to provide a fallback locale if not found (default: true)

#### Get locale code in requested channel

Ensure the locale code is valid for the requested channel:

```php
$validLocaleCode = core()->getRequestedLocaleCodeInRequestedChannel();
```

If not found, this method sets the channel's default locale code.

### Currency Management

Bagisto's currency helpers facilitate multi-currency operations and conversions.

#### Get all currencies

Retrieve all available currencies:

```php
$currencies = core()->getAllCurrencies();
```

#### Get base currency model

Get the application's base currency:

```php
$baseCurrency = core()->getBaseCurrency();
```

#### Get base currency code

Retrieve the base currency code:

```php
$baseCurrencyCode = core()->getBaseCurrencyCode();
```

#### Get channel's base currency

Get the base currency model for the current channel:

```php
$channelBaseCurrency = core()->getChannelBaseCurrency();
```

#### Get channel's base currency code

Retrieve the base currency code for the current channel:

```php
$channelBaseCurrencyCode = core()->getChannelBaseCurrencyCode();
```

#### Set current currency

Change the active currency:

```php
core()->setCurrentCurrency($currencyCode);
```

#### Get current currency model

Retrieve the current channel's currency:

```php
$currentCurrency = core()->getCurrentCurrency();
```

#### Get current currency code

Get the current channel's currency code:

```php
$currentCurrencyCode = core()->getCurrentCurrencyCode();
```

#### Get exchange rates

Retrieve exchange rate information by target currency ID:

```php
$exchangeRate = core()->getExchangeRate($targetCurrencyId);
// Returns: ExchangeRate model or null
```

::: warning Parameter Type
This method expects a currency ID (integer), not a currency code. Use the currency model's ID property.
:::

#### Convert price

Convert an amount to a target currency:

```php
$amount = 100;
$targetCurrencyCode = 'EUR';
$convertedAmount = core()->convertPrice($amount, $targetCurrencyCode);
```

#### Convert to base price

Convert an amount from a specific currency to the base currency:

```php
$amount = 200;
$targetCurrencyCode = 'EUR';
$baseAmount = core()->convertToBasePrice($amount, $targetCurrencyCode);
```

#### Format and convert price with currency

The `currency()` method formats and converts a price with automatic currency conversion:

```php
$formattedPrice = core()->currency($amount);
// Converts to current currency and formats with symbol
```

#### Format price with currency

Format and display a price with currency symbol:

```php
$price = 100;
$currencyCode = 'USD'; // Optional, uses current currency if not provided
$formattedPrice = core()->formatPrice($price, $currencyCode);
```

#### Format base price

Format a price with the base currency symbol:

```php
$formattedBasePrice = core()->formatBasePrice($price, $isEncoded = false);
```

The `$isEncoded` parameter controls whether to encode the currency symbol.

### Date and Time Helpers

#### Check if date is in interval

Verify if the current channel date falls within a specified range:

```php
$isInRange = core()->isChannelDateInInterval($dateFrom = null, $dateTo = null);
```

#### Get channel timestamp

Get a timestamp adjusted for the channel's timezone:

```php
$timestamp = core()->channelTimeStamp($channel);
```

#### Check if SQL date is empty

Validate whether a SQL date field is empty:

```php
$isEmpty = core()->is_empty_date($date);
```

#### Format date using current channel

Format a date according to channel timezone and locale:

```php
$formattedDate = core()->formatDate($date = null, $format = 'd-m-Y H:i:s');
```

### Configuration Management

#### Retrieve configuration data

Get specific configuration values with channel and locale context:

```php
$configValue = core()->getConfigData($field, $channelCode = null, $localeCode = null);
// Delegates to system_config()->getConfigData()
```

#### Get configuration field

Access a specific configuration field structure:

```php
$fieldStructure = core()->getConfigField($fieldName);
// Returns: Configuration field definition array
```

::: tip System Config Integration
The Core class delegates configuration operations to the system configuration manager, providing a unified interface for accessing settings.
:::

### Geographic Data Helpers

#### Get all countries

Retrieve all available countries:

```php
$countries = core()->countries();
```

#### Get country name by code

Get country name using ISO 3166-1 alpha-2 code:

```php
$countryName = core()->country_name($code);
```

#### Get country states

Retrieve all states/provinces for a country:

```php
$states = core()->states($countryCode);
```

#### Get grouped states by countries

Get all states organized by country:

```php
$groupedStates = core()->groupedStatesByCountries();
```

#### Find state by country code

Get specific state information:

```php
$state = core()->findStateByCountryCode($countryCode = null, $stateCode = null);
```

#### Check field requirements

Verify if address fields are required:

```php
$isCountryRequired = core()->isCountryRequired();
$isStateRequired = core()->isStateRequired();
$isPostCodeRequired = core()->isPostCodeRequired();
```

### Customer Management

#### Get guest customer group

Retrieve the guest customer group configuration:

```php
$guestGroup = core()->getGuestCustomerGroup();
```

### Utility Methods

#### Get maximum upload size

Retrieve the maximum file upload size from PHP configuration:

```php
$maxSize = core()->getMaxUploadSize();
// Returns: Value from ini_get('upload_max_filesize')
```

#### Week range calculation

Calculate week start or end dates:

```php
$weekStart = core()->xWeekRange($date, 0); // Week start (Sunday)
$weekEnd = core()->xWeekRange($date, 1);   // Week end (Saturday)
```

Parameters:
- `$date`: Date string to calculate from
- `$day`: 0 for week start, 1 for week end

#### Convert empty strings to null

Clean up array data by converting empty strings to null:

```php
$cleanedArray = core()->convertEmptyStringsToNull($array);
```

#### Create singleton instance

Get singleton instances through the service container:

```php
$instance = core()->getSingletonInstance($className);
```

#### Tax-related helpers

Generate tax rate identifiers for view elements:

```php
$taxIdentifier = core()->taxRateAsIdentifier($taxRate);
// Returns: Tax rate with dots replaced by underscores
```

Get tax category by ID with caching:

```php
$taxCategory = core()->getTaxCategoryById($id);
```

#### Email configuration helpers

Retrieve email sender details:

```php
$shopEmailDetails = core()->getSenderEmailDetails();
$adminEmailDetails = core()->getAdminEmailDetails();
$contactEmailDetails = core()->getContactEmailDetails();
```

Each method returns an array with 'name' and 'email' keys, with automatic fallbacks to configuration defaults.

### Performance and Advanced Features

#### Get speculation rules

Retrieve browser speculation rules for performance optimization:

```php
$speculationRules = core()->getSpeculationRules();
// Returns: Array of prerender and prefetch rules based on configuration
```

This method generates speculation rules for browser performance optimization, including prerender and prefetch configurations.

## Best Practices

When using Bagisto's Core helpers, consider these recommendations:

::: tip Performance
- Cache results of expensive operations like `getAllChannels()` when used repeatedly
- Use specific methods rather than general ones when possible (e.g., `getCurrentChannelCode()` vs `getCurrentChannel()->code`)
:::

::: warning Context Awareness
- Always consider the current channel/locale context when using helpers
- Be aware that some methods may return different results based on the current request context
:::

::: info Error Handling
- Many Core methods return null or empty collections when data is not found
- Always validate return values, especially when working with optional parameters
:::

# Understanding Indexers

When dealing with large volumes of data and retrieving complex information like product variants and pricing, optimizing database queries becomes critical for performance. Bagisto's indexing system provides the solution.

::: info What are Indexers?
Indexers create and maintain specialized data structures optimized for quick information retrieval. They analyze incoming data, extract key metadata, and store it with pointers to original data for lightning-fast searches.
:::

## Benefits of Indexing

### Performance Optimization
- **Fast Data Retrieval**: Eliminates sequential data scanning
- **Optimized Queries**: Reduces database load and response times
- **Scalable Operations**: Handles large datasets efficiently

### Enhanced User Experience
- **Quick Search Results**: Users find products instantly
- **Real-time Updates**: Pricing and inventory reflect immediately
- **Consistent Data**: Synchronized information across all channels

## Essential Indexers in Bagisto

Bagisto implements several specialized indexers to maintain optimal performance across different data types:

### Price Indexing

Price indexing ensures accurate product pricing across your entire storefront, handling complex pricing scenarios efficiently.

::: tip Key Features
- **Real-time Updates**: Prices reflect immediately when changed
- **Rule Integration**: Automatically applies catalog pricing rules
- **Multi-channel Support**: Consistent pricing across all sales channels
:::

**How it Works:**
The price indexing process updates product prices in the database whenever changes occur, ensuring accurate price information across the storefront and maintaining consistency with promotional rules.

### Inventory Indexing

Manages real-time inventory tracking and stock level synchronization across all sales channels.

**Automatic Inventory Management:**
- **Restock Operations**: Quantities update when new stock arrives
- **Return Processing**: Inventory increases when products are returned
- **Real-time Sync**: Inventory levels remain accurate across all touchpoints

::: warning Stock Accuracy
Inventory indexing prevents overselling by maintaining real-time stock levels. Always ensure indexers are running properly to avoid inventory discrepancies.
:::

### Flat Indexing

Optimizes product data retrieval by creating denormalized flat tables for faster query performance.

**Performance Benefits:**
- **Batch Processing**: Handles large datasets efficiently without system overload
- **Attribute Management**: Manages fillable attribute codes during index creation
- **Multi-locale Support**: Accurate indexing for different markets and languages

**Manual Re-indexing:**
When channels or locales change, manually trigger re-indexing to ensure flat tables reflect updates:

```bash
php artisan indexer:index --type=flat --mode=full
```

### Catalog Rule Indexing

Maintains accurate pricing by applying catalog rules, promotions, and discounts consistently across the store.

**Automated Scheduling:**
- **Daily Execution**: Runs automatically at 00:01 every day
- **Rule Validation**: Ensures expired promotions are removed
- **Price Recalculation**: Updates product prices based on active rules
- **Zero Maintenance**: No manual intervention required

::: info Scheduling Details
The catalog rule indexer runs daily to ensure promotional pricing remains accurate. This prevents expired offers from displaying incorrect prices.
:::

### Elasticsearch Integration

Leverages Elasticsearch's powerful search capabilities to provide fast, scalable product search functionality.

**Advanced Search Features:**
- **Full-text Search**: Comprehensive product content indexing
- **Faceted Navigation**: Dynamic filtering and categorization
- **Autocomplete**: Real-time search suggestions
- **Analytics**: Search performance and user behavior insights

::: tip Performance Impact
Elasticsearch can handle millions of products while maintaining sub-second search response times. Perfect for large catalogs and complex search requirements.
:::

**Configuration Reference:**
For detailed Elasticsearch setup, see [Configure Elasticsearch](../performance/configure-elasticsearch) guide.

## Managing Indexers

### Re-indexing Commands

The `indexer:index` console command provides flexible re-indexing capabilities to maintain optimal data performance.

#### Command Syntax

```bash
php artisan indexer:index {--type=*} {--mode=*}
```

**Parameters:**
- `--type`: Specifies which indexers to reindex (optional)
- `--mode`: Sets reindexing mode - `full` or `selective` (default: selective)

### Common Re-indexing Operations

#### Full Re-indexing (All Types)
```bash
# Rebuilds all indexes completely
php artisan indexer:index --mode=full
```

::: tip When to Use Full Re-indexing
Use full re-indexing after major data imports, structural changes, or when troubleshooting index corruption issues.
:::

#### Selective Re-indexing (Specific Type)
```bash
# Re-index only price data
php artisan indexer:index --type=price

# Re-index only inventory data  
php artisan indexer:index --type=inventory

# Re-index only flat tables
php artisan indexer:index --type=flat
```

### Automated Scheduling

Bagisto automatically schedules critical indexers to maintain data accuracy:

| **Indexer** | **Schedule** | **Purpose** |
|---|---|---|
| **Price Indexer** | Daily at 00:01 | Updates product pricing |
| **Catalog Rules** | Daily at 00:01 | Applies promotional pricing |

```php
// Scheduled commands in Laravel
$schedule->command('indexer:index --type=price')->dailyAt('00:01');
$schedule->command('product:price-rule:index')->dailyAt('00:01');
```

::: warning Production Requirement
For automated scheduling to work in production, ensure you have added the Laravel scheduler cron entry to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Without this cron entry, the automated indexer scheduling will not function properly.
:::

## Practical Implementation

Understanding indexers conceptually is important, but seeing how they work in real Bagisto code provides valuable insights for developers working with the system.

### How Indexers Work in Practice

Bagisto uses event listeners to automatically trigger indexing when products are created, updated, or deleted. Here's how the system maintains data consistency:

::: info Example Use Case
The following examples demonstrate indexing implementation from the **Product Listener** - one of many indexing scenarios in Bagisto. Similar indexing patterns are used throughout the system.
:::

#### Automatic Index Updates

```php
// Product Listener Example - Real Bagisto Implementation
public function afterCreate($product)
{
    // Refresh flat index immediately
    $this->flatIndexer->refresh($product);
    
    // Get all related product IDs (variants, bundles, grouped)
    $productIds = $this->getAllRelatedProductIds($product);
    
    // Queue Elasticsearch indexing
    UpdateCreateElasticSearchIndexJob::dispatch($productIds);
}
```

#### Chained Index Operations

When products are updated, multiple indexers run in sequence to maintain data consistency:

```php
public function afterUpdate($product)
{
    // Update flat index first
    $this->flatIndexer->refresh($product);
    
    $productIds = $this->getAllRelatedProductIds($product);
    
    // Chain indexing jobs for optimal performance
    Bus::chain([
        new UpdateCreateInventoryIndexJob($productIds),
        new UpdateCreatePriceIndexJob($productIds), 
        new UpdateCreateElasticSearchIndexJob($productIds),
    ])->dispatch();
}
```

### Event-Driven Indexing

::: info Automatic Updates
Bagisto automatically triggers indexing through Laravel events:
- **Product Created**: Flat and Elasticsearch indexes update
- **Product Updated**: Inventory, Price, and Elasticsearch indexes update in sequence  
- **Product Deleted**: Elasticsearch index removes product data
:::

### Performance Optimization Strategies

Before diving into optimization strategies, it's important to understand that Bagisto's indexers are designed to keep your storefront responsive and data accurate, even as your catalog grows. By leveraging event-driven updates, background job queues, and batch processing, Bagisto ensures that indexing operations do not impact the user experience or slow down your application.

#### Job Queuing
```php
// Jobs are queued to prevent blocking user interactions
UpdateCreateElasticSearchIndexJob::dispatch($productIds);

// Chained jobs ensure proper sequence
Bus::chain([
    new UpdateCreateInventoryIndexJob($productIds),
    new UpdateCreatePriceIndexJob($productIds),
])->dispatch();
```

#### Batch Processing
```php
// Process multiple products efficiently
$productIds = [1, 2, 3, 4, 5]; // Multiple product IDs
UpdateCreatePriceIndexJob::dispatch($productIds);
```

::: tip Development Best Practices
- **Queue Workers**: Ensure queue workers are running for background indexing
- **Error Handling**: Monitor failed jobs and implement retry mechanisms
- **Performance Testing**: Test indexing performance with large product datasets
- **Event Monitoring**: Log indexing events for debugging and optimization
:::

### Monitoring Index Health

Before relying on indexers in a production environment, it's important to monitor their health and ensure all background processes are running smoothly. Regular checks help prevent data inconsistencies and performance bottlenecks.

#### Check Queue Status
```bash
# Monitor indexing job queues
php artisan queue:work --queue=default

# Check failed indexing jobs
php artisan queue:failed
```

#### Debug Index Issues
```bash
# Clear failed jobs and retry
php artisan queue:retry all

# Monitor real-time indexing
php artisan queue:listen --verbose
```

# Understanding Data Transfer

Bagisto's data transfer system enables seamless bulk data import operations directly from the admin panel under the **Settings Menu**. This powerful feature allows efficient management of large datasets through custom import functionality.

::: info What is Data Transfer?
Data transfer in Bagisto provides a structured way to import bulk data (products, customers, tax rates, etc.) using various file formats. It includes validation, batch processing, and error handling for reliable data imports.
:::

## Implementation Guide

This step-by-step guide demonstrates how to create a custom importer for your Bagisto package. For this example, we'll build an **Admin Import** functionality to showcase the implementation process.

::: info Example Scenario
We'll create an admin user importer for a custom package called `AdminImport`. Admin imports are ideal for demonstration because they involve a single table structure, making the concept easier to understand before implementing more complex import logic.
:::

### Create Importer File

Start by creating an `Importer.php` file under the `Helpers/Importers` directory of your package:

```
└── packages
    └── Webkul
        └── AdminImport
            ├── ...
            └── src
                └── ...
                └── Importers
                    └── AdminImporter.php
```

::: tip Directory Structure
The importer file should be placed directly in the `Importers` directory and named appropriately for your import type (e.g., `AdminImporter.php` for admin imports).
:::

### Implement Importer Logic

Create the importer class by extending `AbstractImporter` and implementing the required validation and processing methods:

```php
<?php

namespace Webkul\AdminImport\Importers;

use Illuminate\Support\Facades\Event;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;

class AdminImporter extends AbstractImporter
{
    /**
     * Permanent entity columns.
     *
     * @var string[]
     */
    protected $permanentAttributes = ['email'];

    /**
     * Valid column names.
     */
    protected array $validColumnNames = ['name', 'email', 'password', 'status', 'role_id'];

    /**
     * Validate data row.
     */
    public function validateRow(array $rowData, int $rowNumber): bool
    {
        // Your validation logic here.

        return true;
    }

    /**
     * Import data rows.
     */
    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        // Your import logic here.

        /**
         * Update import batch summary.
         */
        $batch = $this->importBatchRepository->update([
            'state' => Import::STATE_PROCESSED,

            'summary'      => [
                'created' => $this->getCreatedItemsCount(),
                'updated' => $this->getUpdatedItemsCount(),
                'deleted' => $this->getDeletedItemsCount(),
            ],
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.import.after', $batch);
        
        return true;
    }
}
```

#### Key Implementation Details

The `AdminImporter` class contains several important components that work together to handle the data import process. Understanding these properties and methods will help you customize the importer for your specific requirements.

##### Essential Properties

**`$permanentAttributes`** - Fields that cannot be modified during updates
- In this example, `email` is permanent to maintain admin identity
- These fields are used for identifying existing records

**`$validColumnNames`** - Allowed CSV column headers
- Defines which columns are acceptable in the import file
- Helps validate file structure before processing

##### Core Methods

**`validateRow()`** - Validates each CSV row
- Implement your business logic validation here
- Check required fields, data formats, and business rules
- Return `true` for valid rows, `false` for invalid

**`importBatch()`** - Processes the validated data
- Contains the actual import logic
- Create or update database records
- Handle Laravel events for hooks
- Update batch summary with results

::: tip Implementation Strategy
Start with basic validation and import logic, then gradually add more sophisticated features like password hashing, role validation, and duplicate detection as needed.
:::

### Register Custom Importer

After implementing the importer logic, you need to register it with Bagisto's data transfer system to make it available in the admin panel.

#### Create Importer Configuration

Create a configuration file to define your custom importer settings:

**File:** `packages/Webkul/AdminImport/src/Config/importers.php`

```php
<?php

return [
    'admins' => [
        'title'    => 'Admin Users', // add translation key if needed
        'importer' => 'Webkul\AdminImport\Importers\AdminImporter',

        'sample_paths' => [
            'csv'  => 'data-transfer/samples/csv/admins.csv',
            'xls'  => 'data-transfer/samples/xls/admins.xls',
            'xlsx' => 'data-transfer/samples/xlsx/admins.xlsx',
            'xml'  => 'data-transfer/samples/xml/admins.xml',
        ],
    ],
];
```

::: tip Configuration Options
- **title**: Display name shown in the admin panel dropdown
- **importer**: Full class name of your importer
- **sample_paths**: Optional sample file paths for different formats
:::

#### Register Configuration in Service Provider

Update your service provider to merge the importer configuration:

**File:** `packages/Webkul/AdminImport/src/Providers/AdminImportServiceProvider.php`

```php
<?php

namespace Webkul\AdminImport\Providers;

use Illuminate\Support\ServiceProvider;

class AdminImportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/Config/importers.php', 'importers');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
```

#### Verify Registration

After completing the registration steps:

1. **Clear Config Cache**: Run `php artisan config:clear` to refresh configuration
2. **Check Admin Panel**: Navigate to **Settings → Data Transfer → Import**
3. **Verify Dropdown**: Your "Admin Users" option should appear in the importer dropdown

::: info Admin Panel Location
The custom importer will be available at: **Admin Panel → Settings → Data Transfer → Import → Select Type → Admin Users**
:::

#### Implementing Row Validation

Now that you have the basic importer structure, you need to add actual validation and import logic. Without these implementations, the import process will run successfully but won't validate data or insert records into the database.

Let's start by implementing the `validateRow()` method. This method uses Laravel's validation system and Bagisto's built-in error handling to ensure data integrity.

##### Understanding the Validation Process

The validation process follows these key principles:

- **Laravel Validator**: Uses standard Laravel validation rules for consistency
- **Error Tracking**: Failed rows are tracked using the `skipRow()` method
- **Error Export**: Invalid rows are exported to a separate CSV file with error messages
- **Row Status**: The method returns whether the row passed validation

::: tip Validation Best Practices
Always call the `skipRow()` method when validation fails. This ensures proper error tracking and allows users to download a report of failed imports with specific error messages.
:::

##### Validation Implementation

```php
/**
 * Validates row.
 */
public function validateRow(array $rowData, int $rowNumber): bool
{
    $validator = Validator::make($rowData, [
        'name'     => 'required',
        'email'    => 'required|email',
        'password' => 'required',
        'status'   => 'required',
        'role_id'  => 'required',
    ]);

    if ($validator->fails()) {
        $failedAttributes = $validator->failed();

        foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
            $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

            $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
        }
    }

    return ! $this->errorHelper->isRowInvalid($rowNumber);
}
```

##### Validation Flow Explained

1. **Create Validator**: Laravel validator checks each field against defined rules
2. **Handle Failures**: When validation fails, extract error details
3. **Track Errors**: Use `skipRow()` to record failed rows with specific error messages
4. **Return Status**: Return boolean indicating whether the row is valid

::: info Error Handling
The `AbstractImporter` class provides error handling utilities. For detailed implementation patterns, refer to other Bagisto importers or examine the abstract class methods.
:::

#### Implementing Batch Import

After successful validation, you can proceed with the actual data import process. The `importBatch()` method handles the core logic for inserting, updating, or deleting records in the database.

You have flexibility in choosing your data persistence approach - whether using repositories, Eloquent models, or direct database operations. Select the method that best fits your application's architecture and requirements.

##### Import Process Overview

The batch import process follows these key steps:

- **Event Dispatch**: Triggers before/after events for extensibility
- **Action Handling**: Supports create, update, and delete operations
- **Data Processing**: Iterates through validated batch data
- **Database Operations**: Performs bulk inserts/updates for efficiency
- **Summary Tracking**: Maintains counts for reporting

::: tip Database Operations
The example below demonstrates direct database insertion for simplicity. In production, consider using repositories or Eloquent models for better maintainability and to leverage Laravel features like observers and events.
:::

##### Batch Import Implementation

```php
/**
 * Import data rows.
 */
public function importBatch(ImportBatchContract $batch): bool
{
    Event::dispatch('data_transfer.imports.batch.import.before', $batch);

    if ($batch->import->action == Import::ACTION_DELETE) {
        // Deletion logic can be implemented here if needed.
    } else {
        foreach ($batch->data as $rowData) {
            // You can check for existing admin by email and prepare update data if needed.
            $adminData['insert'][$rowData['email']] = array_merge($rowData, [
                'created_at' => $rowData['created_at'] ?? now(),
                'updated_at' => $rowData['updated_at'] ?? now(),
            ]);
        }

        if (! empty($adminData['update'])) {
            $this->updatedItemsCount += count($adminData['update']);

            // Update logic can be implemented here if needed.
        }

        if (! empty($adminData['insert'])) {
            $this->createdItemsCount += count($adminData['insert']);

            DB::table('admins')->insert(array_values($adminData['insert']));
        }
    }

    /**
     * Update import batch summary.
     */
    $batch = $this->importBatchRepository->update([
        'state' => Import::STATE_PROCESSED,

        'summary'      => [
            'created' => $this->getCreatedItemsCount(),
            'updated' => $this->getUpdatedItemsCount(),
            'deleted' => $this->getDeletedItemsCount(),
        ],
    ], $batch->id);

    Event::dispatch('data_transfer.imports.batch.import.after', $batch);
    
    return true;
}
```

##### Implementation Flow Explained

1. **Pre-Import Event**: Dispatches event for any pre-processing hooks
2. **Action Check**: Determines whether to perform create, update, or delete operations
3. **Data Iteration**: Processes each validated row from the batch
4. **Record Preparation**: Organizes data for bulk operations and adds timestamps
5. **Database Operations**: Executes bulk inserts/updates for performance
6. **Count Tracking**: Updates internal counters for summary reporting
7. **State Update**: Marks batch as processed with operation summary
8. **Post-Import Event**: Dispatches event for any post-processing hooks

::: info Performance Considerations
- **Bulk Operations**: Use bulk inserts/updates instead of individual operations
- **Memory Management**: Process large batches in chunks if memory is limited
- **Transaction Handling**: Consider wrapping operations in database transactions
- **Event Optimization**: Be mindful of event listeners that might slow down processing
:::

#### Complete Directory Structure

Your final package structure should look like this:

```
└── packages
    └── Webkul
        └── AdminImport
            ├── src
            │   ├── Config
            │   │   └── importers.php
            │   ├── Importers
            │   │   └── AdminImporter.php
            │   └── Providers
            │       └── AdminImportServiceProvider.php
            └── composer.json
```

### Supported File Formats

Bagisto's data transfer system supports multiple file formats for flexible import operations:

| **Format** | **Extension** | **Use Case** | **Features** |
|---|---|---|---|
| **CSV** | `.csv` | Large datasets | Lightweight, fast processing |
| **Excel** | `.xlsx` | Formatted data | Rich formatting, multiple sheets |
| **Excel Legacy** | `.xls` | Legacy systems | Backward compatibility |
| **XML** | `.xml` | Structured data | Hierarchical data, validation |

::: info File Size Recommendations
- **CSV**: Best for files > 10MB or > 50,000 records
- **Excel**: Ideal for files < 5MB with complex formatting
- **XML**: Perfect for structured data with relationships
:::

## Conclusion

You have successfully learned how to implement custom data transfer functionality in Bagisto. This comprehensive guide covered the complete process from creating importer classes to registering them with the admin panel.

### Key Takeaways

- **Custom Importers**: Extend `AbstractImporter` to create specialized import functionality
- **Validation System**: Leverage Laravel's validation with proper error tracking and reporting
- **Batch Processing**: Implement efficient bulk operations for large dataset imports
- **Event Integration**: Use Laravel events for extensible import workflows
- **Admin Integration**: Register importers through configuration for seamless admin panel access

### Next Steps

With your custom importer implementation complete, consider these enhancements:

- **Sample Files**: Create sample CSV/Excel files for user guidance
- **Advanced Validation**: Implement business-specific validation rules
- **Error Recovery**: Add mechanisms for handling and retrying failed imports
- **Audit Logging**: Track import activities for compliance and debugging

::: tip Best Practices
- Always test with various file sizes and formats
- Implement proper error handling and user feedback
- Consider memory optimization for large imports
- Document your custom fields and validation rules
- Provide clear sample files for end users
:::

For more advanced data transfer scenarios, explore the existing Bagisto importers in the core package for additional implementation patterns and optimization techniques.

# Event Listeners

Event Listeners in Bagisto provide a powerful way to extend and customize the platform's functionality without modifying core code. Bagisto uses Laravel's event system with string-based event dispatching, making it simple to hook into various points in the application lifecycle.

This advanced guide covers how to implement comprehensive event-driven architecture in your Bagisto applications, including listening to core events, creating custom events, and building reactive systems.

## What You'll Learn

- [Understanding Bagisto's Event System](#understanding-bagisto-s-event-system)
- [Dispatching Custom Events](#dispatching-events)  
- [Creating Event Listeners](#creating-event-listeners)
- [Advanced Event Patterns](#advanced-event-patterns)
- [Best Practices](#best-practices)
- [Available Events Reference](#available-bagisto-events)

## Understanding Bagisto's Event System

Bagisto dispatches events throughout its operations using string identifiers. This approach allows for:

- **Loose Coupling**: Components can communicate without direct dependencies
- **Extensibility**: Third-party packages can hook into core functionality
- **Maintainability**: Changes can be made without affecting existing code
- **Scalability**: Event-driven architecture supports complex business logic

## Dispatching Events

Events are dispatched using `Event::dispatch()` with string identifiers:

```php{13,19}
<?php

namespace Webkul\RMA\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;

class RMAController extends Controller
{
    public function processReturnRequest()
    {
        // Dispatch before event
        Event::dispatch('rma.return.request.before', request()->all());

        // Perform main operation - create return request
        $returnRequest = $this->createReturnRequest();

        // Dispatch after event with result
        Event::dispatch('rma.return.request.created', $returnRequest);

        return response()->json(['status' => 'success', 'data' => $returnRequest]);
    }
}
```

### Event Naming Convention

Follow Bagisto's hierarchical naming convention for consistency:

| Event Type | Pattern | Example | Use Case |
|------------|---------|---------|-----------|
| **Core Events** | `{module}.{entity}.{action}.{timing}` | `catalog.product.create.after` | Built-in Bagisto operations |
| **Package Events** | `{package}.{feature}.{action}.{timing}` | `rma.return.request.created` | Custom package functionality |
| **Integration Events** | `{system}.{integration}.{action}.{status}` | `payment.paypal.transaction.failed` | Third-party integrations |

**Event Naming Best Practices:**

```php
// ✅ Good - Clear and descriptive
Event::dispatch('rma.return.request.created', $returnRequest);
Event::dispatch('rma.return.item.approved', $returnItem);
Event::dispatch('rma.refund.processed', $refund);

// ❌ Avoid - Vague or inconsistent
Event::dispatch('rma.something.happened', $data);
Event::dispatch('return_created', $return);
```

## Creating Event Listeners

To create an event listener in Bagisto, you need to define a listener class with a method that will handle the event. This method receives the event data as its argument. You can then register this listener to respond to specific events, allowing you to execute custom logic whenever those events are fired.

Let's say you are having a package like **RMA (Return Merchandise Authorization)** or some other name - let's use RMA for this practical example. This RMA package would listen to order events to manage returns effectively.

::: tip Package Development Reference
If you want to build a package, check out our [Package Development Guide](/package-development/getting-started) where we have shown how to build an RMA package step by step. This guide covers the basics of creating packages, service providers, and directory structure before implementing event listeners.
:::

### Basic Event Listener

In an RMA package, you would have an event listener to handle order events:

```php{9-16,18-24}
<?php

namespace Webkul\RMA\Listeners;

use Illuminate\Support\Facades\Log;

class RMAOrderListener
{
    public function handleOrderCreated($order): void
    {
        // Create RMA eligibility record for the order
        Log::info('Order created - checking RMA eligibility', ['order_id' => $order->id]);
        
        // Check if order items are eligible for returns
        $this->createRMAEligibilityForOrder($order);
    }

    public function handleOrderStatusUpdate($order): void
    {
        // Update RMA status based on order status changes
        if ($order->status === 'delivered') {
            $this->activateReturnWindow($order);
        }
    }

    private function createRMAEligibilityForOrder($order): void
    {
        // Implementation logic for RMA eligibility
    }

    private function activateReturnWindow($order): void
    {
        // Start 30-day return window
    }
}
```

::: details Method Explanations
- **`handleOrderCreated()`**: Creates RMA eligibility records when new orders are placed
- **`handleOrderStatusUpdate()`**: Manages RMA status changes based on order status
- **`createRMAEligibilityForOrder()`**: Business logic for determining return eligibility
- **`activateReturnWindow()`**: Starts the countdown for return requests
:::

### Event Service Provider

In your RMA package, you would register the listeners in the `EventServiceProvider`:

```php{15-24}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\RMA\Listeners\RMAOrderListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, array<int, string>>>
     */
    protected $listen = [
        // Listen to core Bagisto order events
        'checkout.order.save.after' => [
            [RMAOrderListener::class, 'handleOrderCreated'],
        ],

        'sales.order.update-status.after' => [
            [RMAOrderListener::class, 'handleOrderStatusUpdate'],
        ],
    ];
}
```

::: info Modern Event Registration
The `[ClassName::class, 'method']` syntax is the modern Laravel approach for registering event listeners. This provides better IDE support and refactoring capabilities compared to string-based registration.
:::

::: tip RMA Package Registration
Your package may not have an `EventServiceProvider` initially. If so, you would need to register the `EventServiceProvider` in the package's main service provider:

```php
// In Webkul\RMA\Providers\RMAServiceProvider
public function register(): void
{
    $this->app->register(EventServiceProvider::class);
}
```

This ensures all your RMA event listeners are properly loaded when the package is installed.
:::

## Advanced Event Patterns

Advanced event patterns in Bagisto allow you to build more flexible and modular systems. You can chain events, trigger custom events from listeners, and even use queued listeners for asynchronous processing. This enables you to decouple business logic, improve maintainability, and handle complex workflows such as notifications, analytics, or integrations with external services.

### Multiple Listeners for Single Event

You can register multiple listeners for the same event to separate concerns:

```php{4-6}
protected $listen = [
    'checkout.order.save.after' => [
        [RMAOrderListener::class, 'handleOrderCreated'],
        [NotificationListener::class, 'sendOrderConfirmation'],
        [InventoryListener::class, 'updateStockLevels'],
        [AnalyticsListener::class, 'trackOrderMetrics'],
    ],
];
```

### Event Priority and Ordering

Listeners execute in the order they're registered. Place critical listeners first:

```php{3-6}
protected $listen = [
    'sales.order.update-status.after' => [
        // Critical: Update RMA status first
        [RMAStatusListener::class, 'updateReturnEligibility'],
        // Secondary: Send notifications
        [NotificationListener::class, 'notifyCustomer'],
    ],
];
```

## Practical Example: Complete RMA Integration

Here's a complete example showing how to integrate RMA functionality using event listeners:

::: code-group

```php [RMAOrderListener.php]
<?php

namespace Webkul\RMA\Listeners;

use Webkul\RMA\Services\RMAService;
use Illuminate\Support\Facades\Log;

class RMAOrderListener
{
    public function __construct(
        private RMAService $rmaService
    ) {}

    public function handleOrderCreated($order): void
    {
        try {
            $this->rmaService->createEligibilityRecords($order);
            
            Log::info('RMA eligibility created for order', [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create RMA eligibility', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function handleOrderDelivered($order): void
    {
        $this->rmaService->activateReturnWindow($order);
        
        // Dispatch custom RMA event
        event('rma.return.window.activated', $order);
    }
}
```

```php [EventServiceProvider.php]
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\RMA\Listeners\RMAOrderListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Core Bagisto events
        'checkout.order.save.after' => [
            [RMAOrderListener::class, 'handleOrderCreated'],
        ],
        
        'sales.order.update-status.after' => [
            [RMAOrderListener::class, 'handleOrderDelivered'],
        ],
        
        // Custom RMA events
        'rma.return.window.activated' => [
            [RMANotificationListener::class, 'sendReturnEligibilityEmail'],
        ],
    ];
}
```

:::

## Best Practices

By following best practices, you can ensure your event-driven code is robust, easy to extend, and integrates smoothly with both core and custom features.

### Performance Considerations

Keep these performance guidelines in mind when implementing event listeners:

- **Keep listeners lightweight**: Avoid heavy computations in event listeners
- **Use queues for heavy operations**: Dispatch time-consuming tasks to background queues
- **Handle exceptions gracefully**: Wrap listener logic in try-catch blocks
- **Log important events**: Use structured logging for debugging and monitoring

### Event Naming Guidelines

Follow these naming conventions for consistent and maintainable event-driven architecture:

- Use consistent hierarchical patterns (`module.entity.action.timing`)
- Fire both `before` and `after` events for major operations
- Include descriptive action names (`created`, `updated`, `deleted`)

### Data Handling Best Practices

Ensure robust data management in your event listeners:

- Validate event data before processing
- Pass relevant context in event payloads
- Avoid circular event dependencies

### Error Handling Strategies

Implement comprehensive error handling to maintain system stability:

- Implement proper exception handling in listeners
- Log errors with sufficient context for debugging
- Consider fallback mechanisms for critical operations

**Example Error Handling:**

```php{5-14}
public function handleOrderCreated($order): void
{
    try {
        $this->createRMAEligibilityForOrder($order);
    } catch (\Exception $e) {
        Log::error('Failed to create RMA eligibility', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Don't break the order creation process
        // Consider alternative handling or notification
    }
}
```

## Available Bagisto Events

The following table lists the core events available in Bagisto that you can listen to:

| Events Name                                          | Functionality                                                     | Arguments                 |
| ---------------------------------------------------- | ----------------------------------------------------------------- | ------------------------- |
| `catalog.attribute.create.before`                    | This event will be fired before attribute gets created.           | -                         |
| `catalog.attribute.create.after`                     | This event will be fired after attribute gets created.            | `attribute`               |
| `catalog.attribute.update.before`                    | This event will be fired before attribute gets updated.           | -                         |
| `catalog.attribute.update.after`                     | This event will be fired after attribute gets updated.            | `$attribute`              |
| `catalog.attribute.delete.before`                    | This event will be fired before attribute gets deleted.           | `$id`                     |
| `catalog.attribute.delete.after`                     | This event will be fired after attribute gets deleted.            | `$id`                     |
| `catalog.attribute_family.create.before`             | This event will be fired before attribute family gets created.    | -                         |
| `catalog.attribute_family.create.after`              | This event will be fired after attribute family gets created.     | `attribute_family`        |
| `catalog.attribute_family.update.before`             | This event will be fired before updating attribute family.        | `$id`                     |
| `catalog.attribute_family.update.after`              | This event will be fired after updating attribute family.         | `$attributeFamily`        |
| `catalog.attribute_family.delete.before`             | This event will be fired before deleting attribute family.        | `$id`                     |
| `catalog.attribute_family.delete.after`              | This event will be fired after deleting attribute family.         | `$id`                     |
| `catalog.category.create.before`                     | This event will be fired before creating category.                | -                         |
| `catalog.category.create.after`                      | This event will be fired after creating category.                 | `$category`               |
| `catalog.category.update.before`                     | This event will be fired before updating category.                | `$id`                     |
| `catalog.category.update.after`                      | This event will be fired after updating category.                 | `$category`               |
| `catalog.category.delete.before`                     | This event will be fired before deleting category.                | `$id`                     |
| `catalog.category.delete.after`                      | This event will be fired after deleting category.                 | `$id`                     |
| `catalog.categories.mass-update.before`              | This event will be fired before bulk category update.             | `$categoryId`             |
| `catalog.categories.mass-update.after`               | This event will be fired after bulk category update.              | `$category`               |
| `catalog.product.create.before`                      | This event will be fired before product gets created.             | -                         |
| `catalog.product.create.after`                       | This event will be fired after product gets created.              | `$product`                |
| `catalog.product.update.before`                      | This event will be fired before product gets updated.             | `$id`                     |
| `catalog.product.update.after`                       | This event will be fired after product gets updated.              | `$product`                |
| `catalog.product.delete.before`                      | This event will be fired before product gets deleted.             | `$id`                     |
| `catalog.product.delete.after`                       | This event will be fired after product gets deleted.              | `$id`                     |
| `products.datagrid.sync`                             | This event will be fired to synicing datagrid product.            | `true`                    |
| `cms.page.create.before`                             | This event will be fired before cms page gets created.            | -                         |
| `cms.page.create.after`                              | This event will be fired after cms page gets created.             | `$page`                   |
| `cms.page.update.before`                             | This event will be fired before cms page gets updated.            | `$id`                     |
| `cms.page.update.after`                              | This event will be fired after cms page gets updated.             | `$page`                   |
| `cms.page.delete.before`                             | This event will be fired before cms page gets deleted.            | `$id`                     |
| `cms.page.delete.after`                              | This event will be fired after cms page gets deleted.             | `id`                      |
| `customer.addresses.create.before`                   | This event will be fired before customer address gets created.    | -                         |
| `customer.addresses.create.after`                    | This event will be fired after customer address gets created.     | `$address`                |
| `customer.addresses.update.before`                   | This event will be fired before customer address gets updated.    | `$id`                     |
| `customer.addresses.update.after`                    | This event will be fired after customer address gets updated.     | `$address`                |
| `customer.addresses.delete.before`                   | This event will be fired before customer address gets deleted.    | `$id`                     |
| `customer.addresses.delete.after`                    | This event will be fired after customer address gets deleted.     | `$id`                     |
| `customer.registration.before`                       | This event will be fired before customer gets created.            | -                         |
| `customer.registration.after`                        | This event will be fired after customer gets created.             | -                         |
| `customer.update.before`                             | This event will be fired before customer gets updated.            | `$id`                     |
| `customer.update.after`                              | This event will be fired after customer gets updated.             | `$customer`               |
| `customer.password.update.after`                     | This event will be fired after customer password gets updated.    | `$customer`               |
| `customer.note.create.before`                        | This event will be fired before customer note gets created.       | `$id`                     |
| `customer.note.create.after`                         | This event will be fired after customer note gets created.        | `$customerNote`           |
| `customer.subscription.before`                       | This event will be fired before customer gets subscription.       | -                         |
| `customer.subscription.after`                        | This event will be fired after customer gets subscription.        | `$subscription`           |
| `customer.after.login`                               | This event will be fired after customer login.                    | `auth()->guard()->user()` |
| `customer.delete.before`                             | This event will be fired before customer gets deleted.            | `$customer`               |
| `customer.delete.after`                              | This event will be fired after customer gets deleted.             | `$customer`               |
| `customer.customer_group.create.before`              | This event will be fired before customer group gets created.      | -                         |
| `customer.customer_group.create.after`               | This event will be fired after customer group gets created.       | `$customerGroup`          |
| `customer.customer_group.update.before`              | This event will be fired before customer group gets updated.      | `$id`                     |
| `customer.customer_group.update.after`               | This event will be fired after customer group gets updated.       | `$customerGroup`          |
| `customer.customer_group.delete.before`              | This event will be fired before customer group gets deleted.      | `$id`                     |
| `customer.customer_group.delete.after`               | This event will be fired after customer group gets deleted.       | `$id`                     |
| `customer.review.update.before`                      | This event will be fired before customer review gets updated.     | `$id`                     |
| `customer.review.update.after`                       | This event will be fired after customer review gets updated.      | `$review`                 |
| `customer.review.delete.before`                      | This event will be fired before customer review gets deleted.     | `$id`                     |
| `customer.review.delete.after`                       | This event will be fired after customer review gets deleted.      | `$id`                     |
| `customer.account.gdpr-request.create.before`        | This event will be fired before gdpr request created.             | -                         |
| `customer.gdpr-request.create.after`                 | This event will be fired after gdpr request created.              | `$gdprRequest`            |
| `customer.account.gdpr-request.update.before`        | This event will be fired before gdpr request updated.             | -                         |
| `customer.account.gdpr-request.update.after`         | This event will be fired after gdpr request updated.              | `$gdprRequest`            | 
| `customer.gdpr-request.update.after`                 | This event will be fired after gdpr request updated.              | `$gdprRequest`            | 
| `customer.gdpr-request.update.before`                | This event will be fired after gdpr request updated.              | -                         | 
| `marketing.search_seo.sitemap.create.before`         | This event will be fired before sitemaps gets created.            | -                         |
| `marketing.search_seo.sitemap.create.after`          | This event will be fired after sitemaps gets created.             | `$sitemap`                |
| `marketing.search_seo.sitemap.update.before`         | This event will be fired before sitemaps gets updated.            | `$id`                     |
| `marketing.search_seo.sitemap.update.after`          | This event will be fired after sitemaps gets updated.             | `$sitemap`                |
| `marketing.search_seo.sitemap.delete.before`         | This event will be fired before sitemaps gets deleted.            | `$id`                     |
| `marketing.search_seo.sitemap.delete.after`          | This event will be fired after sitemaps gets deleted.             | `$id`                     |
| `marketing.search_seo.search_synonyms.create.before` | This event will be fired before search synonyms created           | -                         |
| `marketing.search_seo.search_synonyms.create.after`  | This event will be fired after search synonyms created            | `$searchSynonym`          |
| `marketing.search_seo.search_synonyms.update.before` | This event will be fired before synonyms gets updated.            | `$id`                     |
| `marketing.search_seo.search_synonyms.update.after`  | This event will be fired after synonyms gets updated.             | `$searchSynonym`          |
| `marketing.search_seo.search_synonyms.delete.before` | This event will be fired before synonyms gets deleted.            | `$id`                     |
| `marketing.search_seo.search_synonyms.delete.after`  | This event will be fired before synonyms gets deleted.            | `$id`                     |
| `marketing.search_seo.search_terms.create.before`    | This event will be fired before search search terms created       | -                         |
| `marketing.search_seo.search_terms.create.after`     | This event will be fired after search search terms created        | `$searchTerm`             |
| `marketing.search_seo.search_terms.update.before`    | This event will be fired before search search terms updated       | `$id`                     |
| `marketing.search_seo.search_terms.update.after`     | This event will be fired after search search terms updated        | `$searchTerm`             |
| `marketing.search_seo.search_terms.delete.before`    | This event will be fired before search search terms gets deleted  | `$id`                     |
| `marketing.search_seo.search_terms.delete.after`     | This event will be fired after search search terms gets deleted   | `$id`                     |
| `marketing.search_seo.url_rewrites.create.before`    | This event will be fired before search url rewrites gets created  | -                         |
| `marketing.search_seo.url_rewrites.create.after`     | This event will be fired after search url rewrites gets created   | `$urlRewrite`             |
| `marketing.search_seo.url_rewrites.update.before`    | This event will be fired before search url rewrites gets updated  | `$id`                     |
| `marketing.search_seo.url_rewrites.update.after`     | This event will be fired after search url rewrites gets updated   | `$urlRewrite`             |
| `marketing.search_seo.url_rewrites.delete.before`    | This event will be fired before search url rewrites gets deleted  | `$id`                     |
| `marketing.search_seo.url_rewrites.delete.after`     | This event will be fired after search url rewrites gets deleted   | `$id`                     |
| `marketing.campaigns.create.before`                  | This event will be fired before campaigns gets created.           | -                         |
| `marketing.campaigns.create.after`                   | This event will be fired after campaigns gets created.            | `$campaign`               |
| `marketing.campaigns.update.before`                  | This event will be fired before campaigns gets updated.           | `$id`                     |
| `marketing.campaigns.update.after`                   | This event will be fired after campaigns gets updated.            | `$campaign`               |
| `marketing.campaigns.delete.before`                  | This event will be fired before campaigns gets deleted.           | `$id`                     |
| `marketing.campaigns.delete.after`                   | This event will be fired after campaigns gets deleted.            | `$id`                     |
| `marketing.events.create.before`                     | This event will be fired before marketing event gets created.     | -                         |
| `marketing.events.create.after`                      | This event will be fired after marketing event gets created.      | `$event`                  |
| `marketing.events.update.before`                     | This event will be fired before marketing event gets updated.     | `$id`                     |
| `marketing.events.update.after`                      | This event will be fired after marketing event gets updated.      | `$event`                  |
| `marketing.events.delete.before`                     | This event will be fired before marketing event gets deleted.     | `$id`                     |
| `marketing.events.delete.after`                      | This event will be fired after marketing event gets deleted.      | `$id`                     |
| `marketing.templates.create.before`                  | This event will be fired before templates gets created.           | -                         |
| `marketing.templates.create.after`                   | This event will be fired after templates gets created.            | ` $template`              |
| `marketing.templates.update.before`                  | This event will be fired before templates gets updated.           | `$id`                     |
| `marketing.templates.update.after`                   | This event will be fired after templates gets updated.            | `$template`               |
| `marketing.templates.delete.before`                  | This event will be fired before templates gets deleted.           | `$id`                     |
| `marketing.templates.delete.after`                   | This event will be fired after templates gets deleted.            | `$id`                     |
| `promotions.cart_rule.create.before`                 | This event will be fired before cart rule gets created.           | -                         |
| `promotions.cart_rule.create.after`                  | This event will be fired after cart rule gets created.            | `$cartRule`               |
| `promotions.cart_rule.update.before`                 | This event will be fired before cart rule gets updated.           | `$id`                     |
| `promotions.cart_rule.update.after`                  | This event will be fired after cart rule gets updated.            | `$cartRule`               |
| `promotions.cart_rule.delete.before`                 | This event will be fired before cart rule gets deleted.           | `$id`                     |
| `promotions.cart_rule.delete.after`                  | This event will be fired after cart rule gets deleted.            | `$id`                     |
| `promotions.catalog_rule.create.before`              | This event will be fired before catalog rule gets created.        | -                         |
| `promotions.catalog_rule.create.after`               | This event will be fired after catalog rule gets created.         | `$catalogRule`            |
| `promotions.catalog_rule.update.before`              | This event will be fired before catalog rule gets updated.        | `$id`                     |
| `promotions.catalog_rule.update.after`               | This event will be fired after catalog rule gets updated.         | `$catalogRule`            |
| `promotions.catalog_rule.delete.before`              | This event will be fired before catalog rule gets deleted.        | `$id`                     |
| `promotions.catalog_rule.delete.after`               | This event will be fired after catalog rule gets deleted.         | `$id`                     |
| `sales.order.comment.create.before`                  | This event will be fired before order comment gets created.       | -                         |
| `sales.order.comment.create.after`                   | This event will be fired after order comment gets created.        | `$comment`                |
| `core.channel.create.before`                         | This event will be fired before channel gets created.             | -                         |
| `core.channel.create.after`                          | This event will be fired after channel gets created.              | `$channel`                |
| `core.channel.update.before`                         | This event will be fired before channel gets updated.             | `$id`                     |
| `core.channel.update.after`                          | This event will be fired after channel gets updated.              | `$channel`                |
| `core.channel.delete.before`                         | This event will be fired before channel gets deleted.             | `$id`                     |
| `core.channel.delete.after`                          | This event will be fired after channel gets deleted.              | `$id`                     |
| `core.exchange_rate.create.before`                   | This event will be fired before exchange rate gets created.       | -                         |
| `core.exchange_rate.create.after`                    | This event will be fired after exchange rate gets created.        |                           |
| `core.exchange_rate.update.before`                   | This event will be fired before exchange rate gets updated.       | `request()->id`           |
| `core.exchange_rate.update.after`                    | This event will be fired after exchange rate gets updated.        | `$exchangeRate`           |
| `core.exchange_rate.delete.before`                   | This event will be fired before exchange rate gets deleted.       | `$id`                     |
| `core.exchange_rate.delete.after`                    | This event will be fired after exchange rate gets deleted.        | `$id`                     |
| `inventory.inventory_source.create.before`           | This event will be fired before inventory source gets created.    | -                         |
| `inventory.inventory_source.create.after`            | This event will be fired after inventory source gets created.     | `$inventorySource`        |
| `inventory.inventory_source.update.before`           | This event will be fired before inventory source gets updated.    | `$id`                     |
| `inventory.inventory_source.update.after`            | This event will be fired after inventory source gets updated.     | `$inventorySource`        |
| `inventory.inventory_source.delete.before`           | This event will be fired before inventory source gets deleted.    | `$id`                     |
| `inventory.inventory_source.delete.after`            | This event will be fired after inventory source gets deleted.     | `$id`                     |
| `user.role.create.before`                            | This event will be fired before role gets created.                | -                         |
| `user.role.create.after`                             | This event will be fired after role gets created.                 | `$role`                   |
| `user.role.update.before`                            | This event will be fired before role gets updated.                | `$id`                     |
| `user.role.update.after`                             | This event will be fired after role gets updated.                 | `$role`                   |
| `user.role.delete.before`                            | This event will be fired before role gets deleted.                | `$id`                     |
| `user.role.delete.after`                             | This event will be fired after role gets deleted.                 | `$id`                     |
| `theme_customization.create.before`                  | This event will be fired before theme customization gets created. | -                         |
| `theme_customization.create.after`                   | This event will be fired after theme customization gets created.  | `$id`                     |
| `theme_customization.update.before`                  | This event will be fired before theme customization gets updated. | `$id`                     |
| `theme_customization.update.after`                   | This event will be fired after theme customization gets updated.  | `$theme`                  |
| `theme_customization.delete.before`                  | This event will be fired before theme customization gets deleted. | `$id`                     |
| `theme_customization.delete.after`                   | This event will be fired after theme customization gets deleted.  | `$id`                     |
| `user.admin.create.before`                           | This event will be fired before admin gets created.               | -                         |
| `user.admin.create.after`                            | This event will be fired after admin gets created.                | `$admin`                  |
| `user.admin.update.before`                           | This event will be fired before admin gets updated.               | `$id`                     |
| `user.admin.update.after`                            | This event will be fired after admin gets updated.                | `$admin`                  |
| `admin.password.update.after`                        | This event will be fired after admin password gets updated.       | `$admin`                  |
| `user.admin.delete.before`                           | This event will be fired before admin gets deleted.               | `$id`                     |
| `user.admin.delete.after`                            | This event will be fired after admin gets deleted.                | `$id`                     |
| `tax.category.create.before`                         | This event will be fired before tax category gets created.        | -                         |
| `tax.category.create.after`                          | This event will be fired after tax category gets created.         | `$taxCategory`            |
| `tax.category.update.before`                         | This event will be fired before tax category gets updated.        | `$id`                     |
| `tax.category.update.after`                          | This event will be fired after tax category gets updated.         | `$taxCategory`            |
| `tax.category.delete.before`                         | This event will be fired before tax category gets deleted.        | `$id`                     |
| `tax.category.delete.after`                          | This event will be fired after tax category gets deleted.         | `$id`                     |
| `tax.rate.create.before`                             | This event will be fired before tax rate gets created.            | -                         |
| `tax.rate.create.after`                              | This event will be fired after tax rate gets created.             | `$taxRate`                |
| `tax.rate.update.before`                             | This event will be fired before tax rate gets updated.            | `$id`                     |
| `tax.rate.update.after`                              | This event will be fired after tax rate gets updated.             | `$taxRate`                |
| `tax.rate.delete.before`                             | This event will be fired before tax rate gets deleted.            | `$id`                     |
| `tax.rate.delete.after`                              | This event will be fired after tax rate gets deleted.             | `$id`                     |
| `checkout.cart.delete.before`                        | This event will be fired before cart item gets deleted.           | `$itemId`                 |
| `checkout.cart.delete.after`                         | This event will be fired after cart item gets deleted.            | `$itemId`                 |
| `checkout.cart.add.before`                           | This event will be fired before cart item gets created.           | `$product->id`            |
| `checkout.cart.add.after`                            | This event will be fired after cart item gets created.            | `$this->cart`             |
| `checkout.cart.update.before`                        | This event will be fired before cart item gets updated.           | `$item`                   |
| `checkout.cart.update.after`                         | This event will be fired after cart item gets updated.            | `$item`                   |
| `checkout.cart.collect.totals.before`                | This event will be fired before collecting cart totals.           | `$this->cart`             |
| `checkout.cart.collect.totals.after`                 | This event will be fired after collecting cart totals.            | `$this->cart`             |
| `checkout.cart.calculate.items.tax.before`           | This event will be fired before calculating cart items tax.       | `$this->cart`             |
| `checkout.cart.calculate.items.tax.after`            | This event will be fired after calculating cart items tax.        | `$this->cart`             |
| `core.configuration.save.before`                     | This event will be fired before core configuration gets saved.    | -                         |
| `core.configuration.save.after`                      | This event will be fired after core configuration gets saved.     | -                         |
| `core.currency.create.before`                        | This event will be fired before currency gets created.            | -                         |
| `core.currency.create.after`                         | This event will be fired after currency gets created.             | `$currency`               |
| `core.currency.update.before`                        | This event will be fired before currency gets updated.            | `$id`                     |
| `core.currency.update.after`                         | This event will be fired after currency gets updated.             | `$currency`               |
| `core.currency.delete.before`                        | This event will be fired before currency gets deleted.            | `$id`                     |
| `core.currency.delete.after`                         | This event will be fired after currency gets deleted.             | `$id`                     |
| `core.locale.create.before`                          | This event will be fired before locale gets created.              | -                         |
| `core.locale.create.after`                           | This event will be fired after locale gets created.               | `$locale`                 |
| `core.locale.update.before`                          | This event will be fired before locale gets updated.              | `$id`                     |
| `core.locale.update.after`                           | This event will be fired after locale gets updated.               | `$locale`                 |
| `core.locale.delete.before`                          | This event will be fired before locale gets deleted.              | `$id`                     |
| `core.locale.delete.after`                           | This event will be fired after locale gets deleted.               | `$id`                     |
| `sales.invoice.save.before`                          | This event will be fired before invoice gets saved.               | `$data`                   |
| `sales.invoice.save.after`                           | This event will be fired after invoice gets saved.                | `$invoice`                |
| `checkout.order.save.before`                         | This event will be fired before order gets saved.                 | `[$data]`                 |
| `checkout.order.save.after`                          | This event will be fired after order gets saved.                  | `$order`                  |
| `checkout.order.orderitem.save.before`               | This event will be fired before order item gets saved.            | `$item`                   |
| `checkout.order.orderitem.save.after`                | This event will be fired after order item gets saved.             | `$orderItem`              |
| `sales.order.cancel.before`                          | This event will be fired before order gets canceled.              | `$order`                  |
| `sales.order.cancel.after`                           | This event will be fired after order gets canceled.               | `$order`                  |
| `sales.order.update-status.before`                   | This event will be fired before order status gets updated.        | `$order`                  |
| `sales.order.update-status.after`                    | This event will be fired after order status gets updated.         | `$order`                  |
| `sales.refund.save.before`                           | This event will be fired before order refund gets saved.          | `$data`                   |
| `sales.refund.save.after`                            | This event will be fired after order refund gets saved.           | `$refund`                 |
| `sales.shipment.save.before`                         | This event will be fired before shipment gets saved.              | `$data`                   |
| `sales.shipment.save.after`                          | This event will be fired after shipment gets saved.               | `$shipment`               |
| `checkout.load.index`                                | This event will be fired on checkout page load.                   | -                         |

# View Render Events

View Render Events in Bagisto provide a powerful mechanism for injecting content into blade templates without modifying the core files. Bagisto strategically places these events throughout its templates, creating extension points that allow packages and customizations to seamlessly integrate with the existing UI.

This guide covers how Bagisto's render event system works and how to leverage it in your packages for flexible template customization.

## What You'll Learn

- [Understanding Bagisto's Render Event System](#understanding-bagisto-s-render-event-system)
- [How Bagisto Uses Render Events](#how-bagisto-uses-render-events)
- [Injecting Content with Render Events](#injecting-content-with-render-events)
- [Practical Example: RMA Package Integration](#practical-example-rma-package-integration)
- [Best Practices](#best-practices)

## Understanding Bagisto's Render Event System

Bagisto's render event system allows modules and packages to inject content into predefined locations within blade templates without overriding core template files. This approach maintains upgradability while providing extensive customization capabilities.

### Core Concept

The `view_render_event()` function creates injection points in templates where external content can be rendered:

```blade
{!! view_render_event('event.name.here') !!}
```

When this function is called, Bagisto checks if any listeners are registered for that event and renders their content at that exact location.

## How Bagisto Uses Render Events

Bagisto strategically places render events throughout its blade templates to provide maximum flexibility for customization. Let's examine some real examples from Bagisto's core templates.

### Shop Layout Events

In the main shop layout (`packages/Webkul/Shop/src/Resources/views/components/layouts/index.blade.php`):

```blade{7,11,18,22,25}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {!! view_render_event('bagisto.shop.layout.head.before') !!}
    
    <title>{{ $title ?? '' }}</title>
    
    {!! view_render_event('bagisto.shop.layout.head.after') !!}
</head>

<body>
    {!! view_render_event('bagisto.shop.layout.body.before') !!}
    
    <div id="app">
        {!! view_render_event('bagisto.shop.layout.content.before') !!}
        
        {{ $slot }}
        
        {!! view_render_event('bagisto.shop.layout.content.after') !!}
    </div>
    
    {!! view_render_event('bagisto.shop.layout.body.after') !!}
</body>
</html>
```

### Product Page Events

In product detail pages, Bagisto provides events for different sections:

```blade{5,9,13,16,20}
<div class="product-details">
    <div class="product-images">
        <!-- Product image gallery -->
        
        {!! view_render_event('bagisto.shop.products.view.gallery.after') !!}
    </div>
    
    <div class="product-info">
        {!! view_render_event('bagisto.shop.products.view.info.before') !!}
        
        <!-- Product title, price, description -->
        
        {!! view_render_event('bagisto.shop.products.view.info.after') !!}
        
        <div class="product-actions">
            {!! view_render_event('bagisto.shop.products.view.actions.before') !!}
            
            <!-- Add to cart, wishlist buttons -->
            
            {!! view_render_event('bagisto.shop.products.view.actions.after') !!}
        </div>
    </div>
</div>
```

### Checkout Page Events

Checkout pages have numerous injection points for payment methods, shipping options, and custom fields:

```blade{3,7,11,15}
<div class="checkout-form">
    <div class="billing-section">
        {!! view_render_event('bagisto.shop.checkout.billing.before') !!}
        
        <!-- Billing address form -->
        
        {!! view_render_event('bagisto.shop.checkout.billing.after') !!}
    </div>
    
    <div class="payment-section">
        {!! view_render_event('bagisto.shop.checkout.payment.before') !!}
        
        <!-- Payment method selection -->
        
        {!! view_render_event('bagisto.shop.checkout.payment.after') !!}
    </div>
</div>
```

::: tip Strategic Placement
Bagisto places render events at key locations where extensions commonly need to add functionality:
- Before/after major sections (header, footer, content areas)
- Around forms (login, checkout, product configuration)
- Near action buttons (add to cart, checkout, account actions)
- In listing pages (product grids, order lists)
:::

## Injecting Content with Render Events

To inject content into these predefined locations, you need to listen for the render events and provide template content. This is typically done in your package's service provider.

### Basic Event Listener Setup

Here's how to set up event listeners for render events:

```php{16-23}
<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Listen to render events
        Event::listen('bagisto.shop.products.view.actions.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('rma::shop.products.return-button');
        });

        Event::listen('bagisto.shop.customers.account.orders.view.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('rma::shop.customers.return-request-form');
        });
    }
}
```

### Template Registration Method

The `$viewRenderEventManager->addTemplate()` method accepts:

- **Template path**: Using package notation (`package::view.path`) or direct path
- **Data array** (optional): Additional data to pass to the template

```php{3-5}
Event::listen('bagisto.shop.checkout.payment.after', function($viewRenderEventManager) {
    $viewRenderEventManager->addTemplate('rma::shop.checkout.return-policy', [
        'policyUrl' => config('rma.return_policy_url'),
        'returnDays' => config('rma.default_return_days', 30)
    ]);
});
```

::: info Template Resolution
Bagisto resolves template paths using Laravel's view system. Package notation (`rma::shop.products.return-button`) looks for templates in your package's `Resources/views` directory.
:::

## Best Practices

When working with Bagisto's render events, following these best practices ensures your implementations are maintainable, performant, and compatible with future updates.

### Event Naming Conventions

Follow consistent naming patterns for your render events:

```blade
{!! view_render_event('bagisto.{area}.{module}.{page}.{section}.{position}') !!}
```

**Examples:**
- `bagisto.shop.products.view.actions.after`
- `bagisto.admin.sales.orders.edit.form.before`
- `bagisto.shop.checkout.billing.address.after`

### Performance Considerations

::: warning Performance Impact
Render events add minimal overhead, but consider these guidelines:
- **Limit complex logic**: Keep event listeners lightweight
- **Cache when possible**: Cache heavy computations in your templates
- **Conditional loading**: Only register events when your package is active
- **Template optimization**: Use efficient blade templates
:::

# Introduction

Bagisto provides comprehensive API solutions to help developers integrate and extend the platform's functionality. Whether you're building mobile apps, third-party integrations, or headless commerce solutions, our APIs offer the flexibility and power you need.

## Available API Types

### REST API

The Bagisto REST API follows RESTful principles and provides complete access to CRUD operations across all Bagisto features. Perfect for:

- **Mobile Applications** - Build native iOS/Android shopping apps
- **Third-party Integrations** - Connect with external systems and services  
- **Progressive Web Apps (PWA)** - Create fast, app-like web experiences
- **Custom Admin Interfaces** - Build specialized admin tools

**Key Features:**
- Full CRUD operations support
- Built-in pagination for performance
- Comprehensive documentation with interactive testing
- Laravel Sanctum authentication

::: tip Getting Started
New to REST APIs? Start with our [REST API Guide](./rest-api) for installation steps and examples.
:::

### GraphQL API  

The Bagisto GraphQL API enables flexible, efficient data fetching with a single endpoint. Ideal for:

- **Headless Commerce** - Power modern frontend frameworks
- **Mobile Apps** - Reduce bandwidth with precise data queries
- **Custom Storefronts** - Build unique shopping experiences
- **Real-time Applications** - Efficient data synchronization

**Key Features:**
- Single endpoint for all operations
- Flexible query structure - fetch exactly what you need
- Real-time subscriptions support
- Built on Laravel Lighthouse
- Type-safe schema with introspection

::: tip Modern Development
GraphQL is perfect for modern frontend frameworks like React, Vue, and React Native. Check out our [GraphQL API Guide](./graphql-api) to get started.
:::

## Authentication

Both APIs use secure authentication methods:

- **REST API**: Laravel Sanctum with token-based authentication
- **GraphQL API**: Session-based authentication with CSRF protection

## What's Next?

Ready to start building? Choose your preferred API approach:

- 📚 [REST API Documentation](./rest-api) - Traditional RESTful endpoints
- ⚡ [GraphQL API Documentation](./graphql-api) - Modern query language

# REST API

The Bagisto REST API provides a comprehensive RESTful interface to access all core Bagisto features. Built with Laravel Sanctum authentication, it offers secure and efficient endpoints for building mobile apps, third-party integrations, and custom interfaces.

## 🚀 Quick Start

### Live Demo

Explore our interactive API documentation and test endpoints in real-time:

- 🔧 [**Admin API Demo**](https://demo.bagisto.com/bagisto-api-demo-common/api/admin/documentation#/) - Manage products, orders, customers, and more
- 🛍️ [**Shop API Demo**](https://demo.bagisto.com/bagisto-api-demo-common/api/shop/documentation#/) - Customer-facing shopping functionality

::: tip Try It Now
Both demos include interactive testing tools where you can send real requests and see responses immediately.
:::

## 📦 Installation

### Step 1: Install the Package

Install the REST API package via Composer:

```bash
composer require bagisto/rest-api
```

### Step 2: Environment Configuration

Add the following configuration to your `.env` file:

```properties
# Replace with your actual domain
SANCTUM_STATEFUL_DOMAINS=http://localhost/public
```

::: warning Domain Configuration
Make sure to replace `http://localhost/public` with your actual domain URL. For production, use your live domain (e.g., `https://yourdomain.com`).
:::

### Step 3: Run Installation Command

Configure the L5-Swagger documentation:

```bash
php artisan bagisto-rest-api:install
```

This command will:
- Publish API configuration files
- Set up Swagger documentation
- Configure authentication routes

## 📖 Documentation Access

Once installed, access the interactive API documentation:

### Admin API Documentation
```
http://localhost/public/api/admin/documentation
```

### Shop API Documentation  
```
http://localhost/public/api/shop/documentation
```

::: info Interactive Testing
Both documentation interfaces include built-in testing tools. You can authenticate and test API endpoints directly from the browser.
:::

## 🔐 Authentication

The REST API uses Laravel Sanctum for secure token-based authentication:

### Getting an Access Token

1. **Admin Authentication**: Use admin credentials to get admin-level access
2. **Customer Authentication**: Use customer credentials for shop-level access

### Using Tokens

Include the token in your requests:

```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json" \
     http://localhost/public/api/v1/admin/get
```

## 🎯 Common Use Cases

### Mobile App Development
Build native iOS/Android apps with full e-commerce functionality:

```javascript
// Example: Fetch products for mobile app
fetch('/api/v1/products', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
})
.then(response => response.json())
.then(products => {
  // Display products in your mobile app
});
```

### Third-party Integration
Connect external systems with your Bagisto store:

```php
// Example: Sync product from external system
$response = Http::withToken($token)->post("/api/v1/admin/catalog/products/{$productId}", [
    'name'  => 'Product Name',
    'sku'   => 'PROD-001',
    'price' => 99.99
]);
```

## 🔗 Next Steps

- 📚 Explore the [interactive documentation](https://demo.bagisto.com/bagisto-api-demo-common/api/admin/documentation#/)

::: tip Need GraphQL?
For modern frontend development with flexible queries, consider our [GraphQL API](./graphql-api) instead.
:::

# GraphQL API

Bagisto's GraphQL API delivers a modern, flexible approach to e-commerce data access. Built on Laravel Lighthouse, it provides efficient querying capabilities perfect for headless commerce, mobile apps, and modern frontend frameworks.

## 🚀 Quick Start

### Live Demo

Experience the power of GraphQL with our interactive demo:

🌐 [**GraphQL API Demo**](https://demo.bagisto.com/mobikul-common/graphiql) - Test queries and explore the schema in real-time

::: tip Interactive Playground
The demo includes GraphiQL playground where you can write queries, explore documentation, and see real-time results.
:::

## 📦 Installation

### Step 1: Install the Package

Install the GraphQL API package via Composer:

```bash
composer require bagisto/graphql-api 
```

### Step 2: Configure Middleware

Update your `bootstrap/app.php` file to ensure proper session handling:

```php
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

return Application::configure(basePath: dirname(__DIR__))
   ->withMiddleware(function (Middleware $middleware) {
      // ... rest of middleware setup

      /**
       * Remove session and cookie middleware from the 'web' middleware group.
       */
      $middleware->removeFromGroup('web', [StartSession::class, AddQueuedCookiesToResponse::class]);

      /**
       * Adding session and cookie middleware globally to apply across non-web routes (e.g. GraphQL)
       */
      $middleware->append([StartSession::class, AddQueuedCookiesToResponse::class]);
   })
   // ... rest of configuration
```

::: warning Important Configuration
This middleware configuration ensures sessions work properly with GraphQL endpoints, which is essential for authentication and cart management.
:::

### Step 3: Environment Configuration

Add the following JWT settings to your `.env` file:

```properties
# JWT Configuration for GraphQL API
JWT_TTL=525600
JWT_SHOW_BLACKLIST_EXCEPTION=true

# API Key for mobile/frontend authentication
MOBIKUL_API_KEY=your-secure-api-key-here
```

::: tip Security Best Practice
Generate a strong, unique API key for production environments. This key should be kept secure and only shared with your development team.
:::

### Step 4: Install and Publish Assets

Run the installation command to set up configurations:

```bash
php artisan bagisto-graphql:install
```

This command will:
- Publish GraphQL schema files
- Set up authentication routes
- Configure GraphiQL playground

## 🔧 Testing Your Setup

### GraphiQL Playground

Access the interactive GraphQL playground:

```
http://your-domain.com/graphiql
```

### Direct API Endpoint

For programmatic access or tools like Postman:

```
http://your-domain.com/graphql
```

## 🔗 Next Steps

Ready to build with GraphQL? Here are your next steps:

- 🎮 [**Try the Live Demo**](https://demo.bagisto.com/mobikul-common/graphiql)

::: tip Need Traditional REST?
If you prefer traditional REST endpoints, check out our [REST API](./rest-api) documentation.
:::
# Artisan Commands Reference

Bagisto provides custom Artisan commands for installation, indexing, maintenance, and data management. These are in addition to Laravel's built-in commands.

## Installation

### `bagisto:install`

Interactive installation wizard that handles environment configuration, database setup, admin user creation, and initial data seeding.

```bash
php artisan bagisto:install
```

**Options:**

| Flag | Description |
|---|---|
| `--skip-env-check` | Skip environment file validation |
| `--skip-admin-creation` | Skip the admin user creation step |

**Source:** `Webkul\Installer\Console\Commands\Installer`

## System Information

### `bagisto:version`

Displays the current installed version of Bagisto.

```bash
php artisan bagisto:version
```

**Output example:**

```
2.4.0-beta6
```

**Source:** `Webkul\Core\Console\Commands\BagistoVersion`

## Product Indexing

### `indexer:index`

Reindexes product data for price, inventory, flat catalog, and Elasticsearch indices.

```bash
# Reindex everything
php artisan indexer:index

# Reindex only specific types
php artisan indexer:index --type=price --type=inventory

# Full reindex mode (drops and rebuilds)
php artisan indexer:index --mode=full
```

**Options:**

| Flag | Values | Description |
|---|---|---|
| `--type` | `price`, `inventory`, `flat`, `elastic` | Select which indexer(s) to run (repeatable) |
| `--mode` | `full`, `selective` | Full reindex vs. incremental |

**Source:** `Webkul\Product\Console\Commands\Indexer`

::: tip When to Reindex
Run this after bulk product imports, price changes, or if product listings appear out of sync. For production, consider scheduling periodic reindex via cron.
:::

### `product:price-rule:index`

Reindexes catalog rule pricing (discount rules applied to products at the catalog level).

```bash
php artisan product:price-rule:index
```

**Source:** `Webkul\CatalogRule\Console\Commands\PriceRuleIndex`

## Currency & Exchange Rates

### `exchange-rate:update`

Fetches and updates currency exchange rates from configured external providers.

```bash
php artisan exchange-rate:update
```

The frequency can be configured via admin panel under **Settings > Exchange Rates**. Supported update intervals: daily, weekly, monthly.

**Source:** `Webkul\Core\Console\Commands\ExchangeRateUpdate`

## Marketing

### `campaign:process`

Processes pending marketing campaigns and sends emails to subscribed customers.

```bash
php artisan campaign:process
```

**Source:** `Webkul\Marketing\Console\Commands\EmailsCommand`

::: tip Scheduling
This command should be run periodically via cron to process queued campaigns. Add it to your server's crontab or use Laravel's task scheduler.
:::

## Invoicing

### `invoice:cron`

Processes overdue invoice reminders. Checks for unpaid invoices past their due date and sends reminder notifications within the configured reminders limit.

```bash
php artisan invoice:cron
```

**Source:** `Webkul\Core\Console\Commands\InvoiceOverdueCron`

## Maintenance Mode

Bagisto overrides Laravel's default `up` and `down` commands to also manage channel-level maintenance state.

### `down`

Puts the application in maintenance mode and marks all channels as under maintenance.

```bash
php artisan down
```

### `up`

Brings the application out of maintenance mode and deactivates maintenance on all channels.

```bash
php artisan up
```

**Source:** `Webkul\Core\Console\Commands\DownCommand`, `Webkul\Core\Console\Commands\UpCommand`

::: info Channel-Aware Maintenance
Unlike standard Laravel, Bagisto's maintenance mode updates the `is_maintenance_on` flag on all channel records in the database, allowing the storefront to show maintenance pages per-channel.
:::

## Translation Validation

### `bagisto:translations:check`

Validates translation files across all packages for consistency against the English (`en`) canonical locale.

```bash
# Check all packages and locales
php artisan bagisto:translations:check

# Check a specific locale
php artisan bagisto:translations:check --locale=fr

# Check a specific package
php artisan bagisto:translations:check --package=Admin

# Show detailed key-by-key differences
php artisan bagisto:translations:check --details
```

**Options:**

| Flag | Description |
|---|---|
| `--locale=` | Check only a specific locale code |
| `--package=` | Check only a specific package name |
| `--details` | Show detailed key-level differences |

**Source:** `Webkul\Core\Console\Commands\TranslationsChecker`

## Common Laravel Commands Used with Bagisto

These are standard Laravel commands frequently needed during Bagisto development:

```bash
# Clear all caches
php artisan optimize:clear

# Create storage symlink (required for product images)
php artisan storage:link

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Fresh install (drops all tables, migrates, seeds)
php artisan migrate:fresh --seed

# Generate application key
php artisan key:generate

# Start development server
php artisan serve
```

# Queue, Jobs & Scheduling

Bagisto uses Laravel's queue system for background processing of indexing, data import/export, and search operations. By default, the queue runs synchronously (`sync` driver), but production environments should use an async driver like Redis or database.

## Queue Configuration

The queue driver is set in `.env`:

```properties
QUEUE_CONNECTION=sync
```

For production, switch to an async driver:

```properties
# Using Redis (recommended)
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Or using the database driver
QUEUE_CONNECTION=database
```

::: tip When to Use Async Queues
If you have more than a few hundred products, or use Elasticsearch, switching to an async queue driver significantly improves admin panel responsiveness during product saves, imports, and catalog rule updates.
:::

## Built-in Jobs

### Product Indexing Jobs

These jobs update product indices when products are created, updated, or deleted:

| Job Class | Package | Purpose |
|---|---|---|
| `UpdateCreateInventoryIndex` | `Webkul\Product` | Reindexes inventory levels for given product IDs |
| `UpdateCreatePriceIndex` | `Webkul\Product` | Reindexes price data for given product IDs |
| `ElasticSearch\UpdateCreateIndex` | `Webkul\Product` | Updates Elasticsearch indices for products (only runs when Elasticsearch is the configured search engine) |
| `ElasticSearch\DeleteIndex` | `Webkul\Product` | Removes products from Elasticsearch across all channel/locale combinations |

### Catalog Rule Jobs

These jobs recalculate product pricing when catalog rules change:

| Job Class | Package | Purpose |
|---|---|---|
| `UpdateCreateCatalogRuleIndex` | `Webkul\CatalogRule` | Reindexes a catalog rule and reprices associated products in batches of 100 |
| `DeleteCatalogRuleIndex` | `Webkul\CatalogRule` | Reprices products after a catalog rule is deleted |
| `UpdateCreateProductIndex` | `Webkul\CatalogRule` | Reindexes a single product against all catalog rules |

### Data Transfer Jobs

The data import system uses Laravel's job batching for large imports:

| Job Class | Package | Purpose |
|---|---|---|
| `Import\ImportBatch` | `Webkul\DataTransfer` | Processes a batch of import records |
| `Import\LinkBatch` | `Webkul\DataTransfer` | Links/associates imported records |
| `Import\IndexBatch` | `Webkul\DataTransfer` | Indexes a batch of imported records |
| `Import\Linking` | `Webkul\DataTransfer` | Full linking pass after import |
| `Import\Indexing` | `Webkul\DataTransfer` | Full indexing pass after import |
| `Import\Completed` | `Webkul\DataTransfer` | Post-import completion tasks |

### Other Jobs

| Job Class | Package | Purpose |
|---|---|---|
| `UpdateCreateSearchTerm` | `Webkul\Marketing` | Creates/updates search term records with usage counts |
| `ProcessSitemap` | `Webkul\Sitemap` | Generates XML sitemaps for products, categories, and CMS pages |

## Running the Queue Worker

For async queue drivers, run a worker process:

```bash
# Start a queue worker
php artisan queue:work

# Process jobs from a specific queue
php artisan queue:work --queue=default

# Limit memory and timeout
php artisan queue:work --memory=256 --timeout=120

# Run once and exit (useful for cron-based processing)
php artisan queue:work --once
```

::: warning Production Workers
In production, use a process manager like Supervisor to keep queue workers running. See the [Laravel Queue documentation](https://laravel.com/docs/12.x/queues#supervisor-configuration) for Supervisor configuration.
:::

## Scheduled Tasks

Bagisto's scheduled commands should be triggered via your server's crontab. Add the Laravel scheduler entry:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Commands Intended for Scheduling

| Command | Suggested Frequency | Purpose |
|---|---|---|
| `invoice:cron` | Daily (e.g., 3:00 AM) | Send overdue invoice reminders |
| `exchange-rate:update` | Daily / Weekly / Monthly | Update currency exchange rates |
| `campaign:process` | Every few minutes | Process and send marketing campaign emails |
| `product:price-rule:index` | Daily or on-demand | Reindex catalog rule pricing |
| `indexer:index` | Daily or on-demand | Full product reindex (price, inventory, flat, elastic) |

### Example Crontab Entries

```bash
# Laravel scheduler (handles all scheduled commands)
* * * * * cd /var/www/html/bagisto && php artisan schedule:run >> /dev/null 2>&1

# Or run specific commands directly:
0 3 * * * cd /var/www/html/bagisto && php artisan invoice:cron >> /dev/null 2>&1
0 4 * * * cd /var/www/html/bagisto && php artisan exchange-rate:update >> /dev/null 2>&1
*/5 * * * * cd /var/www/html/bagisto && php artisan campaign:process >> /dev/null 2>&1
0 2 * * * cd /var/www/html/bagisto && php artisan indexer:index >> /dev/null 2>&1
```

## Dispatching Jobs in Your Package

To dispatch a job from your custom package, follow the standard Laravel pattern:

```php
use Webkul\Product\Jobs\UpdateCreatePriceIndex;

// Dispatch to the queue
UpdateCreatePriceIndex::dispatch($productIds);

// Dispatch synchronously (bypasses queue)
UpdateCreatePriceIndex::dispatchSync($productIds);
```

When creating custom jobs, place them in your package's `Jobs/` directory:

```
packages/Webkul/YourPackage/src/Jobs/
└── YourCustomJob.php
```

```php
<?php

namespace Webkul\YourPackage\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class YourCustomJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected array $data
    ) {}

    public function handle(): void
    {
        // Your background processing logic
    }
}
```

# Cache Strategy

Bagisto implements a multi-layered caching strategy to optimize performance across the storefront, admin panel, and data access layers. Understanding these layers helps you build performant custom packages and debug cache-related issues.

## Cache Layers Overview

| Layer | Technology | Scope | Configuration |
|---|---|---|---|
| **Application Cache** | File / Redis / Memcached | Key-value storage for config, routes | `CACHE_STORE` in `.env` |
| **Repository Cache** | Prettus L5 Repository | Automatic model query caching | `config/repository.php` |
| **Full Page Cache (FPC)** | Spatie ResponseCache | Caches entire HTTP responses | `RESPONSE_CACHE_ENABLED` in `.env` |
| **Elasticsearch** | Elasticsearch 7.17+ | Product search index | `config/elasticsearch.php` |
| **Session Store** | Database / Redis | User session data | `SESSION_DRIVER` in `.env` |

## Application Cache

Configured via the `CACHE_STORE` environment variable. Default is `file`:

```properties
# .env
CACHE_STORE=file
```

For production, Redis is recommended:

```properties
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Clear the application cache:

```bash
php artisan cache:clear

# Or clear everything at once
php artisan optimize:clear
```

## Repository Cache

Bagisto uses the [Prettus L5 Repository](https://github.com/prettus/l5-repository) package, which provides automatic query-level caching for repository operations. Configuration is in `config/repository.php`.

### Automatic Invalidation

The `Webkul\Core\Listeners\CleanCacheRepository` listener automatically invalidates cached repository data when entities are created, updated, or deleted:

```
Event: RepositoryEntityCreated → Cache cleared for that repository
Event: RepositoryEntityUpdated → Cache cleared for that repository
Event: RepositoryEntityDeleted → Cache cleared for that repository
```

This is registered in `Webkul\Core\Providers\EventServiceProvider` and runs globally for all repositories.

::: tip No Manual Cache Clearing Needed
When you use the repository pattern (as Bagisto recommends), cache invalidation happens automatically. You don't need to manually clear caches after CRUD operations through repositories.
:::

## Full Page Cache (FPC)

The FPC package (`Webkul\FPC`) uses [Spatie ResponseCache](https://github.com/spatie/laravel-responsecache) to cache complete HTTP responses for storefront pages.

### Enabling FPC

```properties
# .env
RESPONSE_CACHE_ENABLED=true
```

### How It Works

The `CacheResponse` middleware in the Shop package caches GET responses for guest users. When content changes, event listeners automatically invalidate affected URLs:

| Event | What Gets Invalidated |
|---|---|
| Product create/update/delete | Product page URL + category pages |
| Category update/delete | Category page URL |
| Review update/delete | Corresponding product page |
| Order placed / Refund issued | Product pages (stock changes) |
| CMS page update/delete | CMS page URL |
| URL rewrite change | Old and new URLs |
| Theme customization change | Full cache clear |
| Core configuration change | Full cache clear |
| Channel update | Full cache clear |

### FPC Event Listeners

These listeners are in `packages/Webkul/FPC/src/Listeners/` and are registered via `Webkul\FPC\Providers\EventServiceProvider`:

- `Product.php` — Invalidates product and category URLs
- `Category.php` — Invalidates category URLs
- `Review.php` — Invalidates reviewed product URLs
- `Order.php` — Invalidates product URLs for ordered items
- `Refund.php` — Invalidates product URLs for refunded items
- `Page.php` — Invalidates CMS page URLs
- `URLRewrite.php` — Invalidates rewritten URLs
- `ThemeCustomization.php` — Clears entire cache or specific URLs
- `CoreConfig.php` — Clears entire response cache

::: warning Admin Panel
The admin panel is explicitly excluded from response caching via the `NoCacheMiddleware` applied to all admin routes. This ensures admin users always see fresh data.
:::

## Cache in Custom Packages

When building custom packages that modify data visible on the storefront, make sure to invalidate FPC for affected URLs:

```php
use Spatie\ResponseCache\Facades\ResponseCache;

// Clear specific URLs
ResponseCache::forget('/products/my-product');

// Clear everything
ResponseCache::clear();
```

For repository-level cache, simply using the repository pattern ensures automatic invalidation. If you bypass the repository and use Eloquent directly, cached data may become stale.

## Cache Configuration for Production

Recommended `.env` settings for production:

```properties
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
RESPONSE_CACHE_ENABLED=true

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Run these commands after deployment:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

To clear all caches at once:

```bash
php artisan optimize:clear
```

# Testing Workflow

Bagisto uses [Pest PHP](https://pestphp.com/) v3 as its primary testing framework, backed by PHPUnit 11. The test suite covers unit tests, feature tests, and browser-level E2E tests via Playwright.

## Test Structure

Tests are organized per-package under each package's `tests/` directory, with a shared bootstrap at the project root:

```
tests/
├── Pest.php              # Global Pest configuration
└── TestCase.php          # Base test case

packages/Webkul/
├── Admin/tests/
│   └── Feature/          # Admin panel feature tests
├── Core/tests/
│   └── Unit/             # Core utility unit tests
├── Customer/tests/
│   └── Unit/             # Customer module unit tests
├── DataGrid/tests/
│   └── Unit/             # DataGrid unit tests
├── Installer/tests/
│   └── Feature/          # Installation wizard tests
├── Payment/tests/        # Payment base tests
├── PayU/tests/
│   ├── Unit/
│   └── Feature/
├── Razorpay/tests/
│   ├── Unit/
│   └── Feature/
├── Shop/tests/
│   └── Feature/          # Storefront feature tests
└── Stripe/tests/
    ├── Unit/
    └── Feature/
```

## Running Tests

### Run All Tests

```bash
php artisan test
```

Or directly with Pest:

```bash
./vendor/bin/pest
```

### Run a Specific Test Suite

The test suites are defined in `phpunit.xml`. For example, to run only admin feature tests:

```bash
./vendor/bin/pest --testsuite="Admin Feature Tests"
```

Available test suites (from `phpunit.xml`):

| Suite Name | Path | Type |
|---|---|---|
| Admin Feature Tests | `packages/Webkul/Admin/tests/Feature` | Feature |
| Core Unit Tests | `packages/Webkul/Core/tests/Unit` | Unit |
| Customer Unit Tests | `packages/Webkul/Customer/tests/Unit` | Unit |
| DataGrid Unit Tests | `packages/Webkul/DataGrid/tests/Unit` | Unit |
| Installer Feature Tests | `packages/Webkul/Installer/tests/Feature` | Feature |
| PayU Unit Tests | `packages/Webkul/PayU/tests/Unit` | Unit |
| PayU Feature Tests | `packages/Webkul/PayU/tests/Feature` | Feature |
| Razorpay Unit Tests | `packages/Webkul/Razorpay/tests/Unit` | Unit |
| Razorpay Feature Tests | `packages/Webkul/Razorpay/tests/Feature` | Feature |
| Shop Feature Tests | `packages/Webkul/Shop/tests/Feature` | Feature |
| Stripe Unit Tests | `packages/Webkul/Stripe/tests/Unit` | Unit |
| Stripe Feature Tests | `packages/Webkul/Stripe/tests/Feature` | Feature |

### Run a Single Test File

```bash
./vendor/bin/pest packages/Webkul/Admin/tests/Feature/ExampleTest.php
```

### Filter by Test Name

```bash
./vendor/bin/pest --filter="it can create a product"
```

## Test Environment

The test environment is configured in `phpunit.xml` with these overrides:

```xml
<env name="APP_ENV" value="testing"/>
<env name="BCRYPT_ROUNDS" value="4"/>
<env name="CACHE_STORE" value="array"/>
<env name="MAIL_MAILER" value="array"/>
<env name="SESSION_DRIVER" value="array"/>
<env name="QUEUE_CONNECTION" value="sync"/>
```

::: tip Performance
`BCRYPT_ROUNDS=4` speeds up tests by reducing password hashing cost. `CACHE_STORE=array` and `SESSION_DRIVER=array` use in-memory drivers for fast, isolated tests.
:::

The test bootstrap in `tests/Pest.php` also sets:

```php
ini_set('memory_limit', '1024M');
```

## Test Configuration (Pest.php)

The global `tests/Pest.php` file configures which base test case each package uses:

```php
uses(Tests\TestCase::class)->in(__DIR__);
uses(Webkul\Admin\Tests\AdminTestCase::class)->in('../packages/Webkul/Admin/tests');
uses(Webkul\Shop\Tests\ShopTestCase::class)->in('../packages/Webkul/Shop/tests');
uses(Webkul\Core\Tests\CoreTestCase::class)->in('../packages/Webkul/Core/tests');
// ... and so on for each package
```

Each package-specific test case extends `Tests\TestCase` and may bootstrap package-specific fixtures, factories, or state.

## Test Factories

Bagisto ships with 40+ model factories in `database/factories/` for generating test data. These follow the standard Laravel factory pattern:

```php
use Webkul\Product\Models\Product;

$product = Product::factory()->create();
```

The `bagisto/laravel-datafaker` dev dependency provides additional data generation utilities specific to e-commerce.

## Browser Tests (Playwright)

Bagisto includes Playwright E2E tests for both the admin panel and storefront. These are configured as GitHub Actions workflows:

- `admin_playwright_tests.yml` — Tests admin panel UI flows
- `shop_playwright_tests.yml` — Tests storefront UI flows

To run Playwright tests locally:

```bash
npx playwright install
npx playwright test
```

::: warning Prerequisites
Playwright tests require a running Bagisto instance with a seeded database. Make sure you have run `php artisan migrate:fresh --seed` before running E2E tests.
:::

## Code Style (Pint)

Bagisto uses [Laravel Pint](https://laravel.com/docs/12.x/pint) for code formatting. The CI runs Pint checks on every push and pull request.

```bash
# Check formatting
./vendor/bin/pint --test

# Fix formatting
./vendor/bin/pint
```

The configuration is in `pint.json` at the project root.

## CI Pipeline

GitHub Actions runs these checks on every push/PR:

| Workflow | What it checks |
|---|---|
| `pest_tests.yml` | All Pest unit and feature tests (MySQL 8.0, PHP 8.3) |
| `pint_tests.yml` | Code style with Laravel Pint |
| `admin_playwright_tests.yml` | Admin panel E2E tests |
| `shop_playwright_tests.yml` | Storefront E2E tests |
| `translation_tests.yml` | Translation file consistency |

## Writing Tests for a Package

When creating tests for a custom package, follow this pattern:

1. Create a `tests/` directory inside your package
2. Add a test case class extending `Tests\TestCase`
3. Register the test namespace in `composer.json` under `autoload-dev`
4. Add a test suite entry in `phpunit.xml`
5. Register the test path in `tests/Pest.php`

```php
// packages/Webkul/YourPackage/tests/Feature/ExampleTest.php

it('can perform expected action', function () {
    // Arrange
    $data = ['key' => 'value'];

    // Act
    $response = $this->post(route('your.route'), $data);

    // Assert
    $response->assertStatus(200);
});
```

::: tip Pest Syntax
Bagisto uses Pest's closure-based syntax (`it()`, `test()`, `expect()`) rather than PHPUnit class-based tests. Follow this convention for consistency.
:::

# Debugging Tips

Bagisto includes a custom DebugBar integration and supports standard Laravel debugging tools. This guide covers practical debugging approaches for common development scenarios.

## DebugBar

Bagisto ships with a custom DebugBar package (`Webkul\DebugBar`) that extends [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) with Bagisto-specific insights.

### Enabling DebugBar

DebugBar is available when `APP_DEBUG=true` in your `.env` file:

```properties
APP_DEBUG=true
```

### IP Whitelisting

For security, DebugBar access can be restricted to specific IPs using the `APP_DEBUG_ALLOWED_IPS` environment variable:

```properties
APP_DEBUG_ALLOWED_IPS=127.0.0.1,192.168.1.100
```

This is configured in `App\Providers\AppServiceProvider`, which checks the client IP against the whitelist before enabling DebugBar.

### Bagisto Module Collector

The custom **Modules** tab in DebugBar (`Webkul\DebugBar\DataCollector\ModuleCollector`) provides:

- **Model Tracking** — Which Eloquent models were loaded during the request, grouped by Concord module
- **View Tracking** — Which Blade templates were rendered, with file paths
- **Query Tracking** — SQL queries executed, with bindings substituted and duration recorded
- **Module Grouping** — All of the above grouped by the Webkul package that owns them

This helps you understand which packages are involved in a given page load and identify N+1 query problems or unnecessary view compositions.

## Logging

Bagisto uses Laravel's logging system. Log configuration is in `.env`:

```properties
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug
```

### Writing Logs

```php
use Illuminate\Support\Facades\Log;

Log::info('Order processed', ['order_id' => $order->id]);
Log::error('Payment failed', ['error' => $e->getMessage()]);
```

Log files are stored in `storage/logs/laravel.log`.

### Viewing Logs

```bash
# Tail the log file
tail -f storage/logs/laravel.log

# Search for specific entries
grep "Payment failed" storage/logs/laravel.log
```

## Common Debugging Scenarios

### Debugging Routes

List all registered routes to find which controller handles a URL:

```bash
# All routes
php artisan route:list

# Filter by URI pattern
php artisan route:list --path=admin/catalog

# Filter by name
php artisan route:list --name=shop
```

::: tip Route Organization
Admin routes are in `packages/Webkul/Admin/src/Routes/` (split into `catalog-routes.php`, `sales-routes.php`, etc.). Shop routes are in `packages/Webkul/Shop/src/Routes/` (split into `store-front-routes.php`, `customer-routes.php`, `checkout-routes.php`).
:::

### Debugging Events

To see what events are fired during a request, use DebugBar's Events tab. Alternatively, add a temporary wildcard listener:

```php
// In a service provider boot() method (temporary debugging only)
Event::listen('*', function (string $event, array $data) {
    Log::debug('Event fired: ' . $event);
});
```

### Debugging Database Queries

Enable query logging temporarily:

```php
\DB::enableQueryLog();

// ... your code ...

dd(\DB::getQueryLog());
```

Or use DebugBar's Queries tab, which shows all queries with execution time and the Webkul module that triggered them.

### Debugging Configuration

Check if a system configuration value is loading correctly:

```php
// In tinker
php artisan tinker
>>> core()->getConfigData('catalog.products.storefront.search_mode')
```

### Debugging Blade Views

Bagisto includes a **Blade Tracer** (documented in [Theme Development > Blade Tracer](/theme-development/blade-tracer)) that helps identify which Blade template renders a given section of the page.

### Debugging the Shop Middleware Stack

The Shop middleware group applies these middleware in order:

1. `Webkul\Shop\Http\Middleware\Theme` — Loads the active theme
2. `Webkul\Shop\Http\Middleware\Locale` — Sets the current locale
3. `Webkul\Shop\Http\Middleware\Currency` — Sets the current currency

If the shop looks wrong (wrong theme, language, or currency), check these middleware are running and that the channel/locale/currency configuration is correct.

## Useful Artisan Commands for Debugging

```bash
# Clear all caches (config, route, view, event, app cache)
php artisan optimize:clear

# Check current Bagisto version
php artisan bagisto:version

# Validate translation files
php artisan bagisto:translations:check --details

# List all registered service providers
php artisan about

# Check queue status
php artisan queue:monitor
```

## Environment Checks

Common issues and quick checks:

| Symptom | Check |
|---|---|
| Broken images | `APP_URL` matches your domain; `php artisan storage:link` was run |
| 500 errors | `storage/logs/laravel.log` for stack trace; permissions on `storage/` and `bootstrap/cache/` |
| Stale data after changes | `php artisan optimize:clear`; check `RESPONSE_CACHE_ENABLED` |
| Admin not accessible | `APP_ADMIN_URL` in `.env` matches the URL path you're using |
| CSS/JS not loading | Run `npm run build` or `npm run dev` for Vite assets |
| Queue jobs not processing | Check `QUEUE_CONNECTION` in `.env`; run `php artisan queue:work` |

# Common Pitfalls

This page documents frequent issues encountered by developers working with Bagisto, along with solutions derived from the actual codebase.

## Installation & Setup

### PHP Version Mismatch

Bagisto requires **PHP 8.3 or higher**. The `composer.json` specifies `"php": "^8.3"`. Running on PHP 8.3 or lower will cause dependency resolution failures.

```bash
# Verify your PHP version
php -v
```

### Missing PHP Extensions

The following extensions are required (from `composer.json`):

- `ext-calendar`
- `ext-curl`
- `ext-intl`
- `ext-mbstring`
- `ext-openssl`
- `ext-pdo`
- `ext-pdo_mysql`
- `ext-tokenizer`

```bash
# Check installed extensions
php -m | grep -E "calendar|curl|intl|mbstring|openssl|pdo|tokenizer"
```

### Storage Link Not Created

Product images and uploads won't display without the storage symlink:

```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public`. If images still don't load, verify `APP_URL` in `.env` matches your actual domain/port.

### APP_URL Mismatch

A mismatch between `APP_URL` and your actual URL causes broken assets, images, and redirects. This is especially common when switching between local development and production.

```properties
# Must match exactly, including port
APP_URL=http://localhost:8000
```

## Database

### MySQL Version

Bagisto requires MySQL 8.0.32 or higher. The `utf8mb4_unicode_ci` collation is recommended for full Unicode support.

### Migration Order Matters

When running `php artisan migrate:fresh --seed`, Bagisto's package migrations run in the order they're discovered by Concord. If you add a custom package with foreign key dependencies on core tables, ensure your migrations have timestamps that come after the core migrations.

### Database Session Driver

Bagisto defaults to `SESSION_DRIVER=database`. If you run `php artisan migrate:fresh` but forget `--seed`, the sessions table exists but the application may behave unexpectedly without seed data.

## Package Development

### Service Provider Registration

Every Bagisto package needs a service provider registered in the root `composer.json` autoload section. Forgetting this step means your package won't be discovered:

```json
"autoload": {
    "psr-4": {
        "Webkul\\YourPackage\\": "packages/Webkul/YourPackage/src"
    }
}
```

After adding, run:

```bash
composer dump-autoload
```

### Concord Module Registration

Packages must extend `Webkul\Core\Providers\CoreModuleServiceProvider` (or register as a Concord module) to be properly recognized. Check that your service provider is registered in `config/concord.php` or uses Laravel's package discovery.

### Bypassing the Repository Pattern

Bagisto's repository cache automatically invalidates when you use repositories for CRUD. If you bypass the repository and write raw Eloquent queries, cached data can become stale. Always prefer repository methods:

```php
// ✅ Correct — uses repository, triggers cache invalidation
$this->productRepository->update($data, $id);

// ❌ Avoid — bypasses cache invalidation
Product::where('id', $id)->update($data);
```

## Frontend & Assets

### Vite Build Required

After changing CSS or JavaScript in a package, you must rebuild assets:

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

Each package with frontend assets (Admin, Shop) has its own `vite.config.js`. The root `vite.config.js` handles the main application assets.

### Tailwind CSS Purging

If custom Tailwind classes don't appear in production, ensure your Blade file paths are included in the relevant `tailwind.config.js` content array. Tailwind only includes classes that appear in scanned files.

## Caching Issues

### Stale Configuration After `.env` Changes

After modifying `.env`, always clear the config cache:

```bash
php artisan config:clear
# Or clear everything
php artisan optimize:clear
```

If you've run `php artisan config:cache`, the cached config takes precedence over `.env` values until cleared.

### Response Cache Serving Old Pages

If storefront pages show stale content after product/category changes, the FPC event listeners may not be covering your change. Check that `RESPONSE_CACHE_ENABLED=true` and clear manually if needed:

```bash
php artisan responsecache:clear
```

## Queue & Jobs

### Sync Queue in Production

The default `QUEUE_CONNECTION=sync` processes all jobs synchronously during the HTTP request. This works for development but causes timeouts in production when saving products with Elasticsearch indexing or processing large imports.

Switch to `redis` or `database` driver for production and run a queue worker:

```properties
QUEUE_CONNECTION=redis
```

```bash
php artisan queue:work
```

## Mail Configuration

### Dynamic SMTP

Bagisto uses a custom `bagisto-dynamic-smtp` mail driver that reads SMTP settings from the database (admin panel configuration) rather than `.env` only. If email settings in the admin panel differ from `.env`, the admin panel values take precedence.

This is handled by `Webkul\Core\Providers\DynamicSmtpServiceProvider`.

## Admin Panel

### Custom Admin URL

The admin panel URL is configured via `APP_ADMIN_URL` in `.env`:

```properties
APP_ADMIN_URL=admin
```

Changing this value requires clearing the route cache:

```bash
php artisan route:clear
```

### ACL Not Working

If a custom admin menu item doesn't respect ACL, verify that:

1. Your `Config/acl.php` file defines the permission key
2. Your route or controller checks the permission with the correct key
3. The admin role has the permission enabled

## Elasticsearch

### Connection Refused

If product search fails with Elasticsearch errors, verify:

1. Elasticsearch is running and accessible
2. The `.env` or `config/elasticsearch.php` has the correct host URL
3. Elasticsearch version is 7.x or 8.x compatible

```bash
# Test Elasticsearch connection
curl http://localhost:9200
```

### Index Out of Sync

After bulk changes, reindex Elasticsearch:

```bash
php artisan indexer:index --type=elastic
```

## Multi-Channel / Multi-Locale

### Channel-Specific Configuration

Many configuration values in Bagisto are channel-specific and locale-specific. When reading config with `core()->getConfigData()`, the current channel and locale context matters. A setting that works on one channel may return `null` on another if not configured.

### Maintenance Mode is Per-Channel

Bagisto's `php artisan down` command sets maintenance mode on all channels. If you only want to take down one channel, manage the `is_maintenance_on` flag directly on the channel record rather than using the artisan command.
