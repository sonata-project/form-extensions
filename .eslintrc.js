/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * DO NOT EDIT THIS FILE!
 *
 * It's auto-generated by sonata-project/dev-kit package.
 */

module.exports = {
  parser: '@babel/eslint-parser',
  extends: ['airbnb-base', 'prettier'],
  env: {
    browser: true,
    jquery: true,
  },
  plugins: ['header'],
  rules: {
    'header/header': [
      2,
      'block',
      [
        '!',
        ' * This file is part of the Sonata Project package.',
        ' *',
        ' * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>',
        ' *',
        ' * For the full copyright and license information, please view the LICENSE',
        ' * file that was distributed with this source code.',
        ' ',
      ],
      2,
    ],
    'import/no-webpack-loader-syntax': 'off',
  },
};
