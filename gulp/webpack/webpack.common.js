const config = require('../gulpconfig.js');

module.exports = {
    context: config.default.path.webpack.context,
    entry: config.default.path.webpack.entry,
    output: config.default.path.webpack.output,
    resolve: {
        extensions:['.ts','.js','.json']
    },
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'eslint-loader'
                    }
                ]
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                    }
                ]
            },
            {
                test:/\.ts$/,
                exclude: /node_modules/,
                loader:'awesome-typescript-loader'
            }
        ]
    }
}
