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

use Sonata\Form\Test\AbstractWidgetTestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author Christian Gripp <mail@core23.de>
 */
final class FormChoiceWidgetTest extends AbstractWidgetTestCase
{
    public function testLabelRendering(): void
    {
        $choices = ['some' => 0, 'choices' => 1];

        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getDefaultOption() + [
                'multiple' => true,
                'expanded' => true,
            ] + compact('choices')
        );

        $html = $this->renderWidget($choice->createView());

        static::assertStringContainsString(
            $this->cleanHtmlWhitespace(
                <<<'HTML'
                    <div id="choice">
                        <input type="checkbox" id="choice_0" name="choice[]" value="0" />
                        <label for="choice_0">[trans]some[/trans]</label>
                        <input type="checkbox" id="choice_1" name="choice[]" value="1" />
                        <label for="choice_1">[trans]choices[/trans]</label>
                    </div>
                    HTML
            ),
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testDefaultValueRendering(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getDefaultOption()
        );

        $html = $this->renderWidget($choice->createView());

        static::assertStringContainsString(
            '<option value="" selected="selected">[trans]Choose an option[/trans]</option>',
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testRequiredIsDisabledForEmptyPlaceholder(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getRequiredOption()
        );

        $html = $this->renderWidget($choice->createView());

        static::assertStringNotContainsString(
            'required="required"',
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testRequiredIsEnabledIfPlaceholderIsSet(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            array_merge($this->getRequiredOption(), $this->getDefaultOption())
        );

        $html = $this->renderWidget($choice->createView());

        static::assertStringContainsString(
            'required="required"',
            $this->cleanHtmlWhitespace($html)
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getRequiredOption(): array
    {
        return [
            'required' => true,
        ];
    }

    /**
     * @phpstan-return class-string<ChoiceType>
     */
    private function getChoiceClass(): string
    {
        return ChoiceType::class;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultOption(): array
    {
        return [
            'placeholder' => 'Choose an option',
        ];
    }
}
