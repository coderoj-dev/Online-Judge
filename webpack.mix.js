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
 */

var libDir = [
	
];

var libJs = [
	'resources/assets/lib/jquery/jquery3.4.1.min.js',
	'resources/assets/lib/bootstrap/js/bootstrap.min.js',
 ];

var helperJs = [
 	'resources/assets/js/script.js',
	'resources/assets/js/helper/button.js',
	'resources/assets/js/helper/toast.js',
	'resources/assets/js/helper/url.js',
	'resources/assets/js/helper/preload.js',
	'resources/assets/js/helper/div.js',
	'resources/assets/js/helper/modal.js',
	'resources/assets/js/helper/form.js',
];

var appJs = [
	'resources/assets/js/auth/auth.js',
	'resources/assets/js/problem/problem_dashboard.js',
	'resources/assets/js/problem/problem_details_editor.js',
	'resources/assets/js/profile/profile.js',
	'resources/assets/js/language/language.js',
	'resources/assets/js/submission/submission_editor.js',
	'resources/assets/js/submission/submission.js',
	'resources/assets/js/moderator/moderator_request.js',
	'resources/assets/js/checker/checker.js',
	'resources/assets/js/country/country.js',
	'resources/assets/js/city/city.js',
];

var scripts = [].concat(libJs,helperJs,appJs);

mix.scripts(scripts, 'public/js/app.js').version();
