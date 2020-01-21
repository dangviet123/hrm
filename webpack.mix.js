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
mix.webpackConfig({
    node: {
      fs: "empty"
    },
    externals: [
      {
        './cptable': 'var cptable',
      }
    ]
});

mix.js('resources/js/app.js', 'public/js')
	.js('resources/js/admincp.js', 'public/js')
	.styles([
		'public/admin/vendors/bootstrap/dist/css/bootstrap.min.css',
		'public/admin/vendors/checkbox/checkbox.css',
		'public/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
		'public/admin/vendors/jqvmap/dist/jqvmap.min.css',
		'public/admin/build/css/custom.css'
	],'public/admin/all.css')

	.scripts([
		'public/admin/vendors/jquery/dist/jquery.min.js',
		'public/admin/vendors/bootstrap/dist/js/bootstrap.bundle.min.js',
		'public/admin/vendors/Chart.js/dist/Chart.min.js',
		'public/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
		'public/admin/vendors/moment/min/moment.min.js',
		'public/admin/build/js/custom.js',
		// 'public/admin/vendors/fastclick/lib/fastclick.js',
		// 'public/admin/vendors/gauge.js/dist/gauge.min.js',
		// 'public/admin/vendors/skycons/skycons.js',
		// 'public/admin/vendors/Flot/jquery.flot.js',
		// 'public/admin/vendors/Flot/jquery.flot.pie.js',
		// 'public/admin/vendors/Flot/jquery.flot.time.js',
		// 'public/admin/vendors/Flot/jquery.flot.stack.js',
		// 'public/admin/vendors/Flot/jquery.flot.resize.js',
		// 'public/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js',
		// 'public/admin/vendors/flot-spline/js/jquery.flot.spline.min.js',
		// 'public/admin/vendors/flot.curvedlines/curvedLines.js',
		// 'public/admin/vendors/DateJS/build/date.js',
		// 'public/admin/vendors/jqvmap/dist/jquery.vmap.js',
		// 'public/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js',
		// 'public/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js',
		
	],'public/admin/all.js')
   .sass('resources/sass/app.scss', 'public/css');

