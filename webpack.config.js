const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
// webpack needs to be explicitly required
const webpack = require('webpack');

module.exports = {
    mode: 'development',
    entry: ['./src/js/main.js', './src/css/main.css'],
    output: {
        filename: './dist/[name].bundle.js',
        path: path.resolve(__dirname),
    },
    // resolve: {
    //     fallback: {
    //         http: false,
    //         https: false,
    //         url: false,
    //         assert: false,
    //         zlib: false,
    //         crypto: false,
    //         stream: false,
    //         fs: false,
    //         net: false,
    //     },
    // },
    plugins: [
        new MiniCssExtractPlugin({
            filename: './dist/[name].bundle.css',
        }),
        // Work around for Buffer is undefined:
        // https://github.com/webpack/changelog-v5/issues/10
        // new webpack.ProvidePlugin({
        //     Buffer: ['buffer', 'Buffer'],
        // }),
        // // fix "process is not defined" error:
        // new webpack.ProvidePlugin({
        //     process: 'process/browser',
        // }),
    ],
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    'postcss-import',
                                    'tailwindcss/nesting',
                                    'tailwindcss',
                                    [
                                        'postcss-preset-env',
                                        {
                                            stage: 1,
                                            features: {
                                                'nesting-rules': false,
                                            },
                                        },
                                    ],
                                    'autoprefixer',
                                ],
                            },
                        },
                    },
                ],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [['@babel/preset-env']],
                    },
                },
            },
        ],
    },
    optimization: {
        minimizer: [new CssMinimizerPlugin(), new TerserPlugin()],
    },
    performance: {
        hints: false,
    },
};
