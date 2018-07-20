const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

// var publicPath = 'publishable/assets';
var publicPath = "../../../public/vendor/webkul/customer/assets";

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.sass(
    __dirname + "/src/Resources/assets/sass/app.scss",
    "css/customer.css"
).options({
    processCssUrls: false
});

if (mix.inProduction()) {
    mix.version();
}
