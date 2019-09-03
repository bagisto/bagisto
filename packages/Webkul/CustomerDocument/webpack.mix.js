const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

// var publicPath = 'publishable/assets';
var publicPath = "../../../public/vendor/webkul/customerdocument/assets";

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

    mix.copy(__dirname + "/src/Resources/assets/images", publicPath + "/images/")
    .sass(__dirname + "/src/Resources/assets/sass/customerdocument.scss", "css/customerdocument.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}