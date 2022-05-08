<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Form\Date;

/**
 * Handles JavaScript <-> PHP date format conversion.
 *
 * @author Hugo Briand <briand@ekino.com>
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
final class JavaScriptFormatConverter
{
    /**
     * This defines the mapping between PHP ICU date format (key) and JavaScript date format (value)
     * For ICU formats see http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax
     * For JavaScript formats see https://github.com/Eonasdan/tempus-dominus/blob/master/src/js/datetime.ts#L922-L947.
     */
    private const FORMAT_CONVERT_RULES = [
        'yyyy' => 'yyyy', 'yy' => 'yy', 'y' => 'yyyy',
        'EEEE' => 'dddd', 'EE' => 'ddd', 'E' => 'ddd',
        'a' => 'T',
    ];

    /**
     * Returns associated JavaScript format.
     *
     * @param string $format PHP Date format
     *
     * @return string JavaScript date format
     */
    public function convert(string $format): string
    {
        $size = \strlen($format);

        $output = '';
        // process the format string letter by letter
        for ($i = 0; $i < $size; ++$i) {
            // if finds a '
            if ("'" === $format[$i]) {
                // if the next character are T' forming 'T', send a T to the
                // output
                if ('T' === $format[$i + 1] && '\'' === $format[$i + 2]) {
                    $output .= 'T';
                    $i += 2;
                } else {
                    // if it's no a 'T' then send whatever is inside the '' to
                    // the output, but send it inside [] (useful for cases like
                    // the brazilian translation that uses a 'de' in the date)
                    $output .= '[';
                    $temp = current(explode("'", substr($format, $i + 1)));
                    $output .= $temp;
                    $output .= ']';
                    $i += \strlen($temp) + 1;
                }
            } else {
                // if no ' is found, then search all the rules, see if any of
                // them matchs
                $foundOne = false;
                foreach (self::FORMAT_CONVERT_RULES as $key => $value) {
                    if (substr($format, $i, \strlen($key)) === $key) {
                        $output .= $value;
                        $foundOne = true;
                        $i += \strlen($key) - 1;

                        break;
                    }
                }
                // if no rule is matched, then just add the character to the
                // output
                if (!$foundOne) {
                    $output .= $format[$i];
                }
            }
        }

        return $output;
    }
}
