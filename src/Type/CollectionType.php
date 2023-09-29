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

namespace Sonata\Form\Type;

use Sonata\Form\EventListener\ResizeFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 */
final class CollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber(new ResizeFormListener(
            $options['type'],
            $options['type_options'],
            $options['modifiable'],
            $options['pre_bind_data_callback']
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['btn_add'] = $options['btn_add'];

        // NEXT_MAJOR: Remove the btn_catalogue usage.
        $view->vars['btn_translation_domain'] =
            'SonataFormBundle' !== $options['btn_translation_domain']
                ? $options['btn_translation_domain']
                : $options['btn_catalogue'];
        $view->vars['btn_catalogue'] = $options['btn_catalogue'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'modifiable' => false,
            'type' => TextType::class,
            'type_options' => [],
            'pre_bind_data_callback' => null,
            'btn_add' => 'link_add',
            'btn_catalogue' => 'SonataFormBundle', // NEXT_MAJOR: Remove this option.
            'btn_translation_domain' => 'SonataFormBundle',
        ]);

        $resolver->setDeprecated(
            'btn_catalogue',
            'sonata-project/form-extensions',
            '2.1',
            static function (Options $options, mixed $value): string {
                if ('SonataFormBundle' !== $value) {
                    return 'Passing a value to option "btn_catalogue" is deprecated! Use "btn_translation_domain" instead!';
                }

                return '';
            },
        ); // NEXT_MAJOR: Remove this deprecation notice.

        $resolver->setAllowedTypes('modifiable', 'bool');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('type_options', 'array');
        $resolver->setAllowedTypes('pre_bind_data_callback', ['null', 'callable']);
        $resolver->setAllowedTypes('btn_add', ['null', 'bool', 'string']);
        $resolver->setAllowedTypes('btn_catalogue', ['null', 'bool', 'string']);
        $resolver->setAllowedTypes('btn_translation_domain', ['null', 'bool', 'string']);
    }

    public function getBlockPrefix(): string
    {
        return 'sonata_type_collection';
    }
}
