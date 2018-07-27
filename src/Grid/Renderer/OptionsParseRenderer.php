<?php

declare(strict_types=1);

namespace Chang\Grid\Renderer;

use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;

class OptionsParseRenderer implements GridRendererInterface
{
    /**
     * @var GridRendererInterface
     */
    private $renderer;

    /**
     * @var OptionsParserInterface
     */
    private $parser;

    /**
     * @var array
     */
    private $shadowOptions = [];

    public function __construct(GridRendererInterface $renderer, OptionsParserInterface $parser)
    {
        $this->renderer = $renderer;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function render(GridViewInterface $gridView, ?string $template = null)
    {
        return $this->renderer->render($gridView, $template);
    }

    /**
     * {@inheritdoc}
     *
     * @param GridViewInterface|ResourceGridView $gridView
     */
    public function renderField(GridViewInterface $gridView, Field $field, $data)
    {
        $options = $this->parser->parseOptions(
            $this->shadowOptions[$field->getName()] ?? $this->shadowOptions[$field->getName()] = $field->getOptions(),
            $gridView->getRequestConfiguration()->getRequest(),
            $data
        );

        $field->setOptions($options);

        return $this->renderer->renderField($gridView, $field, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function renderAction(GridViewInterface $gridView, Action $action, $data = null)
    {
        return $this->renderer->renderAction($gridView, $action, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function renderFilter(GridViewInterface $gridView, Filter $filter)
    {
        return $this->renderer->renderFilter($gridView, $filter);
    }
}
