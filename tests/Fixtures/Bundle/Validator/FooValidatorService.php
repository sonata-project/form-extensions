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

namespace Sonata\Form\Tests\Fixtures\Bundle\Validator;

use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Validator service to create exception for test.
 *
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class FooValidatorService
{
    /**
     * @throws ValidatorException
     */
    public function fooValidatorMethod(ErrorElement $errorElement, string $value): void
    {
        throw new ValidatorException($errorElement->getSubject().' is equal to '.$value);
    }
}
