<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\User\Model\UserAwareInterface;

interface DeviceInterface extends ResourceInterface, TimestampableInterface, UserAwareInterface, ToggleableInterface
{
    const PlatformWeb = 'web';
    const PlatformApn = 'apn';
    const PlatformGcm = 'gcm';

    const SupportedPlatforms = [
        self::PlatformWeb,
        self::PlatformApn,
        self::PlatformGcm,
    ];

    /**
     * @return null|string
     */
    public function getToken(): ?string;

    /**
     * @param null|string $token
     */
    public function setToken(?string $token): void;

    /**
     * @return null|string
     */
    public function getPlatform(): ?string;

    /**
     * @param null|string $platform
     */
    public function setPlatform(?string $platform): void;

    /**
     * @return null|string
     */
    public function getClientIp(): ?string;

    /**
     * @param null|string $clientIp
     */
    public function setClientIp(?string $clientIp): void;

    /**
     * @return array
     */
    public function getMetas(): array;

    /**
     * @param array $metas
     */
    public function setMetas(array $metas = []): void;
}
