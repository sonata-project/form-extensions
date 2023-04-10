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
use Sonata\Form\EventListener\ResizeFormListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class ResizeFormListenerTest extends TestCase
{
    private FormFactoryInterface $factory;
    private FormInterface $form;

    /**
     * @psalm-suppress UndefinedMethod -- https://github.com/vimeo/psalm/issues/9104
     */
    protected function setUp(): void
    {
        $this->factory = (new FormFactoryBuilder())->getFormFactory();
        $this->form = $this->getBuilder()
            ->setCompound(true)
            // TODO: Use "new DataMapper()" when removing support for Symfony 4.4 instead of "$this->getDataMapper()"
            ->setDataMapper($this->getDataMapper())
            ->getForm();
    }

    public function testGetSubscribedEvents(): void
    {
        $events = ResizeFormListener::getSubscribedEvents();

        static::assertArrayHasKey(FormEvents::PRE_SET_DATA, $events);
        static::assertSame('preSetData', $events[FormEvents::PRE_SET_DATA]);
        static::assertArrayHasKey(FormEvents::PRE_SUBMIT, $events);
        static::assertSame('preSubmit', $events[FormEvents::PRE_SUBMIT]);
        static::assertArrayHasKey(FormEvents::SUBMIT, $events);
        static::assertSame('onSubmit', $events[FormEvents::SUBMIT]);
    }

    public function testPreSetDataWithNullData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], false, null);

        $event = new FormEvent($this->form, null);

        $listener->preSetData($event);

        static::assertCount(0, $this->form);
    }

    public function testPreSetDataWithArrayData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], false, null);

        $event = new FormEvent($this->form, [1 => 'foo', 2 => 'bar']);

        $listener->preSetData($event);

        static::assertCount(2, $this->form);
        static::assertFalse($this->form->has('0'));
        static::assertTrue($this->form->has('1'));
        static::assertTrue($this->form->has('2'));
    }

    public function testPreSubmitWithArrayData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $event = new FormEvent($this->form, [1 => 'foo', 2 => 'bar']);

        $listener->preSubmit($event);

        static::assertCount(2, $this->form);
        static::assertFalse($this->form->has('0'));
        static::assertTrue($this->form->has('1'));
        static::assertTrue($this->form->has('2'));
    }

    public function testPreSetDataThrowsExceptionWithStringEventData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], false, null);

        $event = new FormEvent($this->form, '');

        $this->expectException(UnexpectedTypeException::class);

        $listener->preSetData($event);
    }

    public function testPreSetData(): void
    {
        $attr = ['maxlength' => 10];
        $typeOptions = [
            'attr' => $attr,
        ];

        $listener = new ResizeFormListener(TextType::class, $typeOptions, false, null);

        $this->form->add($this->getForm('foo'));

        $data = ['baz' => 'caz'];

        $event = new FormEvent($this->form, $data);

        $listener->preSetData($event);

        static::assertCount(1, $this->form);
        static::assertFalse($this->form->has('foo'));
        static::assertTrue($this->form->has('baz'));
        static::assertSame($attr, $this->form->get('baz')->getConfig()->getOption('attr'));
    }

    public function testPreSubmitWithResizeOnBindFalse(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], false, null);

        $this->form->add($this->getForm('foo'));

        $data = ['baz' => 'caz'];

        $event = new FormEvent($this->form, $data);

        $listener->preSubmit($event);

        static::assertCount(1, $this->form);
        static::assertFalse($this->form->has('baz'));
        static::assertTrue($this->form->has('foo'));
    }

    public function testPreSubmitDataWithNullData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $this->form->add($this->getForm('foo'));

        $event = new FormEvent($this->form, null);

        $listener->preSubmit($event);

        static::assertCount(0, $this->form);
    }

    public function testPreSubmitThrowsExceptionWithIntEventData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $event = new FormEvent($this->form, 123);

        $this->expectException(UnexpectedTypeException::class);

        $listener->preSubmit($event);
    }

    public function testPreSubmitData(): void
    {
        $listener = new ResizeFormListener(TextType::class, [], true, null);

        $data = ['baz' => 'caz', 0 => 'daz'];

        $event = new FormEvent($this->form, $data);

        $listener->preSubmit($event);

        static::assertCount(2, $this->form);
        static::assertTrue($this->form->has('baz'));
        static::assertSame('[baz]', $this->form->get('baz')->getConfig()->getOption('property_path'));
        static::assertTrue($this->form->has('0'));
        static::assertSame('[0]', $this->form->get('0')->getConfig()->getOption('property_path'));
    }

    public function testPreSubmitDataWithClosure(): void
    {
        $data = ['baz' => 'caz'];

        $closure = static fn (): string => $data['baz'];

        $listener = new ResizeFormListener(TextType::class, [], true, $closure);

        $event = new FormEvent($this->form, $data);

        $listener->preSubmit($event);

        static::assertCount(1, $this->form);
        static::assertTrue($this->form->has('baz'));
        static::assertSame('caz', $this->form->get('baz')->getData());
    }

    public function testOnSubmitWithResizeOnBindFalse(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], false, null);

        $data = ['baz' => 'caz'];

        $event = new FormEvent($this->form, $data);

        $listener->preSubmit($event);

        $listener->onSubmit($event);

        static::assertCount(0, $this->form);
    }

    public function testOnSubmitDataWithNullData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $event = new FormEvent($this->form, null);

        $listener->onSubmit($event);

        static::assertCount(0, $this->form);
    }

    public function testOnSubmitThrowsExceptionWithIntEventData(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $event = new FormEvent($this->form, 123);

        $this->expectException(UnexpectedTypeException::class);

        $listener->onSubmit($event);
    }

    public function testOnSubmit(): void
    {
        $listener = new ResizeFormListener(FormType::class, [], true, null);

        $this->form->add($this->getForm('foo'));
        $this->form->add($this->getForm('bar'));
        $this->form->add($this->getForm('baz'));
        $this->form->add($this->getForm('0'));

        $data = [
            'foo' => ['_delete' => true, 'value' => 'foo-value'],
            'bar' => ['_delete' => true, 'value' => 'bar-value'],
            'baz' => ['value' => 'baz-value'],
            '0' => ['value' => '0-value'],
        ];

        $event = new FormEvent($this->form, $data);

        $listener->preSubmit($event);
        $listener->onSubmit($event);

        static::assertSame([
            'baz' => ['value' => 'baz-value'],
            '0' => ['value' => '0-value'],
        ], $event->getData());
    }

    private function getBuilder(string $name = 'name'): FormBuilder
    {
        return new FormBuilder($name, null, new EventDispatcher(), $this->factory);
    }

    private function getForm(string $name = 'name'): FormInterface
    {
        return $this->getBuilder($name)->getForm();
    }

    /**
     * TODO: Remove this method when removing support for Symfony 4.4.
     *
     * @psalm-suppress UndefinedClass, InvalidReturnStatement, InvalidReturnType
     */
    private function getDataMapper(): DataMapperInterface
    {
        if (class_exists(DataMapper::class)) {
            return new DataMapper();
        }

        // @phpstan-ignore-next-line -- BC layer for Symfony 4.4
        return new PropertyPathMapper();
    }
}
