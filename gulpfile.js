var gulp = require('gulp');
var chug = require('gulp-chug');

//Call to minify function from public/gulp-minifier.js
gulp.task('minify', function() {
    gulp.src( './public/gulp-minifier.js' )
        .pipe(chug( {
            tasks: ['minify']
        } ))
});


//Call to concat function from public/gulp-minifier.js
gulp.task('concat', function() {
    gulp.src( './public/gulp-minifier.js' )
        .pipe(chug( {
            tasks: ['concat']
        } ))
});


