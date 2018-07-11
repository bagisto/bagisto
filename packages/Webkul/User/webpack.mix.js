const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');


// var publicPath = 'publishable/assets';
var publicPath = '../../../public/vendor/webkul/core/assets';

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.copyDirectory( __dirname + '/src/Resources/assets/lang', publicPath + '/lang')

if (mix.inProduction()) {
    mix.version();
}