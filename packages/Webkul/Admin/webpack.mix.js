const mix = require("laravel-mix");

if (mix == 'undefined') {
    const { mix } = require("laravel-mix");
}

require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/vendor/webkul/admin/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/admin.js")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/admin.css")
    .copy(__dirname + '/src/Resources/assets/images', publicPath + '/images')
    .options({
        processCssUrls: false
    });

if (! mix.inProduction()) {
    mix.sourceMaps();
}

if (mix.inProduction()) {
    mix.version();
}