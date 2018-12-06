/**
 * PHPをバリデート
 */
import gulp from 'gulp';
import config from '../gulpconfig.js';
import $ from './plugins.js';

import notifier from 'node-notifier';

export default function php() {
    let stream = gulp.src(config.path.php.src)
        .pipe($.plumber({ errorHandler: $.notify.onError('<%= error.message %>') }))
        .pipe($.phplint('', { skipPassedFiles: true }))
        .pipe($.phplint.reporter(function(file){
            const report = file.phplintReport || {};
            if (report.error) {
                notifier.notify({
                    message: report.message+' on line '+report.line+' of '+report.filename,
                    title: 'PHP Lint',
                });
            }
        }))

    stream.on('end', function () {
        $.browser.reload();
    });
    return stream;
}
