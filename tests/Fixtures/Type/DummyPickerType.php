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

namespace Sonata\Form\Tests\Fixtures\Type;

use Sonata\Form\Type\BasePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

final class DummyPickerType extends BasePickerType
{
    public function getName(): string
    {
        return 'base_picker_test';
    }

    protected function getDefaultFormat(): string
    {
        return DateTimeType::HTML5_FORMAT;
    }
}
