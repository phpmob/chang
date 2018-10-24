<?php

declare(strict_types=1);

namespace Chang\SecurityAudit;

interface AuditManagerInterface
{
    /**
     * @param string $sessionId
     */
    public function login(string $sessionId): void;

    /**
     * @param string $sessionId
     */
    public function logout(string $sessionId): void;

    /**
     * @param string $sessionId
     */
    public function kicking(string $sessionId): void;
}
