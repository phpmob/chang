<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use Sylius\Component\User\Model\UserInterface;

interface NumberAwareInterface extends UserInterface
{
    /**
     * @return null|string
     */
    public function getPhoneNumber(): ?string;

    /**
     * @param null|string $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void;

    /**
     * @return bool
     */
    public function isPhoneNumberVerified(): bool;

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumberVerifiedAt(): ?\DateTimeInterface;

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumberVerifiedAt(?\DateTimeInterface $verifiedAt): void;

    /**
     * @return string|null
     */
    public function getPhoneNumberVerificationToken(): ?string;

    /**
     * @param string|null $verificationToken
     */
    public function setPhoneNumberVerificationToken(?string $verificationToken): void;

    /**
     * @return string|null
     */
    public function getPhoneNumberUserToken(): ?string;

    /**
     * @param string|null $verificationToken
     */
    public function setPhoneNumberUserToken(?string $verificationToken): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getPhoneNumberRequestedAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setPhoneNumberRequestedAt(?\DateTimeInterface $date): void;

    /**
     * @param \DateInterval $ttl
     *
     * @return bool
     */
    public function isPhoneNumberRequestExpired(\DateInterval $ttl): bool;
}
