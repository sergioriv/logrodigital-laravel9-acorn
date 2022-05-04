const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')

    .sass("resources/sass/styles.scss", "public/css")
    .options({
        processCssUrls: false,
    })
    .copy("resources/favicon.ico", "public/favicon.ico")
    .copy("resources/css", "public/css")
    .copy("resources/font", "public/font")
    .copy("resources/img", "public/img")
    .copy("resources/js", "public/js")
    .copy("resources/icon", "public/icon")
    .copy("resources/json", "public/json")

if (mix.inProduction()) {
    mix.version();
};
