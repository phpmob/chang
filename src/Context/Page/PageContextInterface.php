<?php

declare(strict_types=1);

namespace Chang\Context\Page;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;

interface PageContextInterface
{
    /**
     * @param string $context
     */
    public function setContext(string $context): void;

    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * @param RequestConfiguration $configuration
     */
    public function build(RequestConfiguration $configuration): void;

    /**
     * @param string $key
     * @param null $default
     *
     * @return null|mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string|array $key
     * @param mixed $value
     */
    public function set($key, $value = null): void;

    /**
     * @return null|UserInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * @return null|Request
     */
    public function getRequest(): ?Request;

    /**
     * @return null|string
     */
    public function getClientIp(): ?string;

    /**
     * @param array $parameters
     * @param ResourceInterface $resource
     *
     * @return array
     */
    public function parse(array $parameters, ResourceInterface $resource): array;
}
