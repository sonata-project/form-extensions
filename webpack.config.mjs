/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import webpack from 'webpack';
import Encore from '@symfony/webpack-encore';
import StyleLintPlugin from 'stylelint-webpack-plugin';

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

  .configureCssMinimizerPlugin((options, MinimizerPlugin) => {
    options.minify = {
      implementation: MinimizerPlugin.cssnanoMinify,
      options: {
        preset: ['default', { discardComments: { removeAll: true } }],
      },
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

  .configureJsMinimizerPlugin((options, MinimizerPlugin) => {
    options.minify = {
      implementation: MinimizerPlugin.terserMinify,
      options: { format: { comments: false } },
    };
    options.extractComments = false;
  })

  .addEntry('app', './assets/js/app.js');

export default await Encore.getWebpackConfig();
