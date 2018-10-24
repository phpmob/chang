<?php

declare(strict_types=1);

namespace Chang\SecurityAudit\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface LoginInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @param string $username
     */
    public function setUsername(string $username): void;

    /**
     * @return string
     */
    public function getFirewall(): string;

    /**
     * @param string $firewall
     */
    public function setFirewall(string $firewall): void;

    /**
     * @return string
     */
    public function getSessionId(): string;

    /**
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId): void;

    /**
     * @return string
     */
    public function getClientIp(): string;

    /**
     * @param string $clientIp
     */
    public function setClientIp(string $clientIp): void;

    /**
     * @return null|string
     */
    public function getCountry(): ?string;

    /**
     * @param null|string $country
     */
    public function setCountry(?string $country): void;

    /**
     * @return null|string
     */
    public function getCountryCode(): ?string;

    /**
     * @param null|string $countryCode
     */
    public function setCountryCode(?string $countryCode): void;

    /**
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void;

    /**
     * @return null|string
     */
    public function getZip(): ?string;

    /**
     * @param null|string $zip
     */
    public function setZip(?string $zip): void;

    /**
     * @return int
     */
    public function getLifetime(): int;

    /**
     * @param int $lifetime
     */
    public function setLifetime(int $lifetime): void;

    /**
     * @return \DateTime
     */
    public function getLoginAt(): \DateTime;

    /**
     * @param \DateTime $loginAt
     */
    public function setLoginAt(\DateTime $loginAt): void;

    /**
     * @return \DateTime|null
     */
    public function getLogoutAt(): ?\DateTime;

    /**
     * @param \DateTime|null $logoutAt
     */
    public function setLogoutAt(?\DateTime $logoutAt): void;

    /**
     * @return array
     */
    public function getMeta(): array;

    /**
     * @param array $meta
     */
    public function setMeta(array $meta): void;

    /**
     * @return bool
     */
    public function isKicked(): bool;

    /**
     * @param bool $kicked
     */
    public function setKicked(bool $kicked): void;

    /**
     * @return null|string
     */
    public function getUserAgent(): ?string;

    /**
     * @param null|string $userAgent
     */
    public function setUserAgent(?string $userAgent): void;

    /**
     * @return bool
     */
    public function isExpired(): bool;
}
