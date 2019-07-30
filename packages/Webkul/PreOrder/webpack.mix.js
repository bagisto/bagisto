const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/vendor/webkul/preorder/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.copyDirectory(__dirname + "/src/Resources/assets/images", publicPath + "/images")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/preorder.css")
    .sass(__dirname + "/src/Resources/assets/sass/admin.scss", "css/preorder-admin.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}