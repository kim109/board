let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/freeboard/create.js', 'public/js/freeboard')
   .js('resources/assets/js/freeboard/edit.js', 'public/js/freeboard')
   .js('resources/assets/js/show.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/market/create.js', 'public/js/market');

if (mix.inProduction()) {
    mix.version();
}