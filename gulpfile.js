var gulp = require("gulp"),
    gutil = require("gulp-util"),
    uglify = require("gulp-uglify"),
    concat = require("gulp-concat"),
    connect = require("gulp-connect-php"),
    browserSync = require("browser-sync"),
    del = require("del"),
    rename = require("gulp-rename"),
    cssnano = require("gulp-cssnano");

gulp.task("log", function() {
  gutil.log("== My First Task ==")
});


gulp.task("js", function() {
  gulp.src(["public/assests/js/jquery-3.0.0.min.js", "public/assests/js/index.js", "public/assests/js/handlebars.min.js", "public/assests/js/moment.min.js"])
  .pipe(concat("script.js"))
  .pipe(rename({ suffix: ".min" }))
  .pipe(uglify())
  .pipe(gulp.dest("public/dist/js"))
  .on("change", browserSync.reload);
});

gulp.task("css", function() {
  gulp.src(["public/assests/css/style.css"])
  .pipe(concat("style.css"))
  .pipe(rename({ suffix: ".min" }))
  .pipe(cssnano())
  .pipe(gulp.dest("public/dist/css"))
  .on("change", browserSync.reload);
});

gulp.task("connect", function() {
  connect.server({
    hostname: "127.0.0.1",
    port: 3000,
    base: "."
  },function() {
      browserSync({
        proxy: "127.0.0.1:3000",
        port: 8888
      });
  });
});

gulp.task("watch", function() {
  gulp.watch("public/assets/js/*.js", ["js"]);
  gulp.watch(["views/*.php"]).on("change", browserSync.reload);
});

// cleaning build process- run clean before deploy and rebuild files again
gulp.task("clean", function() {
    return del(["public/dist/js", "public/dist/css"], { force: true });
});


gulp.task("default", ["clean","css", "js", "connect", "watch"]);