<?php

declare(strict_types=1);

namespace Chang\Security\Model;

class LoginAudit
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firewall;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var string
     */
    private $geoIp;

    /**
     * @var int
     */
    private $lifetime;

    /**
     * @var \DateTime
     */
    private $loginAt;

    /**
     * @var \DateTime|null
     */
    private $logoutAt;

}
