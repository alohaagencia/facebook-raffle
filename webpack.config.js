const env = process.env.NODE_ENV !== 'production'
const webpack = require('webpack')

module.exports = {
  context: __dirname,
  devtool: env ? 'inline-sourcemap' : false,
  entry: './src/assets/scripts/main.js',
  devServer: {
    inline: true,
    contentBase: './src',
    watchContentBase: true,
    publicPath: '/assets/scripts',
    port: 3000,
    overlay: {
      errors: true,
      warnings: true
    }
  },
  output: {
    path: __dirname + '/src/assets/scripts',
    filename: 'main.min.js'
  },
  plugins: env ? [] : [
    new webpack.optimize.UglifyJsPlugin({
      mangle: false,
      sourcemap: false
    })
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: 'babel-loader'
      }
    ]
  }
}
