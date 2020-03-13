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
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener as SymfonyResizeFormListener;
use Symfony\Component\Form\FormEvent;

/**
 * Resize a collection form element based on the data sent from the client.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
final class ResizeFormListener extends SymfonyResizeFormListener implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    private $removed = [];

    /**
     * @throws UnexpectedTypeException
     */
    public function preSubmit(FormEvent $event): void
    {
        parent::preSubmit($event);

        if ($this->allowDelete) {
            $form = $event->getForm();

            foreach ($form as $name => $value) {
                if (isset($value['_delete'])) {
                    $this->removed[] = $name;
                }
            }
        }
    }

    /**
     * @throws UnexpectedTypeException
     */
    public function onSubmit(FormEvent $event): void
    {
        parent::onSubmit($event);

        if ($this->allowDelete) {
            $data = $event->getData();

            // remove selected elements
            foreach ($this->removed as $pos) {
                unset($data[$pos]);
            }

            $event->setData($data);
        }
    }
}
