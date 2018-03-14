var gulp = require('gulp');
//var sass = require('gulp-sass'); -- use as required
var minifyCSS    = require('gulp-minify-css');
var browserSync  = require('browser-sync');
var httpProxy    = require('http-proxy');
var php          = require('gulp-connect-php');
var imagemin     = require('gulp-imagemin');

const reload = browserSync.reload;
const jsValidate = require('gulp-jsvalidate');

//---- LOCAL TESTING ENV ACROSS ALL PLATFORMS
gulp.task('browser-sync', function() {
     browserSync.init({
         proxy: "http://testlocal.local:8888"
     });
 });

//---- SASS (IF CSS REQUIRED)
gulp.task('sass', function() {
    return gulp.src('sass/custom.scss')
        .pipe(sass().on('error', sass.logError)) // Converts Sass to CSS with gulp-sass
        .pipe(gulp.dest('assets/css/'))
        .pipe(reload({stream: true}));
});

// ---- JSVALIDATION

gulp.task('jsvalidate', () =>
    gulp.src('assets/js/custom.js')
        .pipe(jsValidate())
);

//---- WATCH FOR CHANGES ON FLY - REFRESH BROWSERS ON ALL PLATFROMS
 gulp.task('watch', ['browser-sync'], function () {
     gulp.watch("sass/**/*.scss", ['sass']);
     gulp.watch("**/*.php").on('change', reload);
     gulp.watch("**/*.js").on('change', reload);
     gulp.watch("classes/*.php").on('change', reload);
     gulp.watch("assets/js/custom.js").on('change', reload);
 });


//---- GULP TASK FOR IMAGES
gulp.task('img-check', () =>
    gulp.src('assets/img/*/**')
        .pipe(imagemin())
        .pipe(gulp.dest('assets/img'))
);


gulp.task('build', ['jsvalidate',  'browser-sync', 'watch', 'img-check']); //'sass' when CSS used

