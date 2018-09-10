const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

// var publicPath = 'publishable/assets';
var publicPath = "../../../public/vendor/webkul/admin/assets";

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/admin.js")
    // .copyDirectory( __dirname + '/src/Resources/assets/images', publicPath + '/images')
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/admin.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
