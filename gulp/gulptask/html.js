/**
 * HTMLをlintの後に出力
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

export default function html() {
    return gulp.src(config.path.html.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        .pipe($.htmlhint(config.htmlhint))
        .pipe($.htmlhint.reporter())
        .pipe($.htmlhint.failReporter());
}
