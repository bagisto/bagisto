const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

let publicPath = '../../../public/vendor/webkul/admin/assets';

if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.sass(
        __dirname + '/src/Resources/assets/sass/newsletter.scss',
        'css/newsletter.css'
    )
    .copy(__dirname + '/src/Resources/assets/images', publicPath + '/images')
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
