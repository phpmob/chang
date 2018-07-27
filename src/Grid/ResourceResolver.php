<?php

declare(strict_types=1);

namespace Chang\Grid;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ResourceResolver implements ResourcesResolverInterface
{
    /**
     * @var ResourcesResolverInterface
     */
    private $decoratedResolver;

    /**
     * @var GridProviderInterface
     */
    private $gridProvider;

    /**
     * @var ResourceGridViewFactoryInterface
     */
    private $gridViewFactory;

    /**
     * @param ResourcesResolverInterface $decoratedResolver
     * @param GridProviderInterface $gridProvider
     * @param ResourceGridViewFactoryInterface $gridViewFactory
     */
    public function __construct(
        ResourcesResolverInterface $decoratedResolver,
        GridProviderInterface $gridProvider,
        ResourceGridViewFactoryInterface $gridViewFactory
    )
    {
        $this->decoratedResolver = $decoratedResolver;
        $this->gridProvider = $gridProvider;
        $this->gridViewFactory = $gridViewFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(RequestConfiguration $requestConfiguration, RepositoryInterface $repository)
    {
        if (!$requestConfiguration->hasGrid()) {
            return $this->decoratedResolver->getResources($requestConfiguration, $repository);
        }

        $gridDefinition = $this->gridProvider->get($requestConfiguration->getGrid());

        $request = $requestConfiguration->getRequest();
        $criteria = ['criteria' => $requestConfiguration->getCriteria()];
        $parameters = new Parameters(array_replace_recursive($request->query->all(), $criteria));

        $gridView = $this->gridViewFactory->create($gridDefinition, $parameters, $requestConfiguration->getMetadata(), $requestConfiguration);

        if ($requestConfiguration->isHtmlRequest()) {
            return $gridView;
        }

        return $gridView->getData();
    }
}
