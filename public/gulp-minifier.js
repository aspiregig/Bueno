var gulp = require('gulp');
var minifycss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var stripCssComments = require('gulp-strip-css-comments');
var stripJsComments = require('gulp-strip-comments');

var fs = require('fs');
var json_data = JSON.parse(fs.readFileSync('config.development.json', 'utf8'));

//gulp.task('default', ['minify', 'concat']);

gulp.task('minify', ['minify-css', 'minify-js']);
gulp.task('concat', ['concat-css', 'concat-js']);

/*Concartinate all minified files 
 from css and css/minified folder
 into one file named all.css
 and save it in css folder.*/
gulp.task('concat-css', function() {
    return gulp.src(json_data['css_includes'])
        .pipe(concat('all.css'))
        .pipe(gulp.dest('css'));
});


/*Create minified version 
 of non-minified css files
 and save them in
 css/minified folder.*/
gulp.task('minify-css', function() {
    return gulp.src(['css/all.css'])
        .pipe(stripCssComments().on('error', errorHandler))
        .pipe(autoprefixer('last 2 versions').on('error', errorHandler))
        .pipe(minifycss().on('error', errorHandler))
        .pipe(gulp.dest('css').on('error', errorHandler));
});


/*Concartinate all minified files 
 from js and js/minified folder
 into one file named all.js
 and save it in js folder.*/
gulp.task('concat-js', function() {
    return gulp.src(json_data['js_includes'])
        .pipe(concat('all.js'))
        .pipe(gulp.dest('js'));
});


/*Create minified version 
 of non-minified js files
 and save them in
 js/minified folder.*/
gulp.task('minify-js', function() {
    return gulp.src(['js/all.js'])
        .pipe(stripJsComments().on('error', errorHandler))
        .pipe(uglify().on('error', errorHandler))
        .pipe(gulp.dest('js').on('error', errorHandler));
});

// Handle the error
function errorHandler (error) {
    console.log(error.toString());
    this.emit('end');
}