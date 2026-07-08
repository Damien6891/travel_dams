const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const browserSync = require('browser-sync').create();

// Domaine Laragon de ton site
const PROXY_URL = 'https://travel-dams.test';

// Certificat Laragon (le même qu'Apache utilise déjà pour travel-dams.test).
// On le réutilise ici pour que BrowserSync présente un certificat déjà
// approuvé par Windows, au lieu d'en générer un nouveau, non reconnu.
const LARAGON_SSL_KEY = 'C:/laragon/etc/ssl/laragon.key';
const LARAGON_SSL_CERT = 'C:/laragon/etc/ssl/laragon.crt';

const paths = {
  scss: {
    main: 'src/scss/style.scss',
    watch: 'src/scss/**/*.scss',
    dest: './assets/css'
  },
  js: {
    src: 'src/js/**/*.js',
    dest: './js'
  },
  php: {
    watch: ['./**/*.php', '!node_modules/**', '!vendor/**']
  }
};

function styles() {
  return gulp
    .src(paths.scss.main)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(browserSync.stream());
}

function scripts() {
  return gulp
    .src(paths.js.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(concat('main.js'))
    .pipe(terser())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.js.dest))
    .pipe(browserSync.stream());
}

function serve(done) {
  browserSync.init({
    proxy: PROXY_URL,
    https: {
      key: LARAGON_SSL_KEY,
      cert: LARAGON_SSL_CERT
    },
    open: false,
    notify: false
  });
  done();
}

function reload(done) {
  browserSync.reload();
  done();
}

function watchFiles() {
  gulp.watch(paths.scss.watch, styles);
  gulp.watch(paths.js.src, scripts);
  gulp.watch(paths.php.watch, reload);
}

const build = gulp.parallel(styles, scripts);
const dev = gulp.series(build, serve, watchFiles);

exports.styles = styles;
exports.scripts = scripts;
exports.build = build;
exports.watch = dev;
exports.default = dev;
