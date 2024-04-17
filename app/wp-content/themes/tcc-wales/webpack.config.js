const webpack = require('webpack')
const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const config = {
    entry: {
        main: './src/index.js',
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader']
            },
            {
                test: /\.s[ac]ss$/i,
                exclude: /node_modules/,
                use: [
                    { loader: MiniCssExtractPlugin.loader },
                    // Creates `style` nodes from JS strings
                    // 'style-loader',
                    // Translates CSS into CommonJS
                    'css-loader',
                    // // includes Post CSS Loader
                    'postcss-loader',
                    // // Compiles Sass to CSS
                    'sass-loader'
                ],
            },
            // {
            //     test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource',
            //     generator: {
            //         filename: '[hash][ext][query]',
            //     },
            //     use: 'file-loader',
            // },
            {
                test: /\.svg$/,
                use: 'file-loader'
            },
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../[name]-style.css',
            ignoreOrder: false // Enable to remove warnings about conflicting order
        }),
    ],
    resolve: {
        extensions: [
            '.js',
        ],
        alias: {
            jquery: "jquery/src/jquery"
        }
    },
    watch: true,
};

module.exports = config;
