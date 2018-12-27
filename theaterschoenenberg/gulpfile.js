var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');

// Directories
// -----------------
const settings = {
    publicDir: '.',
    sassDir: 'assets/sass',
    cssDir: 'assets/css'
};

// Development Tasks
// -----------------

gulp.task('sass', function() {
    return gulp.src(settings.sassDir + "/**/*.scss") // Gets all files ending with .scss in app/scss and children dirs
        .pipe(sass()) // Passes it through a gulp-sass
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
            // cascade: false
        })) // spezifische browser css anpassungen
        .pipe(sourcemaps.write('.')) // inconsole log verweis auf scss quell file
        .pipe(gulp.dest(settings.cssDir)); // Outputs it in the css folder

});

gulp.task('css', function() {
    return gulp.src(settings.cssDir + '/**/*.css');
});

// Watchers
gulp.task('watch', function() {
    gulp.watch(settings.sassDir + "/**/*.scss", ['sass']);
    gulp.watch(settings.cssDir + '/**/*.css', ['css']);
});
