const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 | Google Map js
 | <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .scripts([
        'public/kit/js/core/popper.min.js',
        'public/kit/js/core/bootstrap.min.js',
        'public/kit/js/plugins/moment.min.js',
        'public/kit/js/plugins/bootstrap-switch.js',
        'public/kit/js/plugins/bootstrap-selectpicker.js',
        'public/kit/js/plugins/bootstrap-datetimepicker.js',
        'public/kit/js/plugins/bootstrap-tagsinput.js',
        'public/kit/js/plugins/nouislider.min.js',
        'public/kit/js/now-ui-kit.js',
        'public/dashboard/js/plugins/sweetalert2.min.js',
    ], 'public/js/core/kit.min.js')
    .scripts([
        'public/dashboard/js/plugins/sweetalert2.min.js',
        'public/dashboard/js/plugins/bootstrap-notify.js',
        'public/dashboard/js/plugins/bootstrap-switch.js',
        'public/dashboard/js/plugins/bootstrap-tagsinput.js',
        'public/dashboard/js/plugins/jasny-bootstrap.min.js',
        'public/dashboard/js/plugins/jquery.bootstrap-wizard.js',
        'public/dashboard/js/plugins/bootstrap-selectpicker.js',
        'public/dashboard/js/plugins/bootstrap-datetimepicker.min.js',

        'public/dashboard/js/plugins/chartjs.min.js',
        'public/dashboard/js/plugins/nouislider.min.js',
        'public/dashboard/js/plugins/jquery-jvectormap.js',
        'public/dashboard/js/plugins/fullcalendar.min.js',
        'public/dashboard/js/plugins/jquery.validate.min.js',
    ], 'public/js/core/utilities.min.js')
    .scripts([
        /**
         * Core Js
         */
        'public/dashboard/js/core/bootstrap.min.js',
        'public/dashboard/js/plugins/moment.min.js',
        'public/dashboard/js/plugins/perfect-scrollbar.jquery.min.js',
        /**
         * End core
         */
        /**
         * Demo
         */
        // 'public/dashboard/demo/demo.js',
        'public/dashboard/js/now-ui-dashboard.min.js',
        'public/dashboard/js/plugins/jquery.dataTables.min.js',
    ], 'public/js/core/main.min.js');
