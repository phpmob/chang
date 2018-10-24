<?php

declare(strict_types=1);

namespace Chang\SecurityAudit\Model;

class Login implements LoginInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firewall;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string|null
     */
    private $clientIp;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $zip;

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

    /**
     * @var array
     */
    private $meta = [];

    /**
     * @var bool
     */
    private $kicked = false;

    /**
     * @var string|null
     */
    private $userAgent;

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirewall(): string
    {
        return $this->firewall;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirewall(string $firewall): void
    {
        $this->firewall = $firewall;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * {@inheritdoc}
     */
    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * {@inheritdoc}
     */
    public function setClientIp(string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * {@inheritdoc}
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * {@inheritdoc}
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginAt(): \DateTime
    {
        return $this->loginAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setLoginAt(\DateTime $loginAt): void
    {
        $this->loginAt = $loginAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogoutAt(): ?\DateTime
    {
        return $this->logoutAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogoutAt(?\DateTime $logoutAt): void
    {
        $this->logoutAt = $logoutAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function isKicked(): bool
    {
        return $this->kicked;
    }

    /**
     * {@inheritdoc}
     */
    public function setKicked(bool $kicked): void
    {
        $this->kicked = $kicked;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * {@inheritdoc}
     */
    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired(): bool
    {
        if (!$this->lifetime) {
            return false;
        }

        $loginAt = clone $this->loginAt;

        try {
            $loginAt->add(new \DateInterval(sprintf('PT%sS', $this->lifetime)));
        } catch (\Exception $e) {
            return false;
        }

        return new \DateTime() > $loginAt;
    }
}
