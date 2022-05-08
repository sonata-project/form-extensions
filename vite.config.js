/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// eslint-disable-next-line import/no-unresolved
import { defineConfig } from 'vitest/config';
// eslint-disable-next-line import/no-extraneous-dependencies
import GithubActionsReporter from 'vitest-github-actions-reporter';

export default defineConfig({
  test: {
    setupFiles: ['./assets/js/setup.test.js'],
    include: ['assets/js/**/*.test.js', '!assets/js/setup.test.js'],
    coverage: {
      all: true,
      include: ['assets/js/**/*.js'],
      reporter: ['text', 'json', 'html', 'lcovonly'],
      exclude: ['assets/js/**/*.test.js'],
    },
    reporters: process.env.GITHUB_ACTIONS ? ['default', new GithubActionsReporter()] : 'default',
    environment: 'jsdom',
  },
});
