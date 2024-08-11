const path = require('path');

module.exports = {
    entry: './src/js/main.js',
    output: {
        path: path.resolve(__dirname),
        filename: './dist/[name].bundle.js',
        publicPath: '/wp-content/themes/control/realviews/',
    },
    devServer: {
        static: {
            directory: path.join(__dirname, '/'),
        },
    },
    mode: 'development',
    devtool: false,
    resolve: {
        fallback: {
            path: false, //require.resolve('path-browserify'),
            os: false, //require.resolve('os-browserify/browser'),
            crypto: false, //require.resolve('crypto-browserify'),
            stream: false, //require.resolve('stream-browserify'),
            tls: false,
            net: false,
            zlib: false, //require.resolve('browserify-zlib'),
            http: false, //require.resolve('stream-http'),
            url: false, //require.resolve('url/'),
            http2: false,
            dns: false,
            util: false, //require.resolve('util/'),
            fs: false,
        },
    },
    plugins: [],
    optimization: {
        splitChunks: {
            chunks: 'all', // Split all chunks
            cacheGroups: {
                default: false, // Disable the default 'commons' chunk
                vendors: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
            },
        },
    },
};
