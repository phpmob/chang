<?php

declare(strict_types=1);

namespace Chang\SecurityAudit\EventListener;

use Chang\SecurityAudit\AuditManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LoginAuditListener implements LogoutSuccessHandlerInterface
{
    /**
     * @var AuditManagerInterface
     */
    private $auditManager;

    /**
     * @var SessionStorageInterface
     */
    private $sessionStorage;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        AuditManagerInterface $auditManager,
        SessionStorageInterface $sessionStorage,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->auditManager = $auditManager;
        $this->sessionStorage = $sessionStorage;
        $this->dispatcher = $dispatcher;
    }

    public function onLoginSuccess()
    {
        $this->dispatcher->addListener(KernelEvents::TERMINATE, function (PostResponseEvent $event) {
            $this->auditManager->login($event->getRequest(), $this->sessionStorage->getId());
        });
    }

    public function onLogoutSuccess(Request $request)
    {
        $this->dispatcher->addListener(KernelEvents::TERMINATE, function () {
            $this->auditManager->logout($this->sessionStorage->getId());
        });
    }
}
