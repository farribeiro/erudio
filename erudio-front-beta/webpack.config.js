module.exports = {
    entry: './apps/appController.js',
    output: {
        filename: './bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.js?$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel-loader'
            }
        ]
    }
}