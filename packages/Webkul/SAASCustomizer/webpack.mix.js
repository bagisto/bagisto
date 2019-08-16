const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/vendor/webkul/saas/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/saas.js")
    .copyDirectory( __dirname + '/src/Resources/assets/images', publicPath + '/images')
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/saas.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}