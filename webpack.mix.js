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
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .version()
    .vue();

mix.sass('resources/sass/app.scss', 'public/dist/css')
    .sass('resources/sass/user-management-page.scss', 'public/dist/css')
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .version();
