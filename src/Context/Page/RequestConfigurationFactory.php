<?php

declare(strict_types=1);

namespace Chang\Context\Page;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestConfigurationFactory implements RequestConfigurationFactoryInterface
{
    /**
     * @var RequestConfigurationFactoryInterface
     */
    private $decoratedFactory;

    /**
     * @var PageContextInterface
     */
    private $pageContext;

    public function __construct(RequestConfigurationFactoryInterface $factory, PageContextInterface $pageContext)
    {
        $this->decoratedFactory = $factory;
        $this->pageContext = $pageContext;
    }

    /**
     * {@inheritdoc}
     */
    public function create(MetadataInterface $metadata, Request $request): RequestConfiguration
    {
        $configuration = $this->decoratedFactory->create($metadata, $request);
        $this->pageContext->build($configuration);

        return $configuration;
    }
}
