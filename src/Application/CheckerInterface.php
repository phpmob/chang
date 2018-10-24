<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface CheckerInterface
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @param string $package
     * @param string $feature
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return string
     */
    public function check(string $package, string $feature, array $config, ContainerBuilder $container): string;
}
