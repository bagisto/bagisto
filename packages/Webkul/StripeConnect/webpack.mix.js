const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/vendor/webkul/stripe/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

    mix.copy(__dirname + "/src/Resources/assets/images", publicPath + "/images/")
    .copy(__dirname + "/src/Resources/assets/fonts", publicPath + "/fonts/")
    .sass(__dirname + "/src/Resources/assets/sass/stripe.scss", "css/stripe.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}