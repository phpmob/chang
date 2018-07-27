<?php

declare(strict_types=1);

namespace Chang\Web\Model;

use Chang\Locale\Model\LocaleAwareInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use PhpMob\MediaBundle\Model\FileAwareInterface;
use Sylius\Component\User\Model\UserInterface;

interface WebUserInterface extends UserInterface, FileAwareInterface, MessageRecipientInterface, LocaleAwareInterface
{
    /**
     * @return null|string
     */
    public function getGender(): ?string;

    /**
     * @param null|string $gender
     */
    public function setGender(?string $gender): void;

    /**
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * @return WebUserPictureInterface|null
     */
    public function getPicture(): ?WebUserPictureInterface;

    /**
     * @param WebUserPictureInterface|null $picture
     */
    public function setPicture(?WebUserPictureInterface $picture): void;
}
