const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

var publicPath = "../../../resources/themes/velocity/assets";

if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

publicPath = 'publishable/assets';

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix
    .js(
        __dirname + "/src/Resources/assets/js/app.js",
        "js/velocity.js"
    )

    .sass(
        __dirname + '/src/Resources/assets/sass/app.scss',
        __dirname + '/' + publicPath + '/css/velocity.css', {
            includePaths: ['node_modules/bootstrap-sass/assets/stylesheets/'],
        }
    )

    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
