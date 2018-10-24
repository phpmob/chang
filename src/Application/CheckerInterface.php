<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface CheckerInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $rows
     * @param Kernel $kernel
     * @param ContainerInterface $container
     */
    public function check(array &$rows, Kernel $kernel, ContainerInterface $container): void;
}
