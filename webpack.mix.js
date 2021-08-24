const mix = require('laravel-mix');
mix.disableSuccessNotifications();
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/dist/js')
    .js('resources/js/admin/user-management.js', 'public/dist/js')
    .js('resources/js/home.js', 'public/dist/js')
    .js('resources/js/create-edit-resource.js', 'public/dist/js')

    .extract([
        'jquery', 'bootstrap'
    ])
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .version()
    .vue();

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery']
});

mix.sass('resources/sass/app.scss', 'public/dist/css')
    .sass('resources/sass/user-management-page.scss', 'public/dist/css')
    .sass('resources/sass/footer.scss', 'public/dist/css')
    .sass('resources/sass/home.scss', 'public/dist/css')
    .sass('resources/sass/resources-packages-index.scss', 'public/dist/css')
    .sass('resources/sass/communication-cards-create-edit.scss', 'public/dist/css')
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .version();
