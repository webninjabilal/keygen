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

mix.styles([
    'resources/assets/css/jquery-ui.min.css',
    'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/font-awesome.min.css',
    'resources/assets/css/animate.css',
    'resources/assets/css/toastr.min.css',
    'resources/assets/css/select2.min.css',
    'resources/assets/css/datatables.min.css',
    'resources/assets/css/style.css',
    'resources/assets/css/datepicker3.css',
    'resources/assets/css/bootstrap-datetimepicker.min.css',
    'resources/assets/css/extra.css'
],'public/css/app.css').version();

mix.scripts([
    'resources/assets/js/jquery-3.1.1.min.js',
    'resources/assets/js/jquery-migrate-3.0.0.js',
    'resources/assets/js/jquery-ui.min.js',
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/js/jquery.metisMenu.js',
    'resources/assets/js/jquery.slimscroll.min.js',
    'resources/assets/js/toastr.min.js',
    'resources/assets/js/clipboard.min.js',
    'resources/assets/js/select2.min.js',
    'resources/assets/js/site.js',
    'resources/assets/js/pace.min.js',
    'resources/assets/js/datatables.min.js',
    'resources/assets/js/bootstrap-datepicker.js',
    'resources/assets/js/bootstrap-datetimepicker.min.js',
    'resources/assets/js/extra.js',
],'public/js/app.js').version();
