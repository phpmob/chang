<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\User\Model\UserInterface;

abstract class Device implements DeviceInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var array
     */
    private $metas = [];

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlatform(?string $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    /**
     * {@inheritdoc}
     */
    public function setClientIp(?string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetas(array $metas = []): void
    {
        $this->metas = $metas;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(?UserInterface $user)
    {
        $this->user = $user;
    }
}
