<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\User\Model\UserInterface;

interface MessageInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @return string
     */
    public function getContent(): ?string;

    /**
     * @param string $content
     */
    public function setContent(?string $content): void;

    /**
     * @return bool
     */
    public function isGlobal(): bool;

    /**
     * @param bool $global
     */
    public function setGlobal(bool $global): void;

    /**
     * @return \DateTime|null
     */
    public function getExpiredAt(): ?\DateTime;

    /**
     * @param \DateTime|null $dateTime
     */
    public function setExpiredAt(?\DateTime $dateTime): void;

    /**
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * @return array
     */
    public function getMetas(): array;

    /**
     * @param array $metas
     */
    public function setMetas(array $metas): void;

    /**
     * @param ViewerInterface $viewer
     *
     * @return bool
     */
    public function hasViewer(ViewerInterface $viewer): bool;

    /**
     * @param ViewerInterface $viewer
     */
    public function addViewer(ViewerInterface $viewer): void;

    /**
     * @param ViewerInterface $viewer
     */
    public function removeViewer(ViewerInterface $viewer): void;

    /**
     * @param UserInterface $user
     */
    public function removeViewerByUser(UserInterface $user): void;

    /**
     * @return Collection
     */
    public function getViewers(): Collection;

    /**
     * @param RecipientInterface $recipient
     *
     * @return bool
     */
    public function hasRecipient(RecipientInterface $recipient): bool;

    /**
     * @param RecipientInterface $recipient
     */
    public function addRecipient(RecipientInterface $recipient): void;

    /**
     * @param RecipientInterface $recipient
     */
    public function removeRecipient(RecipientInterface $recipient): void;

    /**
     * @return Collection|RecipientInterface[]
     */
    public function getRecipients(): Collection;

    /**
     * @return array
     */
    public function getBody(): array;

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isSeen(UserInterface $user): bool;
}
