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

namespace Sonata\Form\Form\DataTransformer;

use Sonata\Form\Form\Type\BooleanType;
use Symfony\Component\Form\DataTransformerInterface;

class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (true === $value or BooleanType::TYPE_YES === (int) $value) {
            return BooleanType::TYPE_YES;
        }

        return BooleanType::TYPE_NO;
    }

    public function reverseTransform($value)
    {
        if (BooleanType::TYPE_YES === $value) {
            return true;
        }

        return false;
    }
}
