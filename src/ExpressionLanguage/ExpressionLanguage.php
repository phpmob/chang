<?php

declare(strict_types=1);

namespace Chang\ExpressionLanguage;

use Psr\Cache\CacheItemPoolInterface;
use Sylius\Bundle\ResourceBundle\ExpressionLanguage\NotNullExpressionFunctionProvider;
use Symfony\Component\DependencyInjection\ExpressionLanguage as BaseExpressionLanguage;

final class ExpressionLanguage extends BaseExpressionLanguage
{
    /**
     * {@inheritdoc}
     */
    public function __construct(CacheItemPoolInterface $cacheItemPool = null, array $providers = array())
    {
        array_unshift($providers, new NotNullExpressionFunctionProvider());
        array_unshift($providers, new DefaultNullValueExpressionFunctionProvider());

        parent::__construct($cacheItemPool, $providers);
    }
}
