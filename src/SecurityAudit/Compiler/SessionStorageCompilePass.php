<?php

declare(strict_types=1);

namespace Chang\SecurityAudit\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SessionStorageCompilePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('session.storage')) {
            return;
        }

        $container->setAlias('chang.security_audit.session_storage', 'session.storage');
    }
}
