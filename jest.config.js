/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @type {import('@jest/types').Config.InitialOptions} */
module.exports = {
  setupFilesAfterEnv: ['./assets/js/controllers/setup.js'],
  testEnvironment: 'jsdom',
  testPathIgnorePatterns: ['/node_modules/', '/vendor/'],
};
