const gpath       = require('./gulpfile.path.js');
const gdel        = require('./gulpfile.del.js');
const gulp        = require('gulp');
const watch       = require('gulp-watch');
const rename      = require('gulp-rename');
const shell       = require('gulp-shell');
const pump        = require('pump');
const removeFiles = require('gulp-remove-files');
const del         = require('del');
const runSequence = require('run-sequence');
const assets      = gpath.assetsVendorResource(false, null);

gulp.task('move-public-only', function () {
    return gulp.src(['./raw/public/**', '!./raw/public/**'], {dot: true, base: './raw/public/'})
        .pipe(gulp.dest('./public/'));
});

gulp.task('move-public-assets', function () {
    return gulp.src(['./raw/public/**',
        '!./raw/public/**/*.{js,css,json,png,jpg,jpeg,gif,svg}'
    ], {dot: true, base: './raw/public/'})
        .pipe(gulp.dest('./public/'));
});

gulp.task('move-public-minified-assets', function () {
    return gulp.src(['./raw/public/**/*.min.{js,css,json}'], {dot: true, base: './raw/public/'})
        .pipe(gulp.dest('./public/'));
});

gulp.task('minify-public-img', function () {
    return gulp.src(['./raw/public/**/*.{png,jpg,jpeg,gif,svg}'], {dot: true, base: './raw/public/'})
        .pipe(gulp.dest('./public/'));
});

gulp.task('minify-public-js', function (cb) {
    pump([
            gulp.src(['./raw/public/**/*.js'], {dot: true, base: './raw/public/'})
                .pipe(rename({suffix: ".min", extname: ".js"})),
            gulp.dest('./public/')],
        cb
    );
});

gulp.task('minify-public-css', function () {
    return gulp.src(['./raw/public/**/*.css'], {dot: true, base: './raw/public/'})
        .pipe(rename({suffix: ".min", extname: ".css"}))
        .pipe(gulp.dest('./public/'));
});

gulp.task('minify-public-json', function () {
    return gulp.src(['./raw/public/**/*.json'], {dot: true, base: './raw/public/'})
        .pipe(rename({suffix: ".min", extname: ".json"}))
        .pipe(gulp.dest('./public/'));
});

gulp.task('minify-public-xlsx', function () {
    return gulp.src(['./raw/public/**/*.xlsx'], {dot: true, base: './raw/public/'})
        .pipe(gulp.dest('./public/'));
});

gulp.task('minify-resources-views', function () {
    return gulp.src(['./raw/resources/views/**/*.{php,blade.php,html}'], {dot: true, base: './raw/resources/views/'})
        .pipe(gulp.dest('./resources/views/'));
});

gulp.task('move-public-assets-vendor', function () {
    return gulp.src(assets.concat(gpath.assetsVendorResource(true, '*.{js,css,json,png,jpg,jpeg,gif,svg}')), {
        dot: true,
        base: './node_modules/'
    })
        .pipe(gulp.dest('./public/vendor/'));
});

gulp.task('move-public-minified-assets-vendor', function () {
    return gulp.src(gpath.assetsVendorResource(false, '*.min.{js,css,json}'), {dot: true, base: './node_modules/'})
        .pipe(gulp.dest('./public/vendor/'));
});

gulp.task('minify-public-assets-vendor-img', function () {
    return gulp.src(gpath.assetsVendorResource(false, '*.{png,jpg,jpeg,gif,svg}'), {dot: true, base: './node_modules/'})
        .pipe(gulp.dest('./public/vendor/'));
});

gulp.task('minify-public-assets-vendor-js', function (cb) {
    pump([
            gulp.src(gpath.assetsVendorResource(false, '*.js'), {dot: true, base: './node_modules/'})
                .pipe(rename({suffix: ".min", extname: ".js"})),
            gulp.dest('./public/vendor/')],
        cb
    );
});

gulp.task('minify-public-assets-vendor-css', function () {
    return gulp.src(gpath.assetsVendorResource(false, '*.css'), {dot: true, base: './node_modules/'})
        .pipe(rename({suffix: ".min", extname: ".css"}))
        .pipe(gulp.dest('./public/vendor/'));
});

gulp.task('minify-public-assets-vendor-json', function () {
    return gulp.src(gpath.assetsVendorResource(false, '*.json'), {dot: true, base: './node_modules/'})
        .pipe(rename({suffix: ".min", extname: ".json"}))
        .pipe(gulp.dest('./public/vendor'));
});

gulp.task('minify-everything-light', function (callback) {
    runSequence('move-public-only',
        'move-public-assets',
        'move-public-minified-assets',
        ['minify-public-img', 'minify-public-js', 'minify-public-css', 'minify-public-json'],
        'minify-resources-views',
        callback);
});

gulp.task('minify-everything-hard', function (callback) {
    runSequence('move-public-assets-vendor', 'move-public-minified-assets-vendor',
        ['minify-public-assets-vendor-img', 'minify-public-assets-vendor-js', 'minify-public-assets-vendor-css', 'minify-public-assets-vendor-json'],
        'generate-asset',
        callback);
});

gulp.task('minify-everything', function (callback) {
    runSequence('minify-everything-light',
        'minify-everything-hard',
        callback);
});

gulp.task('generate-asset', shell.task('npm run dev'));

gulp.task('watch-public-js', function () {
    var path = ['./raw/public/**/*.js'];
    return watch(path, function (cb) {
        pump([
                gulp.src(path, {dot: true, base: './raw/public/'})
                    .pipe(rename({suffix: ".min", extname: ".js"})),
                gulp.dest('./public/')],
            cb
        );
    });
});

gulp.task('watch-public-css', function () {
    var path = ['./raw/public/**/*.css'];
    return watch(path, function () {
        return gulp.src(path, {dot: true, base: './raw/public/'})
            .pipe(rename({suffix: ".min", extname: ".css"}))
            .pipe(gulp.dest('./public/'));
    });
});

gulp.task('watch-resources-views', function () {
    var path = ['./raw/resources/views/**/*.{php,blade.php,html}'];
    return watch(path, function () {
        return gulp.src(path, {dot: true, base: './raw/resources/views/'})
            .pipe(gulp.dest('./resources/views/'));
    });
});

gulp.task('minify-and-watch', function (callback) {
    runSequence(['minify-public-js',
            'minify-public-css',
            'minify-resources-views'],
        ['watch-public-js',
            'watch-public-css',
            'watch-resources-views'],
        callback);
});

gulp.task('cleaning-generated-file-light', function () {
    return del(gdel.delLight(), {dot: true});
});

gulp.task('cleaning-generated-file-hard', function () {
    return del(gdel.delHard(), {dot: true});
});

gulp.task('clean-everything', function (callback) {
    runSequence('cleaning-generated-file-light',
        'cleaning-generated-file-hard',
        callback);
});
