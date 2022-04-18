const mix = require('laravel-mix');

if (mix == 'undefined') {
    const { mix } = require('laravel-mix');
}

require('laravel-mix-merge-manifest');

let publicPath = '../../../public/themes/default/assets';

if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(__dirname + '/src/Resources/assets/js/app.js', 'js/admin-booking.js')
    .copyDirectory(
        __dirname + '/src/Resources/assets/images',
        publicPath + '/images'
    )
    .sass(
        __dirname + '/src/Resources/assets/sass/admin.scss',
        'css/admin-booking.css'
    )
    .sass(
        __dirname + '/src/Resources/assets/sass/default.scss',
        'css/default-booking.css'
    )
    .sass(
        __dirname + '/src/Resources/assets/sass/velocity.scss',
        'css/velocity-booking.css'
    )
    .options({
        processCssUrls: false,
    });

if (!mix.inProduction()) {
    mix.sourceMaps();
}

if (mix.inProduction()) {
    mix.version();
}
