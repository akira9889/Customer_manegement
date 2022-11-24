const { src, dest, watch, series, parallel } = require('gulp');
const loadPlugins = require('gulp-load-plugins');
const $ = loadPlugins();
// const pkg = require('./package.json');
// const conf = pkg["gulp-config"];
// const sizes = conf.sizes;
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('autoprefixer');
const cssdeclsort = require('css-declaration-sorter');
const del = require('del');
const browserSync = require('browser-sync');
const server = browserSync.create();
const connect = require('gulp-connect-php');

function clean() {
  return del(['./public']);
}

function extras() {
  return src('./html/*.html')
    .pipe(dest('./public'));
}

function htmlClean() {
  return del(['./public/*.html']);
}

function php() {
  return src('./php/**/*.php')
    .pipe(dest('./public'));
}

function phpClean() {
  return del(['./public/*.php']);
}

function styles() {
  return src('./sass/**/*.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sassGlob())
    .pipe(sass())
    .pipe($.postcss([
      autoprefixer(),
      cssdeclsort({ order: 'alphabetical' })
    ]))
    .pipe($.sourcemaps.write('.'))
    .pipe(dest('./public/css'));
}


function cssClean() {
  return del(['./public/css']);
}

function scripts() {
  return src('./js/*.js')
    .pipe($.sourcemaps.init())
    .pipe($.babel())
    .pipe($.sourcemaps.write('.'))
    .pipe(dest('./public/js'));
}


function jsClean() {
  return del(['./public/js']);
}

// function lint() {
//   return src('./js/*.js')
//     .pipe($.eslintNew({ fix: true }))
//     .pipe($.eslintNew.format())
//     .pipe($.eslintNew.failAfterError())
//     .pipe(dest('./js'))
// }

function startAppServer() {
  //-------------------------------------
  // Start a Browsersync static file server
  //-------------------------------------

  // server.init({
  //   server: {
  //     baseDir: './public',
  //     index: "./index.html" //ブラウザに反映させるファイル
  //   }
  // });

  //-------------------------------------
  // Start a Browsersync proxy
  //-------------------------------------

  connect.server({
    port: 8000,
    base: './public',
  }, function () {
    server.init({
      proxy: 'localhost:8000'
    });
  });

  // watch('./php/**/*.php', series(phpClean, php));
  // watch('./sass/**/*.scss', series(cssClean, styles));
  watch('./sass/**/*.scss', series(styles));
  watch('./js/*.js', series(jsClean, scripts));
  watch(['./sass/**/*.scss',
    './js/*.js',
    './html/*.html',
    './php/*.php'
  ]).on('change', server.reload);
}

// const build = series(parallel(extras, php, styles, series(lint, scripts)));
const build = series(parallel(php, styles, series(scripts)));
const serve = series(build, startAppServer);

exports.extras = extras;
exports.php = php;
exports.styles = styles;
exports.scripts = scripts;
// exports.lint = lint;
exports.clean = clean;
exports.htmlClean = htmlClean;
exports.phpClean = phpClean;
exports.csslean = cssClean;
exports.jsClean = jsClean;
exports.serve = serve;
exports.default = serve;
