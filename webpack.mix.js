let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.version();

mix.sass('resources/assets/sass/app.scss', 'public/css').sourceMaps();
mix.copy('node_modules/font-awesome/fonts', 'public/fonts');
mix.copy('node_modules/font-awesome/css/font-awesome.css', 'public/css');

// application
mix.scripts([
    'node_modules/jquery/dist/jquery.js',
    'resources/assets/js/sortables.js',
    'resources/assets/js/app.js'
], 'public/js/app.js');

mix.js('resources/assets/js/pages/machines.js', 'public/js/pages');
mix.js('resources/assets/js/pages/profile.js', 'public/js/pages');
mix.js('resources/assets/js/pages/programs.js', 'public/js/pages');