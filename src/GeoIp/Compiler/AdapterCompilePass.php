<?php

declare(strict_types=1);

namespace Chang\GeoIp\Compiler;

use Chang\GeoIp\AdapterInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdapterCompilePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('chang.packages.geo_ip.data_source')) {
            return;
        }

        if (!$adapter = $container->getParameter('chang.packages.geo_ip.data_source')['adapter'] ?? null) {
            return;
        }

        $container->setAlias(AdapterInterface::class, $adapter);
    }
}
