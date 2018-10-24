<?php

declare(strict_types=1);

namespace Chang\Verification;

use Chang\Application\CheckerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Checker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getName(): string
    {
        return 'Verification';
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $package, string $feature, array $config, ContainerBuilder $container): string
    {
        return '<comment>TODO</comment>';
    }
}
