/**
 * JavaScriptの出力
 * ファイルはマージしない
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

import minimist from 'minimist';
const env = minimist(process.argv.slice(2));

export default function script() {
    return gulp.src(config.path.script.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        .pipe($.if(!env.production, $.sourcemaps.init()))
        .pipe($.eslint())
        .pipe($.eslint.format())
        .pipe($.eslint.failAfterError())
        .pipe($.babel())
        .pipe($.if(env.production, $.uglify()))
        .pipe($.if(!env.production, $.sourcemaps.write()))
        .pipe(gulp.dest(config.path.script.dest))
        .pipe($.browser.stream());
}
