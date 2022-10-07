const path = require('path');
const mix = require('laravel-mix');

require('laravel-mix-merge-manifest');
require('laravel-mix-clean');

const prodPublicPath = path.join('publishable', 'assets');
const devPublicPath = path.join(
    '..',
    '..',
    '..',
    'public',
    'themes',
    'velocity',
    'assets'
);
const publicPath = mix.inProduction() ? prodPublicPath : devPublicPath;

console.log(`Assets will be published in: ${publicPath}`);

const assetsPath = path.join(__dirname, 'src', 'Resources', 'assets');
const jsPath = path.join(assetsPath, 'js');
const imagesPath = path.join(assetsPath, 'images');

mix.setPublicPath(publicPath)
    .js(path.join(jsPath, 'jquery-ez-plus.js'), 'js/jquery-ez-plus.js')
    .js(path.join(jsPath, 'app-core.js'), 'js/velocity-core.js')
    .js(path.join(jsPath, 'app.js'), 'js/velocity.js')
    .vue()

    .alias({
        '@components': path.join(jsPath, 'UI', 'components'),
    })
    .extract({
        to: `/js/components.js`,
        test(mod) {
            return /(component|style|loader|node)/.test(mod.nameForCondition());
        },
    })

    .copy(imagesPath, path.join(publicPath, 'images'))

    .sass(
        path.join(assetsPath, 'sass', 'admin.scss'),
        path.join(__dirname, publicPath, 'css', 'velocity-admin.css')
    )
    .sass(
        path.join(assetsPath, 'sass', 'app.scss'),
        path.join(__dirname, publicPath, 'css', 'velocity.css'),
        {
            sassOptions: {
                includePaths: [
                    'node_modules/bootstrap-sass/assets/stylesheets/',
                ],
            },
        }
    )

    .clean({
        cleanOnceBeforeBuildPatterns: [
            'js/**/*',
            'css/velocity.css',
            'css/velocity-admin.css',
            'mix-manifest.json',
        ],
    })

    .options({
        processCssUrls: false,
        clearConsole: mix.inProduction(),
    })

    .disableNotifications()
    .mergeManifest();

if (mix.inProduction()) {
    mix.version();
}
