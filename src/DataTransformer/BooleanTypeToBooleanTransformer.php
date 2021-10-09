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
 * @psalm-suppress InvalidReturnType, InvalidReturnStatement, FalsableReturnStatement
 *
 * @see https://github.com/psalm/psalm-plugin-symfony/issues/223
 */
final class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{
    public function transform($value): ?int
    {
        if (true === $value || BooleanType::TYPE_YES === (int) $value) {
            return BooleanType::TYPE_YES;
        } elseif (false === $value || BooleanType::TYPE_NO === (int) $value) {
            return BooleanType::TYPE_NO;
        }

        return null;
    }

    public function reverseTransform($value): ?bool
    {
        if (BooleanType::TYPE_YES === $value) {
            return true;
        } elseif (BooleanType::TYPE_NO === $value) {
            return false;
        }

        return null;
    }
}
