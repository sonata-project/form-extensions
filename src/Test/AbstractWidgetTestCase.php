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

namespace Sonata\Form\Test;

use Sonata\Form\Fixtures\StubTranslator;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

/**
 * Base class for tests checking rendering of form widgets.
 *
 * @author Christian Gripp <mail@core23.de>
 */
abstract class AbstractWidgetTestCase extends TypeTestCase
{
    /**
     * @var FormRenderer
     */
    private $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $environment = $this->getEnvironment();

        $this->renderer = new FormRenderer(
            $this->getRenderingEngine($environment),
            $this->createMock(CsrfTokenManagerInterface::class)
        );

        $environment->addRuntimeLoader(new FactoryRuntimeLoader([
            FormRenderer::class => function (): FormRenderer {
                return $this->renderer;
            },
        ]));
        $environment->addExtension(new FormExtension());
    }

    final public function getRenderer(): FormRenderer
    {
        return $this->renderer;
    }

    protected function getEnvironment(): Environment
    {
        $loader = new FilesystemLoader($this->getTemplatePaths());

        $environment = new Environment($loader, [
            'strict_variables' => true,
        ]);
        $environment->addExtension(new TranslationExtension(new StubTranslator()));

        return $environment;
    }

    /**
     * Returns a list of template paths.
     *
     * @return string[]
     */
    protected function getTemplatePaths(): array
    {
        // this is an workaround for different composer requirements and different TwigBridge installation directories
        $twigPaths = array_filter([
            // symfony/twig-bridge (running from this bundle)
            __DIR__.'/../../vendor/symfony/twig-bridge/Resources/views/Form',
            // symfony/twig-bridge (running from other bundles)
            __DIR__.'/../../../../symfony/twig-bridge/Resources/views/Form',
            // symfony/symfony (running from this bundle)
            __DIR__.'/../../vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form',
            // symfony/symfony (running from other bundles)
            __DIR__.'/../../../../symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form',
        ], 'is_dir');

        $twigPaths[] = __DIR__.'/../Bridge/Symfony/Resources/views/Form';

        return $twigPaths;
    }

    protected function getRenderingEngine(Environment $environment): TwigRendererEngine
    {
        return new TwigRendererEngine(['form_div_layout.html.twig'], $environment);
    }

    /**
     * Renders widget from FormView, in SonataAdmin context, with optional view variables $vars. Returns plain HTML.
     */
    final protected function renderWidget(FormView $view, array $vars = []): string
    {
        return $this->renderer->searchAndRenderBlock($view, 'widget', $vars);
    }

    /**
     * Helper method to strip newline and space characters from html string to make comparing easier.
     */
    final protected function cleanHtmlWhitespace(string $html): string
    {
        return preg_replace_callback('/\s*>([^<]+)</', static function (array $value): string {
            return '>'.trim($value[1]).'<';
        }, $html);
    }

    final protected function cleanHtmlAttributeWhitespace(string $html): string
    {
        return preg_replace_callback('~<([A-Z0-9]+) \K(.*?)>~i', static function (array $m): string {
            return preg_replace('~\s*~', '', $m[0]);
        }, $html);
    }
}
