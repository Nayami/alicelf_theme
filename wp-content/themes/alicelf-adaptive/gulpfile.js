var gulp = require('gulp'),
	aa_concat = require('gulp-concat'),
	aa_rename = require('gulp-rename'),
	aa_uglify = require('gulp-uglify'),
	aa_sourcemaps = require('gulp-sourcemaps');

gulp.task('aa-concat', function(){
	return gulp.src([
		//'js_dev/_bootstrap/affix.js',
		'js_dev/_bootstrap/alert.js',
		//'js_dev/_bootstrap/button.js',
		'js_dev/_bootstrap/carousel.js',
		'js_dev/_bootstrap/collapse.js',
		'js_dev/_bootstrap/dropdown.js',
		//'js_dev/_bootstrap/modal.js',
		//'js_dev/_bootstrap/popover.js',
		//'js_dev/_bootstrap/scrollspy.js',
		//'js_dev/_bootstrap/tab.js',
		//'js_dev/_bootstrap/tooltip.js',
		'js_dev/_bootstrap/transition.js',

		// Third Party Scripts
		'js_dev/_js/smooth-scroll.js',
		'js_dev/_js/progressjs/progress.min.js',

		// ScrollMagic
		//'js_dev/_scrollmagic/tween-max.1.14.2.js',
		//'js_dev/_scrollmagic/scrollmagic.2.0.5.js',
		//'js_dev/_scrollmagic/indications.js',
		//'js_dev/_scrollmagic/animation.gsap.js',

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

// @Template Todo: create watch task

gulp.task('default', ['aa-concat'], function(){});