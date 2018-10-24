<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CheckerCompilePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('chang.checker')) {
            return;
        }

        $registry = $container->findDefinition('chang.checker');

        foreach ($container->findTaggedServiceIds('chang.checker') as $id => $attributes) {
            $registry->addMethodCall('addChecker', [new Reference($id)]);
        }
    }
}
