/**
 * browser-syncで自動リロード
 */
import config from '../gulpconfig.js';
import $ from './plugins.js';

import merge from 'merge';

export default function server() {
    let options = merge(config.browser, {
        server: {
            baseDir: config.webroot,
            directory: false
        },
        ui: {
            port: 8080
        },
        notify: false
    });
    if (options.proxy.target) {
        delete options.server;
    } else {
        delete options.proxy;
    }
    return $.browser.init(options);
}
