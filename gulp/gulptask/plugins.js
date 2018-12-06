/**
 * gulpから始まるモジュールを$.<module>で読み込めるようにする
 */
// const loader = require('gulp-load-plugins');
// const browser = require('browser-sync');
//
import loader from 'gulp-load-plugins';
import browser from 'browser-sync';

const $ = loader({
    pattern: ['gulp-*', 'gulp.*'],
    replaceString: /\bgulp[\-.]/
});

$.browser = browser;
export default $;
