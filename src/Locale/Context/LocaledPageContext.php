<?php

declare(strict_types=1);

namespace Chang\Locale\Context;

use Chang\Context\Page\PageContextInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class LocaledPageContext implements PageContextInterface
{
    /**
     * @var PageContextInterface
     */
    private $decoratedContext;

    /**
     * @var LocaleContextInterface
     */
    private $localContext;

    public function __construct(PageContextInterface $pageContext, LocaleContextInterface $localeContext)
    {
        $this->decoratedContext = $pageContext;
        $this->localContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(string $context): void
    {
        $this->decoratedContext->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): string
    {
        return $this->decoratedContext->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function build(RequestConfiguration $configuration): void
    {
        $this->decoratedContext->build($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        $value = $this->decoratedContext->get($key, $default);
        $localeCode = $this->localContext->getLocaleCode();

        if (is_array($value) && array_key_exists($localeCode, $value)) {
            return $value[$localeCode];
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null): void
    {
        $this->decoratedContext->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        return $this->decoratedContext->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): ?Request
    {
        return $this->decoratedContext->getRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp(): ?string
    {
        return $this->decoratedContext->getClientIp();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $parameters, ResourceInterface $resource): array
    {
        return $this->decoratedContext->parse($parameters, $resource);
    }
}
