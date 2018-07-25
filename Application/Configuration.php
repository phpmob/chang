<?php

declare(strict_types=1);

namespace Chang\Application;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const NAME = 'chang';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::NAME);

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->arrayNode('packages')
                    ->useAttributeAsKey('name')
                    ->beforeNormalization()
                        ->ifArray()
                        ->then(function (array $packages) {
                            foreach ($packages as &$package) {
                                if (null === $package || true === $package) {
                                    $package = ['full' => true];
                                }
                            }

                            return $packages;
                        })
                    ->end()
                    ->arrayPrototype()
                        ->canBeUnset()
                        ->useAttributeAsKey('name')
                        ->beforeNormalization()
                            ->ifEmpty()
                            ->then(function () { return true; })
                        ->end()
                        ->arrayPrototype()
                            ->canBeUnset()
                            ->children()
                                ->variableNode('enabled')->defaultTrue()->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
