const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const mode = process.env.NODE_ENV || "development";
// const target = process.env.NODE_ENV === "production" ? "browserslist" : "web";

// JS Directory path.
const path = require( 'path' );
const JS_DIR = path.resolve( __dirname, 'src/js' );
const BUILD_DIR = path.resolve( __dirname, 'build' );

const entry = {
    main: JS_DIR + '/main.js',
};

const output = {
    path: BUILD_DIR,
    filename: 'js/[name].js'
};

module.exports = {
    // mode defaults to 'production' if not set
    mode: mode,

    entry: entry,

    output: output,

    plugins: [new MiniCssExtractPlugin(
        {
            filename: 'css/[name].css'
        }
    )],

    module: {
        rules: [
            {
                test: /\.(s[ac]|c)ss$/i,
                exclude: /node_modules/,
                use: [ 
                    // could replace the next line with "style-loader" here for inline css
                    MiniCssExtractPlugin.loader, 

                    "css-loader", 
                    // according to the docs, sass-loader should be at the bottom, which
                    // loads it first to avoid prefixes in your sourcemaps and other issues.
                    "sass-loader",
                    "postcss-loader"
                ]
            },

            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                  // without additional settings, this will reference .babelrc
                  loader: "babel-loader",
                },
            },
        ]
    },
  
    // defaults to "web", so only required for webpack-dev-server bug
    // target: target,

    devtool: 'source-map',

    // // required if using webpack-dev-server
    devServer: {
        contentBase: "./build",
    }
}
