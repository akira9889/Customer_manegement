const { src, dest, watch, series, parallel } = require('gulp');
const loadPlugins = require('gulp-load-plugins');
const $ = loadPlugins();
// const pkg = require('./package.json');
// const conf = pkg["gulp-config"];
// const sizes = conf.sizes;
const mozjpeg = require('imagemin-mozjpeg');
const pngquant = require('imagemin-pngquant');
const sass = require('gulp-sass')(require('sass'));
const eslint = require('gulp-eslint-new');
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
  return del(['./public/**/*.php']);
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
    .pipe(dest('./public/assets/css'));
}


function cssClean() {
  return del(['./public/assets/css']);
}

function scripts() {
  return src('./js/*.js')
    .pipe($.sourcemaps.init())
    .pipe($.babel())
    .pipe($.sourcemaps.write('.'))
    .pipe(dest('./public/assets/js'));
}


function jsClean() {
  return del(['./public/assets/js']);
}

function lint() {
  return src('./js/*.js')
    .pipe(eslint({ fix: true }))
    .pipe(eslint.format())
    .pipe(eslint.failAfterError())
    .pipe(dest('./public/assets/js'))
}

function imageMin() {
  return src('./img' + '/**/*.{png,jpg,gif,jpeg}')
    .pipe($.changed('./public/img')) // src/imgフォルダの中身と、出力先のimgフォルダの中身を比較して異なるものだけ処理(新しく追加されたファイル等)
    .pipe($.imagemin([
      pngquant({
        quality: [.60, .70], // 画質
        speed: 1 // スピード
      }),
      mozjpeg({
        quality: 85, // 画質 こちらも0から100まで指定できるが、pngquantと違って65-80のように幅を持って指定はできない。1つの数字のみ。
        progressive: true // baselineとprogressiveがある。baselineよりprogressiveのほうがエンコードは遅いが圧縮率は高い。
      }),
      $.imagemin.svgo(),
      $.imagemin.optipng(),
      $.imagemin.gifsicle()
    ]))
    .pipe(dest('./public/assets/img')) // imgファイルに保存(出力)
}

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
      proxy: 'localhost:8000',
      open: false
    });
  });

  watch('./php/**/*.php', series(php));
  // watch('./sass/**/*.scss', series(cssClean, styles));
  watch('./sass/**/*.scss', series(styles));
  watch('./js/*.js', series(jsClean, scripts));
  watch('./img' + '/**/*.{png,jpg,gif,jpeg}', series(imageMin));
  watch(['./sass/**/*.scss',
    './js/*.js',
    './html/*.html',
  ]).on('change', server.reload);
}

const build = series(parallel(extras, php, styles, series(lint, scripts)));
// const build = series(phpClean, php, styles, scripts, imageMin);
const serve = series(build, startAppServer);

exports.extras = extras;
exports.php = php;
exports.styles = styles;
exports.scripts = scripts;
exports.lint = lint;
exports.clean = clean;
exports.htmlClean = htmlClean;
exports.phpClean = phpClean;
exports.cssClean = cssClean;
exports.jsClean = jsClean;
exports.imageMin = imageMin;
exports.serve = serve;
exports.default = serve;
