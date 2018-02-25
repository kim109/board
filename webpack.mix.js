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

mix.js('resources/assets/js/qna/list.js', 'public/js/qna')
   .js('resources/assets/js/qna/show.js', 'public/js/qna')
   .js('resources/assets/js/qna/answer.js', 'public/js/qna');

mix.js('resources/assets/js/columns/list.js', 'public/js/columns')
   .js('resources/assets/js/columns/create.js', 'public/js/columns')
   .js('resources/assets/js/columns/edit.js', 'public/js/columns');

mix.js('resources/assets/js/seminars/list.js', 'public/js/seminars')
   .js('resources/assets/js/seminars/create.js', 'public/js/seminars')
   .js('resources/assets/js/seminars/edit.js', 'public/js/seminars');

if (mix.inProduction()) {
    mix.version();
}