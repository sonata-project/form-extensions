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

namespace Sonata\Form\DataTransformer;

use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @phpstan-implements DataTransformerInterface<bool, int>
 */
final class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{
    /**
     * @phpstan-param mixed $value
     */
    public function transform(mixed $value): ?int
    {
        if (true === $value || BooleanType::TYPE_YES === (int) $value) {
            return BooleanType::TYPE_YES;
        }
        if (false === $value || BooleanType::TYPE_NO === (int) $value) {
            return BooleanType::TYPE_NO;
        }

        return null;
    }

    /**
     * @phpstan-param mixed $value
     */
    public function reverseTransform(mixed $value): ?bool
    {
        if (BooleanType::TYPE_YES === $value) {
            return true;
        }
        if (BooleanType::TYPE_NO === $value) {
            return false;
        }

        return null;
    }
}
