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

namespace Sonata\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Resize a collection form element based on the data sent from the client.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
final class ResizeFormListener implements EventSubscriberInterface
{
    private string $type;

    private bool $resizeOnSubmit;

    /**
     * @var array<string, mixed>
     */
    private array $typeOptions;

    /**
     * @var string[]
     */
    private array $removed = [];

    /**
     * @var \Closure|null
     */
    private $preSubmitDataCallback;

    /**
     * @param string               $type
     * @param array<string, mixed> $typeOptions
     * @param bool                 $resizeOnSubmit
     * @param \Closure|null        $preSubmitDataCallback
     */
    public function __construct(
        $type,
        array $typeOptions = [],
        $resizeOnSubmit = false,
        $preSubmitDataCallback = null
    ) {
        $this->type = $type;
        $this->resizeOnSubmit = $resizeOnSubmit;
        $this->typeOptions = $typeOptions;
        $this->preSubmitDataCallback = $preSubmitDataCallback;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * @throws UnexpectedTypeException
     */
    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (!\is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }

        // First remove all rows except for the prototype row
        // Type cast to string, because Symfony form can returns integer keys
        foreach ($form as $name => $child) {
            // @phpstan-ignore-next-line
            $form->remove((string) $name);
        }

        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {
            $options = array_merge($this->typeOptions, [
                'property_path' => '['.$name.']',
                'data' => $value,
            ]);

            $name = \is_int($name) ? (string) $name : $name;

            $form->add($name, $this->type, $options);
        }
    }

    /**
     * @throws UnexpectedTypeException
     */
    public function preSubmit(FormEvent $event): void
    {
        if (!$this->resizeOnSubmit) {
            return;
        }

        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data || '' === $data) {
            $data = [];
        }

        if (!\is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }

        // Remove all empty rows except for the prototype row
        // Type cast to string, because Symfony form can returns integer keys
        foreach ($form as $name => $child) {
            // @phpstan-ignore-next-line
            $form->remove((string) $name);
        }

        // Add all additional rows
        foreach ($data as $name => $value) {
            // Type cast to string, because Symfony form can returns integer keys
            if (!$form->has((string) $name)) {
                $buildOptions = [
                    'property_path' => '['.$name.']',
                ];

                if (null !== $this->preSubmitDataCallback) {
                    $buildOptions['data'] = \call_user_func($this->preSubmitDataCallback, $value);
                }

                $options = array_merge($this->typeOptions, $buildOptions);

                $name = \is_int($name) ? (string) $name : $name;

                $form->add($name, $this->type, $options);
            }

            if (isset($value['_delete'])) {
                $this->removed[] = $name;
            }
        }
    }

    /**
     * @throws UnexpectedTypeException
     */
    public function onSubmit(FormEvent $event): void
    {
        if (!$this->resizeOnSubmit) {
            return;
        }

        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (
            !\is_array($data)
            && (!$data instanceof \Traversable || !$data instanceof \ArrayAccess)
        ) {
            throw new UnexpectedTypeException($data, 'array or \Traversable&\ArrayAccess');
        }

        foreach ($data as $name => $child) {
            // Type cast to string, because Symfony form can returns integer keys
            if (!$form->has((string) $name)) {
                unset($data[$name]);
            }
        }

        // remove selected elements
        foreach ($this->removed as $pos) {
            unset($data[$pos]);
        }

        $event->setData($data);
    }
}
