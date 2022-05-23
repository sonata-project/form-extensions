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

import moment from 'moment';

// Eonasdan Bootstrap DateTimePicker in its version 3 does not
// provide the scss or plain css, it only provides the less version
// of its source files, that's why it is not included it via npm.
import '../vendor/bootstrap-datetimepicker';

// Create global moment variable to be used by the locale script.
// It expects moment to be available on the global scope
// in order to define the requested locale translations
global.moment = moment;
