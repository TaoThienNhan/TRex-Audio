var gulp = require('gulp'),
	sass = require('gulp-dart-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	uglify = require('gulp-uglify'),
	cleanCSS = require('gulp-clean-css'),
	plumber = require('gulp-plumber'),
	squoosh = require('gulp-squoosh'),
	path = require('path'),
	gulpif = require('gulp-if');

gulp.task('scripts', function () {
	return gulp.src([
		'assets/src/**/*.js',
	])
		.pipe(uglify())
		.pipe(gulp.dest('assets/dist/'))
});

gulp.task('styles', function () {
	return gulp.src('assets/src/**/*.{css,scss,sass}')
		.pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
		.pipe(autoprefixer('last 2 versions'))
		.pipe(gulp.dest('assets/dist/'))
		.pipe(cleanCSS('level: 2'))
		.pipe(gulp.dest('assets/dist/'))
});

gulp.task('fonts', function () {
	return gulp.src('assets/src/**/*.{eot,otf,ttf,woff,woff2,svg}')
		.pipe(plumber())
		.pipe(gulp.dest('assets/dist/'))
});

gulp.task('images', function (done) {
	gulp.src('assets/src/**/*.{gif,ico,jpg,jpeg,png,webp}')
		.pipe(plumber({errorHandler: true}))
		.pipe(gulpif('*.gif', gulp.dest('assets/dist/')))
		.pipe(
			squoosh(({width, height, size, filePath}) => ({
				encodeOptions: {
					...(path.extname(filePath) === ".png"
						? {oxipng: {}}
						: {mozjpeg: {}}),
				},
			}))
		)
		.pipe(gulp.dest('assets/dist/'))
	done();
});

gulp.task('prod', gulp.parallel(
	'scripts',
	'styles',
	'fonts',
	'images'
));
