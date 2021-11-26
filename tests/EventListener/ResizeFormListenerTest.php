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
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class ResizeFormListenerTest extends TestCase
{
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
        $listener = new ResizeFormListener('form', [], false, null);

        $form = $this->createMock(Form::class);
        $form->expects(static::once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator());
        $form->expects(static::never())
            ->method('add');

        $event = new FormEvent($form, null);

        $listener->preSetData($event);
    }

    public function testPreSetDataWithArrayData(): void
    {
        $listener = new ResizeFormListener('form', [], false, null);

        $form = $this->createMock(Form::class);
        $form
            ->method('getIterator')
            ->willReturn(new \ArrayIterator());
        $form
            ->expects(static::exactly(2))
            ->method('add')
            ->withConsecutive(
                ['0'],
                ['1'],
            );

        $event = new FormEvent($form, ['foo', 'bar']);

        $listener->preSetData($event);
    }

    public function testPreSubmitWithArrayData(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $form = $this->createMock(Form::class);
        $form
            ->method('getIterator')
            ->willReturn(new \ArrayIterator());
        $form
            ->method('has')
            ->willReturn(false);
        $form
            ->expects(static::exactly(2))
            ->method('add')
            ->withConsecutive(
                ['0'],
                ['1'],
            );

        $event = new FormEvent($form, ['foo', 'bar']);

        $listener->preSubmit($event);
    }

    public function testPreSetDataThrowsExceptionWithStringEventData(): void
    {
        $listener = new ResizeFormListener('form', [], false, null);

        $form = $this->createMock(Form::class);

        $event = new FormEvent($form, '');

        $this->expectException(UnexpectedTypeException::class);

        $listener->preSetData($event);
    }

    public function testPreSetData(): void
    {
        $typeOptions = [
            'default' => 'option',
        ];

        $listener = new ResizeFormListener('form', $typeOptions, false, null);

        $options = [
            'property_path' => '[baz]',
            'data' => 'caz',
            'default' => 'option',
        ];

        $form = $this->createMock(Form::class);
        $form->expects(static::once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['foo' => 'bar']));
        $form->expects(static::once())
            ->method('remove')
            ->with('foo');
        $form->expects(static::once())
            ->method('add')
            ->with('baz', 'form', $options);

        $data = ['baz' => 'caz'];

        $event = new FormEvent($form, $data);

        $listener->preSetData($event);
    }

    public function testPreSubmitWithResizeOnBindFalse(): void
    {
        $listener = new ResizeFormListener('form', [], false, null);

        $event = $this->createMock(FormEvent::class);
        $event->expects(static::never())
            ->method('getForm');

        $listener->preSubmit($event);
    }

    public function testPreSubmitDataWithNullData(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $form = $this->createMock(Form::class);
        $form->expects(static::once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['foo' => 'bar']));
        $form->expects(static::never())
            ->method('has');

        $event = new FormEvent($form, null);

        $listener->preSubmit($event);
    }

    public function testPreSubmitThrowsExceptionWithIntEventData(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $form = $this->createMock(Form::class);
        $event = new FormEvent($form, 123);

        $this->expectException(UnexpectedTypeException::class);

        $listener->preSubmit($event);
    }

    public function testPreSubmitData(): void
    {
        $typeOptions = [
            'default' => 'option',
        ];

        $listener = new ResizeFormListener('form', $typeOptions, true, null);

        $options = [
            'property_path' => '[baz]',
            'default' => 'option',
        ];

        $form = $this->createMock(Form::class);
        $form->expects(static::once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['foo' => 'bar']));
        $form->expects(static::once())
            ->method('remove')
            ->with('foo');
        $form->expects(static::once())
            ->method('add')
            ->with('baz', 'form', $options);

        $data = ['baz' => 'caz'];

        $event = new FormEvent($form, $data);

        $listener->preSubmit($event);
    }

    public function testPreSubmitDataWithClosure(): void
    {
        $typeOptions = [
            'default' => 'option',
        ];

        $data = ['baz' => 'caz'];

        $closure = static function () use ($data): string {
            return $data['baz'];
        };

        $listener = new ResizeFormListener('form', $typeOptions, true, $closure);

        $options = [
            'property_path' => '[baz]',
            'default' => 'option',
            'data' => 'caz',
        ];

        $form = $this->createMock(Form::class);
        $form->expects(static::once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['foo' => 'bar']));
        $form->expects(static::once())
            ->method('remove')
            ->with('foo');
        $form->expects(static::once())
            ->method('add')
            ->with('baz', 'form', $options);

        $event = new FormEvent($form, $data);

        $listener->preSubmit($event);
    }

    public function testOnSubmitWithResizeOnBindFalse(): void
    {
        $listener = new ResizeFormListener('form', [], false, null);

        $event = $this->createMock(FormEvent::class);
        $event->expects(static::never())
            ->method('getForm');

        $listener->onSubmit($event);
    }

    public function testOnSubmitDataWithNullData(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $form = $this->createMock(Form::class);
        $form->expects(static::never())
            ->method('has');

        $event = new FormEvent($form, null);

        $listener->onSubmit($event);
    }

    public function testOnSubmitThrowsExceptionWithIntEventData(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $form = $this->createMock(Form::class);

        $event = new FormEvent($form, 123);

        $this->expectException(UnexpectedTypeException::class);

        $listener->onSubmit($event);
    }

    public function testOnSubmit(): void
    {
        $listener = new ResizeFormListener('form', [], true, null);

        $reflector = new \ReflectionClass(ResizeFormListener::class);
        $reflectedMethod = $reflector->getProperty('removed');
        $reflectedMethod->setAccessible(true);
        $reflectedMethod->setValue($listener, ['foo', 'bar']);

        $form = $this->createMock(Form::class);
        $form
            ->expects(static::exactly(3))
            ->method('has')
            ->withConsecutive(
                ['foo'],
                ['bar'],
                ['baz']
            )
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                true
            );

        $data = [
            'foo' => 'foo-value',
            'bar' => 'bar-value',
            'baz' => 'baz-value',
        ];

        $removedData = [
            'baz' => 'baz-value',
        ];

        $event = $this->createMock(FormEvent::class);
        $event->expects(static::once())
            ->method('getForm')
            ->willReturn($form);
        $event->expects(static::once())
            ->method('getData')
            ->willReturn($data);
        $event->expects(static::once())
            ->method('setData')
            ->with($removedData);

        $listener->onSubmit($event);
    }
}
