let mix = require('laravel-mix');

require('laravel-mix-merge-manifest');

const publicPath = mix.inProduction()
                   ? `publishable/assets`
                   : `../../../public/themes/velocity/assets`;

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix
    .js(`${__dirname}/src/Resources/assets/js/app-core.js`, 'js/velocity-core.js')
    .js(`${__dirname}/src/Resources/assets/js/app.js`,      'js/velocity.js')
    .vue()

    .copy(`${__dirname}/src/Resources/assets/images`, `${publicPath}/images`)

    .sass(
        `${__dirname}/src/Resources/assets/sass/admin.scss`,
        `${__dirname}/${publicPath}/css/velocity-admin.css`
    )
    .sass(
        `${__dirname}/src/Resources/assets/sass/app.scss`,
        `${__dirname}/${publicPath}/css/velocity.css`,
        {
            sassOptions: {
                includePaths: [ 'node_modules/bootstrap-sass/assets/stylesheets/' ],
            },
        }
    )
    .options({
                 processCssUrls: false,
    });

if (mix.inProduction()) {
    mix.version();
}
