/**
 * 画像の圧縮
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

export default function imagemin() {
    return gulp.src(config.path.imagemin.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        .pipe($.imagemin())
        .pipe(gulp.dest(config.path.imagemin.dest));
}
