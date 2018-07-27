<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

trait NumberAwareTrait
{
    /**
     * @var null|string
     */
    private $phoneNumber;

    /**
     * @var null|string
     */
    private $phoneNumberUserToken;

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function isPhoneNumberVerified(): bool
    {
        return $this->phoneNumber && $this->isVerified();
    }

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumberVerifiedAt(): ?\DateTimeInterface
    {
        return $this->getVerifiedAt();
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumberVerifiedAt(?\DateTimeInterface $verifiedAt): void
    {
        $this->setVerifiedAt($verifiedAt);
        $this->setPhoneNumberRequestedAt(null);
        $this->setPhoneNumberVerificationToken(null);
        $this->setPhoneNumberUserToken(null);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumberVerificationToken(): ?string
    {
        return $this->getPasswordResetToken();
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumberVerificationToken(?string $verificationToken): void
    {
        $this->setPasswordResetToken($verificationToken);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumberUserToken(): ?string
    {
        return $this->phoneNumberUserToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumberUserToken(?string $verificationToken): void
    {
        $this->phoneNumberUserToken = $verificationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumberRequestedAt(): ?\DateTimeInterface
    {
        return $this->getPasswordRequestedAt();
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumberRequestedAt(?\DateTimeInterface $date): void
    {
        $this->setPasswordRequestedAt($date);
    }

    /**
     * {@inheritdoc}
     */
    public function isPhoneNumberRequestExpired(\DateInterval $ttl): bool
    {
        return $this->isPasswordRequestNonExpired($ttl);
    }
}
