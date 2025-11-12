/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const webpack = require('webpack');
const Encore = require('@symfony/webpack-encore');
const StyleLintPlugin = require('stylelint-webpack-plugin');

Encore.setOutputPath('./src/Bridge/Symfony/Resources/public')
  .setPublicPath('.')
  .setManifestKeyPrefix('bundles/sonataform')

  .cleanupOutputBeforeBuild()
  .enableSassLoader()
  .enablePostCssLoader()
  .enableVersioning(false)
  .enableSourceMaps(false)
  .disableSingleRuntimeChunk()

  .addExternals({
    '@hotwired/stimulus': 'stimulus',
  })

  .configureCssMinimizerPlugin((options) => {
    options.minimizerOptions = {
      preset: ['default', { discardComments: { removeAll: true } }],
    };
  })

  .addPlugin(
    new StyleLintPlugin({
      context: 'assets/scss',
      emitWarning: true,
    })
  )

  // As async loading of chunks is using a wrong base URL
  // we make sure we never load any additional chunks and everything is inlined into the entrypoint assets
  .addPlugin(
    new webpack.optimize.LimitChunkCountPlugin({
        maxChunks: 1,
    })
  )

  .configureTerserPlugin((options) => {
    options.terserOptions = {
      output: { comments: false },
    };
    options.extractComments = false;
  })

  .addEntry('app', './assets/js/app.js');

module.exports = Encore.getWebpackConfig();
