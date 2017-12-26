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

mix.sass('resources/assets/sass/chikaplus.scss', 'public/css')
   .sass('resources/assets/sass/wysiwyg.scss', 'public/css')
   .js('resources/assets/js/home.js', 'public/js')
   .js('resources/assets/js/list.js', 'public/js')
   .js('resources/assets/js/show.js', 'public/js')
   .js('resources/assets/js/create.js', 'public/js')
   .js('resources/assets/js/edit.js', 'public/js');

if (mix.inProduction()) {
    mix.version();
}