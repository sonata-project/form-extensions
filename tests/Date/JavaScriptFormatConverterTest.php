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

namespace Sonata\Form\Tests\Date;

use PHPUnit\Framework\TestCase;
use Sonata\Form\Date\JavaScriptFormatConverter;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class JavaScriptFormatConverterTest extends TestCase
{
    public function testPhpToJavaScript(): void
    {
        $mfc = new JavaScriptFormatConverter();

        $phpFormat = "yyyy-MM-dd'T'HH:mm:ss";
        static::assertSame('yyyy-MM-ddTHH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd HH:mm:ss';
        static::assertSame('yyyy-MM-dd HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd HH:mm';
        static::assertSame('yyyy-MM-dd HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd';
        static::assertSame('yyyy-MM-dd', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy, HH:mm';
        static::assertSame('dd.MM.yyyy, HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy, HH:mm:ss';
        static::assertSame('dd.MM.yyyy, HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy';
        static::assertSame('dd.MM.yyyy', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy';
        static::assertSame('d.M.yyyy', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy HH:mm';
        static::assertSame('d.M.yyyy HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy HH:mm:ss';
        static::assertSame('d.M.yyyy HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd/MM/yyyy';
        static::assertSame('dd/MM/yyyy', $mfc->convert($phpFormat));

        $phpFormat = 'dd/MM/yyyy HH:mm';
        static::assertSame('dd/MM/yyyy HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'EE, dd/MM/yyyy HH:mm';
        static::assertSame('ddd, dd/MM/yyyy HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'EEEE d MMMM y HH:mm';
        static::assertSame('dddd d MMMM yyyy HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy';
        static::assertSame('dd-MM-yyyy', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy HH:mm';
        static::assertSame('dd-MM-yyyy HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy HH:mm:ss';
        static::assertSame('dd-MM-yyyy HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.y HH:mm:ss';
        static::assertSame('dd.MM.yyyy HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'D MMM y';
        static::assertSame('D MMM yyyy', $mfc->convert($phpFormat));

        $phpFormat = "dd 'de' MMMM 'de' yyyy"; // Brazilian date format
        static::assertSame('dd [de] MMMM [de] yyyy', $mfc->convert($phpFormat));
    }
}
