const commonConfig = require("./webpack.common");
let   devVueConfig = require("./webpack.dev");
const { merge }    = require('webpack-merge');

const MiniCssExtractPlugin   = require("mini-css-extract-plugin");
const WebpackRTLPlugin       = require("webpack-rtl-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FileManagerPlugin      = require('filemanager-webpack-plugin');

let prodConfig = {
  mode: "production", // production | development
  watch: false,
  entry: {
    ['admin-multi-directory-builder']: "./assets/src/js/admin/multi-directory-builder.js",
    ['admin-settings-manager']: "./assets/src/js/admin/settings-manager.js",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].min.css",
      minify: true,
    }),
    new WebpackRTLPlugin({
      minify: true,
    }),
    new CleanWebpackPlugin({
      dry: false,
      cleanOnceBeforeBuildPatterns: [ '../css/*.map', '../js/*.map' ],
      dangerouslyAllowCleanPatternsOutsideProject: true,
    }),
    new FileManagerPlugin({
      events: {
        onEnd: [
          {
            copy: [
              { source: './admin', destination: './__build/directorist/directorist/admin' },
              { source: './assets', destination: './__build/directorist/directorist/assets' },
              { source: './languages', destination: './__build/directorist/directorist/languages' },
              { source: './includes', destination: './__build/directorist/directorist/includes' },
              { source: './templates', destination: './__build/directorist/directorist/templates' },
              { source: './views', destination: './__build/directorist/directorist/views' },
              { source: './*.php', destination: './__build/directorist/directorist' },
            ],
          },
          {
            delete: ['./__build/directorist/directorist/assets/src'],
          },
          {
            archive: [
              { source: './__build/directorist', destination: './__build/directorist.zip' },
            ],
          },
          {
            delete: ['./__build/directorist'],
          },
        ],
      },
    }),
    
  ],

  output: {
    filename: "../js/[name].min.js",
  },
};



let configs = [];

devVueConfig = merge( commonConfig, devVueConfig );
devVueConfig.watch = false;
configs.push( devVueConfig );

prodConfig = merge( commonConfig, prodConfig );
configs.push( prodConfig );

module.exports = configs;