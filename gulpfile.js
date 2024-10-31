'use strict';

var browserify = require('browserify');
var gulp = require('gulp');
var del = require('del');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var babel = require('gulp-babel');
var babelify = require('babelify');
var log = require('gulplog');
var inject = require('gulp-inject-string');

const paths = {
	src:	"./js/src/**/*.js"
};

function js() {
    var b = browserify({
		entries: './js/src/entry.js',
		debug: true
	}).transform(babelify, {
		presets : [ '@babel/preset-env' ]
	});
	
	del("./js/dist/**/*");

    return b.bundle()
		.pipe(source('entry.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({
			loadMaps: true
		}))
		// Add transformation tasks to the pipeline here.
		// .pipe(uglify())
		.on('error', log.error)
		.pipe(inject.prepend("jQuery(function($) {"))
		.pipe(inject.append("});"))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('./js/dist/'));
}

const watch = () => gulp.watch(paths.src, js);
const dev = gulp.series(js, watch);

exports.default = dev;
