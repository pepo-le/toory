/**
 * png画像からスプライトを作成する
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

import ms from 'merge-stream';
export default function sprite() {
    const spriteData = gulp.src(config.path.sprite.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        .pipe($.spritesmith(config.sprite));

    const imgStream = spriteData.img.pipe(gulp.dest(config.path.sprite.imgDest));
    const cssStream = spriteData.css.pipe(gulp.dest(config.path.sprite.scssDest));
    return ms(imgStream, cssStream);
}
