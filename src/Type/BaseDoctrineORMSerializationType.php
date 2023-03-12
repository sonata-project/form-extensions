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

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use Metadata\MetadataFactoryInterface;
use Sonata\Form\EventListener\FixCheckboxDataListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * NEXT_MAJOR: Remove this class.
 *
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 *
 * @deprecated since sonata-project/form-extensions version 1.13 and will be removed in 2.0.
 *
 * This is a doctrine serialization form type that generates a form type from class serialization metadata
 * and doctrine metadata.
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class BaseDoctrineORMSerializationType extends AbstractType
{
    /**
     * @param MetadataFactoryInterface $metadataFactory     Serializer metadata factory
     * @param ManagerRegistry          $registry            Doctrine registry
     * @param string                   $name                Form type name
     * @param string                   $class               Data class name
     * @param string                   $group               Serialization group name
     * @param bool                     $identifierOverwrite
     *
     * @phpstan-param class-string $class
     */
    public function __construct(
        protected MetadataFactoryInterface $metadataFactory,
        protected ManagerRegistry $registry,
        protected $name,
        protected $class,
        protected $group,
        protected $identifierOverwrite = false
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $serializerMetadata = $this->metadataFactory->getMetadataForClass($this->class);
        if (!$serializerMetadata instanceof ClassMetadata) {
            throw new \RuntimeException(sprintf(
                'The serializer metadata of the class "%s" MUST implement "%s".',
                $this->class,
                ClassMetadata::class
            ));
        }

        $manager = $this->registry->getManagerForClass($this->class);
        if (null === $manager) {
            throw new \RuntimeException(sprintf(
                'The object manager of the class "%s" cannot be found.',
                $this->class
            ));
        }

        $doctrineMetadata = $manager->getClassMetadata($this->class);
        if (!$doctrineMetadata instanceof ClassMetadataInfo) {
            throw new \RuntimeException(sprintf(
                'The class metadata of the class "%s" MUST implement "%s".',
                $this->class,
                ClassMetadataInfo::class
            ));
        }

        foreach ($serializerMetadata->propertyMetadata as $propertyMetadata) {
            $name = $propertyMetadata->name;
            if (!$propertyMetadata instanceof PropertyMetadata) {
                throw new \RuntimeException(sprintf(
                    'The serializer metadata of the property "%s" MUST implement "%s".',
                    $name,
                    PropertyMetadata::class
                ));
            }

            if (\in_array($name, $doctrineMetadata->getIdentifierFieldNames(), true) && !$this->identifierOverwrite) {
                continue;
            }

            if (!isset($propertyMetadata->groups) || !\in_array($this->group, $propertyMetadata->groups, true)) {
                continue;
            }

            $type = null;
            $nullable = true;

            if (isset($doctrineMetadata->fieldMappings[$name])) {
                $fieldMetadata = $doctrineMetadata->fieldMappings[$name];
                $type = $fieldMetadata['type'];
                $nullable = $fieldMetadata['nullable'] ?? false;
            } elseif (isset($doctrineMetadata->associationMappings[$name])) {
                $associationMetadata = $doctrineMetadata->associationMappings[$name];

                if (isset($associationMetadata['joinColumns']['nullable'])) {
                    $nullable = $associationMetadata['joinColumns']['nullable'];
                } elseif (isset($associationMetadata['joinTable']['inverseJoinColumns']['nullable'])) {
                    $nullable = $associationMetadata['joinTable']['inverseJoinColumns']['nullable'];
                }
            }

            $required = true !== $nullable;

            switch ($type) {
                case 'datetime':
                    $builder->add(
                        $name,
                        DateTimeType::class,
                        ['required' => $required, 'widget' => 'single_text']
                    );

                    break;

                case 'boolean':
                    $childBuilder = $builder->create($name, null, ['required' => $required]);
                    $childBuilder->addEventSubscriber(new FixCheckboxDataListener());
                    $builder->add($childBuilder);

                    break;

                default:
                    $builder->add($name, null, ['required' => $required]);

                    break;
            }
        }
    }

    /**
     * @return string
     */
    #[\ReturnTypeWillChange]
    public function getBlockPrefix()
    {
        return $this->name;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
        ]);
    }
}
