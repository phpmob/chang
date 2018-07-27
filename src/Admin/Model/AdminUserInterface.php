<?php

declare(strict_types=1);

namespace Chang\Admin\Model;

use Chang\Locale\Model\LocaleAwareInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use PhpMob\MediaBundle\Model\FileAwareInterface;
use Sylius\Component\User\Model\UserInterface;

interface AdminUserInterface extends UserInterface, FileAwareInterface, MessageRecipientInterface, LocaleAwareInterface
{
    /**
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * @return AdminUserPictureInterface|null
     */
    public function getPicture(): ?AdminUserPictureInterface;

    /**
     * @param AdminUserPictureInterface|null $picture
     */
    public function setPicture(?AdminUserPictureInterface $picture): void;
}
