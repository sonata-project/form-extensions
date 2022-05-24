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

namespace Sonata\Form\Tests\DataTransformer;

use PHPUnit\Framework\TestCase;
use Sonata\Form\DataTransformer\BooleanTypeToBooleanTransformer;
use Sonata\Form\Type\BooleanType;

final class BooleanTypeToBooleanTransformerTest extends TestCase
{
    /**
     * @dataProvider getTransformData
     */
    public function testTransform(mixed $value, ?int $expected): void
    {
        $transformer = new BooleanTypeToBooleanTransformer();

        static::assertSame($expected, $transformer->transform($value));
    }

    public function testReverseTransform(): void
    {
        $transformer = new BooleanTypeToBooleanTransformer();
        static::assertTrue($transformer->reverseTransform(BooleanType::TYPE_YES));
        static::assertTrue($transformer->reverseTransform(1));
        static::assertFalse($transformer->reverseTransform(BooleanType::TYPE_NO));
        static::assertFalse($transformer->reverseTransform(2));
        static::assertNull($transformer->reverseTransform(null));
        static::assertNull($transformer->reverseTransform('asd'));
    }

    /**
     * @return iterable<array{mixed, int|null}>
     */
    public function getTransformData(): iterable
    {
        return [
            [true, BooleanType::TYPE_YES],
            [false, BooleanType::TYPE_NO],
            ['wrong', null],
            ['1', BooleanType::TYPE_YES],
            ['2', BooleanType::TYPE_NO],
            ['3', null], // default value is null ...
        ];
    }
}
