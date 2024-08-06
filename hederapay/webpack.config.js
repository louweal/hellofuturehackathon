const path = require('path');
const webpack = require('webpack');

module.exports = {
    entry: './src/js/main.js',
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'hederapay.bundle.js',
        publicPath: '/dist/',
    },
    devServer: {
        static: {
            directory: path.join(__dirname, '/'),
        },
    },
    mode: 'development',
    resolve: {
        fallback: {
            // http: false,
            // https: false,
            // url: false,
            // assert: false,
            // zlib: false,
            // crypto: false,
            // stream: false,
            // fs: false,
            // net: false,
            // tls: false,
            // http2: false,
            // dns: false,
            // os: false,
            // 'os-browserify': false,
            // path: false,
            // util: false,
            // stream: require.resolve('stream-browserify'),
            // buffer: require.resolve('buffer/'),
            // zlib: require.resolve('browserify-zlib'),
        },
    },
    plugins: [
        // new webpack.ProvidePlugin({
        //     Buffer: ['buffer', 'Buffer'],
        // }),
        // new webpack.ProvidePlugin({
        //     process: 'process/browser',
        // }),
    ],
};
