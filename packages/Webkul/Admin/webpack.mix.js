const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../../public/vendor/webkul/admin/assets').mergeManifest();
// mix.setPublicPath('publishable/assets').mergeManifest();

mix.js(__dirname + '/src/Resources/assets/js/app.js', 'js/admin.js')
    .sass( __dirname + '/src/Resources/assets/sass/app.scss', 'css/admin.css');

if (mix.inProduction()) {
    mix.version();
}