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

namespace Sonata\Form\Tests\Type;

use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeTest extends TypeTestCase
{
    public function testGetDefaultOptions(): void
    {
        $type = new CollectionType();

        $type->configureOptions($optionResolver = new OptionsResolver());

        $options = $optionResolver->resolve();

        $this->assertFalse($options['modifiable']);
        $this->assertSame(TextType::class, $options['type']);
        $this->assertCount(0, $options['type_options']);
        $this->assertSame('link_add', $options['btn_add']);
        $this->assertSame('SonataFormBundle', $options['btn_catalogue']);
        $this->assertNull($options['pre_bind_data_callback']);
    }
}
