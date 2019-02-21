<?php

declare(strict_types=1);

namespace Chang\Web\Context;

use Chang\Context\Page\PageContextInterface;
use Chang\Messenger\Model\InboxInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class WebPageContext implements PageContextInterface
{
    /**
     * @var PageContextInterface
     */
    private $decoratedContext;

    /**
     * @var RepositoryInterface
     */
    private $inboxRepository;

    public function __construct(PageContextInterface $pageContext, RepositoryInterface $inboxRepository = null)
    {
        $this->decoratedContext = $pageContext;
        $this->inboxRepository = $inboxRepository;
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
        return $this->decoratedContext->get($key, $default);
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

    /**
     * @return InboxInterface|null
     */
    public function getInbox(): ?InboxInterface
    {
        $user = $this->getUser();

        if (null === $this->inboxRepository || null === $user) {
            return null;
        }

        return $this->inboxRepository->findOneBy(['user' => $user]);
    }
}
