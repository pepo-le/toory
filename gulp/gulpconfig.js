import path from 'path';

const app = '..';
const assets = app + '/Assets';
const webroot = app + '/webroot';

export default {
    app: app,
    webroot: webroot,
    htmlhint: '.htmlhintrc',
    ejs: {
        ext: '.html'
    },
    style: {
        sass: {
            outputStyle: 'compressed'
        },
        autoprefixer: {
            browsers: ['last 3 version', 'ie >= 9', 'Android 4.0'],
            ignore: []
        },
        mqpacker: {}
    },
    sprite: {
        imgName: 'sprite.png',
        cssName: '_sprite.scss',
        imgPath: '../img/sprite.png',
        cssFormat: 'scss',
        cssVarMap: function (sprite) {
            sprite.name = 'sprite-' + sprite.name;
        }
    },
    styleguide: {
        out: webroot + '/styleguide/',
        css: '../public/css/style.css',
        script: '../public/js/script.js',
        clean: true
    },
    browser: {
        proxy: {
            target: 'toory.pepolocalhost.com'
        },
        ghostMode: {
            clicks: false,
            location: false,
            forms: false,
            scroll: false
        }
    },
    path: {
        clean: {
            // TODO: 環境に合わせて変更する
            src: [
                webroot + '/js/script.js',
                webroot + '/js/chart.js',
                webroot + '/css/style.css',
                webroot + '/css/maps',
            ]
        },
        html: {
            // 出力先のHTMLをチェックする
            src: webroot + '/**/*.html'
        },
        ejs: {
            src: [assets + '/view/**/*.ejs', '!' + assets + '/view/**/_*.ejs'],
            watch: [assets + '/view/**/*.ejs'],
            dest: webroot
        },
        style: {
            src: [assets + '/sass/**/*.scss'],
            watch: [assets + '/sass/**/*.scss'],
            dest: webroot + '/css'
        },
        script: {
            src: [assets + '/js/**/*.js', '!' + assets + '/js/node_modules/**/*'],
            watch: [assets + '/js/**/*.js', '!' + assets + '/js/node_modules/**/*'],
            dest: webroot + '/js'
        },
        webpack: {
            // 詳細はwebpack/以下のファイルで指定する
            context: path.resolve(__dirname, assets + '/js'),
            entry: './main.js',
            output: {
                publicPath: '/',
                path: path.resolve(__dirname, webroot + '/js'),
                filename: './[name].js'
            },
            watch: [assets + '/js/**/*.ts', '!' + assets + '/js/node_modules/**/*']
        },
        imagemin: {
            src: assets + '/img/imagemin/*',
            dest: assets + '/img'
        },
        sprite: {
            src: assets + '/img/sprite/*',
            imgDest: assets + '/img',
            scssDest: assets + '/sass/sprite'
        },
        php: {
            src: [
                app + '/**/*.php',
                app + '/**/*.ctp',
                '!' + app + '/Vendor/**/*.php',
                '!' + app + '/Vendor/**/*.ctp',
                '!' + app + '/Plugin/**/*.php',
                '!' + app + '/Plugin/**/*.ctp',
            ],
        },
        copy: [
            {
                // Image
                from: [
                    assets + '/img/**/*',
                    '!' + assets + '/img/imagemin/**/*',
                    '!' + assets + '/img/sprite/**/*',
                ],
                watchFlag: true,
                to: webroot + '/img'
            },
            {
                // Library
                from: [
                    assets + '/lib/**/*'
                ],
                watchFlag: true,
                to: webroot + '/lib'
            }
        ]
    }
}
