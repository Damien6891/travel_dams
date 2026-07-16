const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const merge = require('merge-stream');
const browserSync = require('browser-sync').create();

const PROXY_URL = 'https://travel-dams.test';
const LARAGON_SSL_KEY = 'C:/laragon/etc/ssl/laragon.key';
const LARAGON_SSL_CERT = 'C:/laragon/etc/ssl/laragon.crt';

const paths = {
  scss: {
    main: 'src/scss/style.scss',
    watch: 'src/scss/**/*.scss',
    dest: './assets/css'
  },
  js: {
    watch: 'src/js/**/*.js',
    dest: './assets/js'
  },
  php: {
    watch: ['./**/*.php', '!node_modules/**', '!vendor/**']
  }
};

// Un point d'entrée = un fichier de sortie, chargé uniquement où il est enqueue.
// "navigation" est global (présent sur chaque page).
// Ajoute une entrée par besoin ponctuel au fur et à mesure (ex: "gallery": [...]).
const jsEntries = {
  navigation: ['src/js/navigation.js'],
  header: ['src/js/header.js'],
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
  const streams = Object.entries(jsEntries).map(([name, files]) => {
    return gulp
      .src(files)
      .pipe(plumber())
      .pipe(sourcemaps.init())
      .pipe(concat(`${name}.js`))
      .pipe(terser())
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(paths.js.dest));
  });

  return merge(streams).pipe(browserSync.stream());
}

function serve(done) {
  browserSync.init({
    proxy: PROXY_URL,
    https: { key: LARAGON_SSL_KEY, cert: LARAGON_SSL_CERT },
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
  gulp.watch(paths.js.watch, scripts);
  gulp.watch(paths.php.watch, reload);
}

const build = gulp.parallel(styles, scripts);
const dev = gulp.series(build, serve, watchFiles);

exports.styles = styles;
exports.scripts = scripts;
exports.build = build;
exports.watch = dev;
exports.default = dev;