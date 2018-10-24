<?php

declare(strict_types=1);

namespace Chang\Redis;

use Chang\Application\CheckerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Checker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getName(): string
    {
        return 'Redis';
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $package, string $feature, array $config, ContainerBuilder $container): string
    {
        if ('session' === $feature) {
            return $container->hasAlias('session.handler') && 'chang.redis.session_handler' === strval($container->getAlias('session.handler'))
                ? '<info>OK</info>' : '<error> Failed </error>';
        }

        if ('monolog' === $feature) {
            return '<comment>TODO</comment>';
        }

        return '<comment>Unknown</comment>';
    }
}
