var gulp = require('gulp');
var ts = require('gulp-typescript');

gulp.task('default', function () {
    return gulp.src('public/static/js/*.ts')
        .pipe(ts({
            noImplicitAny: true,
            lib:['ES2015']
        }))
        .pipe(gulp.dest('public/static/js'));
});