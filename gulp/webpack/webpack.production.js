const Merge = require('webpack-merge');

const CommonConfig = require('./webpack.common.js');

module.exports = Merge(CommonConfig, {
    mode: "production"
});
