/**
 * SASSを使用
 * scssをコンパイルしてautoprefixerでプレフィックスをつける
 * ソースマップを出力する
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

import merge from 'merge';
import autoprefixer from 'autoprefixer';
import cssMqpacker from 'css-mqpacker';

import minimist from 'minimist';
const env = minimist(process.argv.slice(2));

export default function style() {
    config.style = config.style || {};
    const guideOptions = merge({ out: config.webroot + '/styleguide/' }, config.styleguide);
    const sourcemaps = config.style.sourcemaps || 'maps';
    const filter = $.filter(['**', '!_*.scss']);

    return gulp.src(config.path.style.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        // .pipe($.frontnote(guideOptions))
        .pipe($.sassLint())
        .pipe($.sassLint.format())
        .pipe($.sassLint.failOnError())
        .pipe(filter)
        .pipe($.if(!env.production, $.sourcemaps.init()))
        .pipe($.sass(config.style.sass))
        .pipe($.postcss([
            autoprefixer(config.style.autoprefixer),
            cssMqpacker(config.style.mqpacker)
        ]))
        .pipe($.if(!env.production, $.sourcemaps.write(sourcemaps)))
        .pipe(gulp.dest(config.path.style.dest))
        .pipe($.browser.stream({ match: '**/*.css' }));
}
