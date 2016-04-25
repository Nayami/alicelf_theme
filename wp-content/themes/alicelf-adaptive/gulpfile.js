// npm install --save-dev gulp
// npm install --save-dev gulp-concat
// npm install --save-dev gulp-rename
// npm install --save-dev gulp-uglify
// npm install --save-dev gulp-sourcemaps

// rimraf node_modules

var gulp = require('gulp'),
	aa_concat = require('gulp-concat'),
	aa_rename = require('gulp-rename'),
	aa_uglify = require('gulp-uglify'),
	aa_sourcemaps = require('gulp-sourcemaps');

// ================== Main working scope ==================
gulp.task('aa-concat', function() {
	return gulp.src([
			// Ajax
			'js_dev/_js/ajax.js',
			// Site script
			'js_dev/_js/script.js'

		])
		.pipe(aa_sourcemaps.init())
		.pipe(aa_concat('concat.js'))
		.pipe(gulp.dest('js_dev/_js'))
		.pipe(aa_rename('uglify.js'))
		.pipe(aa_uglify())
		.pipe(aa_sourcemaps.write('./'))
		.pipe(gulp.dest('js_prod'));
});

/**
 * ==================== Libs ======================
 * Third party plugins and scripts
 * 2/28/2016
 */
gulp.task('plugins', function() {
	return gulp.src([
			//'js_dev/_bootstrap/affix.js',
			'js_dev/_bootstrap/alert.js',
			//'js_dev/_bootstrap/button.js',
			'js_dev/_bootstrap/carousel.js',
			'js_dev/_bootstrap/collapse.js',
			'js_dev/_bootstrap/dropdown.js',
			//'js_dev/_bootstrap/modal.js',
			//'js_dev/_bootstrap/tooltip.js',
			//'js_dev/_bootstrap/popover.js',
			//'js_dev/_bootstrap/scrollspy.js',
			'js_dev/_bootstrap/tab.js',
			'js_dev/_bootstrap/transition.js',

			'js_dev/_js/smooth-scroll.js',
			'js_dev/_js/progressbar/progressbar.min.js',
			'style-parts/transformicons/transformicons.js',

		])
		.pipe(aa_sourcemaps.init())
		.pipe(aa_concat('compiled-plugins-script.js'))
		.pipe(aa_uglify())
		.pipe(aa_sourcemaps.write('./'))
		.pipe(gulp.dest('js_prod'));
});


gulp.task('nonuglified-scripts', function() {
	return gulp.src([

			// ScrollMagic
			'js_dev/_scrollmagic/tween-max.1.14.2.js',
			'js_dev/_scrollmagic/scrollmagic.2.0.5.js',
			'js_dev/_scrollmagic/indications.js', // commented
			'js_dev/_scrollmagic/animation.gsap.js'

		])
		.pipe(aa_sourcemaps.init())
		.pipe(aa_concat('nonuglify-concat.js'))
		.pipe(aa_rename('non-uglified.js'))
		.pipe(aa_sourcemaps.write('./'))
		.pipe(gulp.dest('js_prod'));
});


gulp.task('watch', function() {
	gulp.watch(['js_dev/_js/ajax.js', 'js_dev/_js/script.js'], ['aa-concat']);
});

// @Template Todo: create watch task

gulp.task('default', ['aa-concat'], function() {
});