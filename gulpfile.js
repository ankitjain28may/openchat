var gulp = require('gulp'),
    gutil = require('gulp-util'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat');
    connect = require('gulp-connect-php'),
    browserSync = require('browser-sync'),
    rename = require('gulp-rename');

var jsSources = ['js/*.js'],
    phpSources = ['*.php'];





gulp.task('log', function() {
  gutil.log('== My First Task ==')
});

// gulp.task('copy', function() {
//   gulp.src('index.html')
//   .pipe(gulp.dest(outputDir))
// });

// gulp.task('sass', function() {
//   gulp.src(sassSources)
//   .pipe(sass({style: 'expanded'}))
//     .on('error', gutil.log)
//   .pipe(gulp.dest('assets'))
//   .pipe(connect.reload())
// });

// gulp.task('coffee', function() {
//   gulp.src(coffeeSources)
//   .pipe(coffee({bare: true})
//     .on('error', gutil.log))
//   .pipe(gulp.dest('scripts'))
// });

// gulp.task('js', function() {
//   gulp.src('js/index.js')
//   .pipe(concat('script.js'))
//   .pipe(rename({ suffix: '.min' }))
//   .pipe(uglify())
//   .pipe(gulp.dest('js'))
// });

// gulp.task('js2', function() {
//   gulp.src('js/index.js')
//   .pipe(concat('script.js'))
//   .pipe(gulp.dest('js'))
//   .pipe(rename({ suffix: '.min' }))
//   .pipe(uglify())
//   .pipe(gulp.dest('js'))
// });



// gulp.task('watch', function() {

//   livereload.listen();
//   // gulp.watch('*.php').on('change', livereload.reload);

//     gulp.watch('*.php').on('change', function(file) {
//           gutil.log("Changed" + file.path);
//           livereload.changed(file.path);
//       });

// });


// gulp.task('connect', function() {
//   connectPHP.server({
//     hostname: '127.0.0.1',
//     bin: 'C:/xampp/php/php.exe',
//     ini: 'C:/xampp/php/php.ini',
//     port: 3000,
//     base: '.',
//     livereload: true
//   // }, function () {
//   //         browserSync.init({
//   //           proxy: "localhost:3000"
//   //   });
//   });
// });




gulp.task('connect', function() {
  connect.server({
    hostname: '127.0.0.1',
    port: 3000,
    base: '.'
  }, function() {
    browserSync({
      proxy: '127.0.0.1:3000',
      port: 8888
  });
  });
});

gulp.task('watch', function() {
  gulp.watch(['views/*.php']).on('change', browserSync.reload);
});


//task that fires up browserSync proxy after connect server has started
// gulp.task('browser-sync',['connect'], function() {

// });


//default task that runs task browser-sync ones and then watches php files to change. If they change browserSync is reloaded
// gulp.task('default', ['browser-sync'], function () {
//   gulp.watch(['*.php'], browserSync.reload);
// });

gulp.task('default', ['connect', 'watch']);