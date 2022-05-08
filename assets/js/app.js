/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Any SCSS/CSS you require will output into a single css file (app.css in this case)
import '../scss/app.scss';

// eslint-disable-next-line import/no-unresolved
import DatePicker from '@symfony/stimulus-bridge/lazy-controller-loader?lazy=true!./controllers/datepicker_controller.js';

const { sonataApplication } = global;

sonataApplication.register('datepicker', DatePicker);
