'use strict';
 
const gulp = require('gulp');
const del = require('del');
const browserSync = require('browser-sync').create();
const fs          = require('fs');
const zip         = require('gulp-zip');
const xml2js      = require('xml2js');
const parser      = new xml2js.Parser();
 
const config = require('./gulp-config.json');
const defaultBrowserConfig = {
	proxy : "localhost"
}

const browserConfig = config.hasOwnProperty('browserConfig') ? config.browserConfig : defaultBrowserConfig;

function initBrowserSync() {
	return browserSync.init(browserConfig)
}

function reloadBrowserSync(cb) {
	browserSync.reload({stream:true});
	cb();
}

const moduleName = "mod_phproberto_bs5_navbar";

const nodeModulesPath = './node_modules';
const extensionPath = './extension';
const extensionMediaPath = extensionPath + '/media/mod_phproberto_bs5_navbar';
const extensionAssetsPath = extensionPath + '/assets';
const manifesFileName = moduleName + '.xml';
const wwwPath = config.wwwDir + '/modules/' + moduleName;
const wwwMediaPath = config.wwwDir + '/media/' + moduleName;
const wwwAssetsPath = wwwPath + '/assets';

function copyBootstrapJavascript() {
    return gulp.src([
        nodeModulesPath + '/bootstrap/dist/js/bootstrap.bundle.min.*'
    ])
	.pipe(gulp.dest(extensionMediaPath + '/js'))
}


function copyBootstrapCss() {
    return gulp.src([
        nodeModulesPath + '/bootstrap/dist/css/bootstrap.min.*'
    ])
	.pipe(gulp.dest(extensionMediaPath + '/css'))
}

function clean() {
	return del(wwwPath, {force : true});
}

function cleanMedia() {
	return del(wwwMediaPath, {force : true});
}

function copy() {
	return gulp.src([extensionPath + '/**', '!' + extensionMediaPath + '/**' ],{ base: extensionPath })
		.pipe(gulp.dest(wwwPath))
		.pipe(browserSync.stream());
}

function copyMedia() {
	return gulp.src(extensionMediaPath + '/**',{ base: extensionMediaPath })
		.pipe(gulp.dest(wwwMediaPath))
		.pipe(browserSync.stream());
}

function createZip(cb) {
	return fs.readFile(extensionPath + '/' + manifesFileName, function(err, data) {
		parser.parseString(data, function (err, result) {
			var version = result.extension.version[0];

			var fileName = result.extension.name + '-v' + version + '.zip';

			return gulp.src(extensionPath + '/**',{ base: extensionPath })
				.pipe(zip(fileName))
				.pipe(gulp.dest('releases'))
				.on('end', cb);
		});
	});
}

function watch(cb) {
	gulp.watch(
		[
			extensionPath + '/language/**/*.ini',
			extensionPath + '/tmpl/**/*.php',
			extensionPath + '/*.php',
			extensionPath + '/' + manifesFileName
		], 
		gulp.series(clean, copy)
	);
	cb();
}

exports.release = gulp.series(createZip);
exports.default = gulp.series(
	gulp.parallel(copyBootstrapJavascript, copyBootstrapCss),
	gulp.parallel(clean, cleanMedia),
	gulp.parallel(copy, copyMedia),
    gulp.parallel(watch, initBrowserSync)
);
