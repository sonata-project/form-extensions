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

namespace Sonata\Form\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Sonata\Form\EventListener\FixCheckboxDataListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;

class FixCheckboxDataListenerTest extends TestCase
{
    /**
     * @param mixed $data
     * @param mixed $expected
     *
     * @dataProvider valuesProvider
     */
    public function testFixCheckbox(
        $data,
        $expected,
        ?EventSubscriberInterface $subscriber,
        BooleanToStringTransformer $transformer
    ): void {
        $dispatcher = new EventDispatcher();

        if (null !== $subscriber) {
            $dispatcher->addSubscriber($subscriber);
        }

        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->getFormFactory();

        $formBuilder = new FormBuilder('checkbox', \stdClass::class, $dispatcher, $formFactory);
        $formBuilder->addViewTransformer($transformer);

        $form = $formBuilder->getForm();
        $form->submit($data);

        static::assertSame($expected, $form->getData());
    }

    /**
     * @return iterable<array{mixed, mixed, EventSubscriberInterface|null, BooleanToStringTransformer}>
     */
    public function valuesProvider(): iterable
    {
        return [
            ['0', true, null, new BooleanToStringTransformer('1')],
            ['0', false, new FixCheckboxDataListener(), new BooleanToStringTransformer('1')],
        ];
    }
}
