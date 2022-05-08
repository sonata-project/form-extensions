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

namespace Sonata\Form\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Sonata\Form\Twig\CanonicalizeRuntime;

/**
 * NEXT_MAJOR: Remove this test case.
 *
 * @group legacy
 */
final class CanonicalizeRuntimeTest extends TestCase
{
    /**
     * @psalm-suppress DeprecatedClass
     */
    private CanonicalizeRuntime $canonicalizeRuntime;

    /**
     * @psalm-suppress DeprecatedClass
     */
    protected function setUp(): void
    {
        $this->canonicalizeRuntime = new CanonicalizeRuntime();
    }

    public function testCanonicalizedLocaleForMoment(): void
    {
        static::assertNull($this->canonicalizeRuntime->getCanonicalizedLocaleForMoment());
    }
}
