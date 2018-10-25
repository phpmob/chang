<?php

declare(strict_types=1);

namespace Chang\SecurityAudit;

use Symfony\Component\HttpFoundation\Request;

interface AuditManagerInterface
{
    /**
     * @param Request $request
     * @param string $sessionId
     */
    public function login(Request $request, string $sessionId): void;

    /**
     * @param string $sessionId
     */
    public function logout(string $sessionId): void;

    /**
     * @param string $sessionId
     */
    public function kicking(string $sessionId): void;
}
