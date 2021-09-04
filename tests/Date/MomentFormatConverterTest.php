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
use Sonata\Form\Date\MomentFormatConverter;

/**
 * @author Hugo Briand <briand@ekino.com>
 *
 * @group legacy
 */
class MomentFormatConverterTest extends TestCase
{
    public function testPhpToMoment(): void
    {
        $mfc = new MomentFormatConverter();

        $phpFormat = "yyyy-MM-dd'T'HH:mm:ssZZZZZ";
        static::assertSame('YYYY-MM-DDTHH:mm:ssZ', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd HH:mm:ss';
        static::assertSame('YYYY-MM-DD HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd HH:mm';
        static::assertSame('YYYY-MM-DD HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'yyyy-MM-dd';
        static::assertSame('YYYY-MM-DD', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy, HH:mm';
        static::assertSame('DD.MM.YYYY, HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy, HH:mm:ss';
        static::assertSame('DD.MM.YYYY, HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.yyyy';
        static::assertSame('DD.MM.YYYY', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy';
        static::assertSame('D.M.YYYY', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy HH:mm';
        static::assertSame('D.M.YYYY HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'd.M.yyyy HH:mm:ss';
        static::assertSame('D.M.YYYY HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd/MM/yyyy';
        static::assertSame('DD/MM/YYYY', $mfc->convert($phpFormat));

        $phpFormat = 'dd/MM/yyyy HH:mm';
        static::assertSame('DD/MM/YYYY HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'EE, dd/MM/yyyy HH:mm';
        static::assertSame('ddd, DD/MM/YYYY HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'EEEE d MMMM y HH:mm';
        static::assertSame('dddd D MMMM YYYY HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy';
        static::assertSame('DD-MM-YYYY', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy HH:mm';
        static::assertSame('DD-MM-YYYY HH:mm', $mfc->convert($phpFormat));

        $phpFormat = 'dd-MM-yyyy HH:mm:ss';
        static::assertSame('DD-MM-YYYY HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'dd.MM.y HH:mm:ss';
        static::assertSame('DD.MM.YYYY HH:mm:ss', $mfc->convert($phpFormat));

        $phpFormat = 'D MMM y';
        static::assertSame('D MMM YYYY', $mfc->convert($phpFormat));

        $phpFormat = "dd 'de' MMMM 'de' YYYY"; //Brazilian date format
        static::assertSame('DD [de] MMMM [de] YYYY', $mfc->convert($phpFormat));
    }
}
