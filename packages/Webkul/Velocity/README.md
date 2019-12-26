<p align="center">
<a href="http://www.bagisto.com"><img src="https://bagisto.com/wp-content/themes/bagisto/images/logo.png" alt="Total Downloads"></a>
</p>

# Bagisto Velocity Theme

### Installation and Configuration

**1. Clone the repository:**

run command in packages/Webkul directory

~~~
git clone -b shubham https://git.webkul.com/laravel/laravel-velocity-theme.git Velocity
~~~

**2. Update app.php:**

pass the following to the providers array in app.php inside the config directory as the last element.

~~~
// Velocity provider
Webkul\Velocity\Providers\VelocityServiceProvider::class
~~~

**3. Update composer.json:**

pass the following to the psr-4 in composer.json

~~~
"Webkul\\Velocity\\": "packages/Webkul/Velocity/src"
~~~

**4. update themes.php:**

add the following array in themes.php file under config directory

~~~
'velocity' => [
    'views_path' => 'resources/themes/velocity/views',
    'assets_path' => 'public/themes/velocity/assets',
    'name' => 'Velocity',
    'parent' => 'default'
],
~~~

**5. Run commands:**

~~~
composer dump-autoload
~~~

~~~
php artisan vendor:publish --force
~~~

**6. For development:**

~~~
ln -s __PROJECT_DIR__/packages/Webkul/Velocity/publishable/assets __PROJECT_DIR__/public/themes/velocity/assets
~~~

~~~
ln -s __PROJECT_DIR__/packages/Webkul/Velocity/src/Resources/views __PROJECT_DIR__/resources/themes/velocity
~~~
