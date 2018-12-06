import gulp from 'gulp';
import ms from 'merge-stream';

import config from './gulpconfig.js';

import html from './gulptask/html.js';
import ejs from './gulptask/ejs.js';
import style from './gulptask/style.js';
import webpack from './gulptask/webpack.js';
import script from './gulptask/script.js';
import imagemin from './gulptask/imagemin.js';
import sprite from './gulptask/sprite.js';
import php from './gulptask/php.js';
import copy from './gulptask/copy.js';
import server from './gulptask/server.js';
import clean from './gulptask/clean.js';

const copyfiles = config.path.copy || [];

gulp.task('html', html);
gulp.task('ejs', ejs);
gulp.task('style', style);
gulp.task('webpack', webpack);
gulp.task('script', script);
gulp.task('imagemin', imagemin);
gulp.task('sprite', sprite);
gulp.task('php', php);
gulp.task('copy', function () {
    const stream = ms();
    copyfiles.forEach(function (copyfiles) {
        stream.add(copy(copyfiles.from, copyfiles.to));
    });
    return stream;
});
gulp.task('server', server);
gulp.task('clean', clean);

gulp.task('watch', function () {
    gulp.watch(config.path.html.src, html);
    gulp.watch(config.path.ejs.watch, ejs);
    gulp.watch(config.path.style.watch, style);
    // gulp.watch(config.path.webpack.watch, webpack);
    gulp.watch(config.path.script.watch, script);
    gulp.watch(config.path.php.src, php);
    // 複製タスクはループで回して監視対象とする
    copyfiles.forEach(function (files) {
        if (files.watchFlag) {
            gulp.watch(files.from, function copy_watch() {
                return copy(files.from, files.to);
            });
        }
    });
})

gulp.task('build', gulp.series('clean', gulp.parallel('html', 'ejs', 'style', 'php', 'script'), 'copy'));
gulp.task('default', gulp.series('build', gulp.parallel('server', 'watch')));
