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

mix
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/index.js', 'public/js')
  .postCss('resources/css/app.css', 'public/css')
  .sass('resources/scss/style.scss', 'public/css')
  .disableNotifications()


  .browserSync({
    proxy: 'localhost:8000', // Укажите ваш локальный адрес
    files: [
      'app/**/*.php',
      'resources/views/**/*.blade.php',
      'public/js/**/*.js',
      'public/css/**/*.css'
    ]
  })
  .sourceMaps();
