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

namespace Sonata\Form\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\VisitorInterface;
use Sonata\Doctrine\Model\ManagerInterface;

/**
 * NEXT_MAJOR: Remove this class.
 *
 * @deprecated since sonata-project/form-extensions version 1.13 and will be removed in 2.0.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
abstract class BaseSerializerHandler implements SerializerHandlerInterface
{
    /**
     * @var ManagerInterface<object>
     */
    protected $manager;

    /**
     * @var string[]
     */
    protected static $formats = [];

    /**
     * @param ManagerInterface<object> $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string[] $formats
     */
    final public static function setFormats(array $formats): void
    {
        static::$formats = $formats;
    }

    final public static function addFormat(string $format): void
    {
        static::$formats[] = $format;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public static function getSubscribingMethods(): array
    {
        $type = static::getType();
        $methods = [];

        foreach (static::$formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => $format,
                'type' => $type,
                'method' => 'serializeObjectToId',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'type' => $type,
                'method' => 'deserializeObjectFromId',
            ];
        }

        return $methods;
    }

    /**
     * Serialize data object to id.
     *
     * NEXT_MAJOR: Update $visitor type hint and remove $context when dropping jms/serializer < 3.0
     *
     * @psalm-suppress TooManyArguments for jms/serializer < 3.0 support
     *
     * @param SerializationVisitorInterface $visitor
     * @param mixed[]                       $type
     *
     * @return int|null
     */
    public function serializeObjectToId(
        VisitorInterface $visitor,
        object $data,
        array $type,
        Context $context
    ) {
        $className = $this->manager->getClass();

        if ($data instanceof $className) {
            // @phpstan-ignore-next-line
            return $visitor->visitInteger($data->getId(), $type, $context);
        }

        return null;
    }

    /**
     * Deserialize object from its id.
     *
     * NEXT_MAJOR: Update $visitor typehint when dropping jms/serializer < 3.0
     *
     * @param DeserializationVisitorInterface $visitor
     * @param mixed[]                         $type
     */
    public function deserializeObjectFromId(
        VisitorInterface $visitor,
        int $data,
        array $type
    ): ?object {
        return $this->manager->findOneBy(['id' => $data]);
    }
}
