const mix = require('laravel-mix');
const path = require('path');

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

mix.combine([
  'resources/js/plugins/ckeditor.js',
  'resources/js/plugins/moment.min.js',
  'resources/js/core/ConfirmModal.js',
  'resources/js/core/DataTable.js',
  'resources/js/core/FormRequest.js',
  'resources/js/core/Modal.js',
  'resources/js/core/MultipleInputs.js',
  'resources/js/core/FileUploader.js',
  'resources/js/common/main.js',
  'resources/js/common/notifications-bell.js',
], 'public/js/dashboard/bundle.js').minify('public/js/dashboard/bundle.js');

mix.js('resources/js/app.js', 'public/js')
  .js('resources/js/dashboard/chat.js', 'public/js/dashboard')
  .js('resources/js/dashboard/dashboard-app.js', 'public/js/dashboard')
  .js('resources/js/dashboard/department/hub.js', 'public/js/dashboard/department')
  .js('resources/js/dashboard/task/board.js', 'public/js/dashboard/task')
  .sass('resources/sass/dashboard/dashboard-app.scss', 'public/css/dashboard', [])
  .sass('resources/sass/app.scss', 'public/css')
  .version();

// laravel-echo@2 "main" is echo.common.js; Webpack leaves bare `exports` and breaks in the browser.
// Force the ESM build so the bundle wraps it correctly.
mix.webpackConfig({
  resolve: {
    alias: {
      'laravel-echo$': path.resolve(__dirname, 'node_modules/laravel-echo/dist/echo.js'),
    },
  },
});
