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
        if ($container->hasAlias(AdapterInterface::class)) {
            return;
        }

        if (!$container->hasParameter('chang.packages.geoip.data_source')) {
            return;
        }

        if (!$adapter = $container->getParameter('chang.packages.geoip.data_source')['adapter'] ?? null) {
            throw new \RuntimeException('"chang.packages.geoip.data_source.adapter" cannot be null.');
        }

        $container->setAlias(AdapterInterface::class, $adapter);
    }
}
