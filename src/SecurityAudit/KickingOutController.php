<?php

declare(strict_types=1);

namespace Chang\SecurityAudit;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class KickingOutController
{
    /**
     * @var NativeSessionStorage
     */
    private $sessionStorage;

    /**
     * @var AuditManagerInterface
     */
    private $auditManager;

    public function __construct(NativeSessionStorage $sessionStorage, AuditManagerInterface $auditManager)
    {
        $this->sessionStorage = $sessionStorage;
        $this->auditManager = $auditManager;
    }

    /**
     * @param Request $request
     * @param string $sessionId
     *
     * @return RedirectResponse
     */
    public function kickAction(Request $request, string $sessionId): RedirectResponse
    {
        // FIXME: can't destroy without delete all current cookie,
        // TODO: implement session delete
        $this->sessionStorage->getSaveHandler()->destroy($sessionId);
        $this->auditManager->kicking($sessionId);

        return RedirectResponse::create($request->headers->get('referer'));
    }
}
