const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../../public/vendor/webkul/ui/assets').mergeManifest();
// mix.setPublicPath('publishable/assets').mergeManifest();

mix.js(__dirname + '/src/Resources/assets/js/app.js', 'js/ui.js')
    .sass( __dirname + '/src/Resources/assets/sass/app.scss', 'css/ui.css');

if (mix.inProduction()) {
    mix.version();
}