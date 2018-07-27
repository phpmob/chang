<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\User\Model\UserInterface;

abstract class Message implements MessageInterface
{
    use TimestampableTrait;
    use SoftDeleteable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $content;

    /**
     * @var bool
     */
    private $global = false;

    /**
     * @var \DateTime|null
     */
    private $expiredAt;

    /**
     * @var array
     */
    private $metas = [];

    /**
     * @var Collection|RecipientInterface[]
     */
    protected $recipients;

    /**
     * @var Collection|ViewerInterface[]
     */
    protected $viewers;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->viewers = new ArrayCollection();
    }

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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function isGlobal(): bool
    {
        return $this->global;
    }

    /**
     * {@inheritdoc}
     */
    public function setGlobal(bool $global): void
    {
        $this->global = $global;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiredAt(): ?\DateTime
    {
        return $this->expiredAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiredAt(?\DateTime $dateTime): void
    {
        $this->expiredAt = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired(): bool
    {
        if (!$this->expiredAt) {
            return false;
        }

        return $this->expiredAt < new \DateTime();
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
    public function setMetas(array $metas): void
    {
        $this->metas = $metas;
    }

    /**
     * {@inheritdoc}
     */
    public function hasViewer(ViewerInterface $viewer): bool
    {
        return $this->viewers->contains($viewer)
            || $this->viewers->exists(function (int $n, ViewerInterface $v) use ($viewer) {
                return $viewer->getUser() === $v->getUser();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function addViewer(ViewerInterface $viewer): void
    {
        if (!$this->hasViewer($viewer)) {
            $viewer->setMessage($this);
            $viewer->setSeenAt($viewer->getSeenAt() ?? new \DateTime());
            $this->viewers->add($viewer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeViewer(ViewerInterface $viewer): void
    {
        if ($this->hasViewer($viewer)) {
            $viewer->setMessage(null);
            $this->viewers->removeElement($viewer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeViewerByUser(UserInterface $user): void
    {
        $viewers = $this->viewers->filter(function (ViewerInterface $viewer) use ($user) {
            return $viewer->getUser() === $user;
        })->toArray();

        foreach ($viewers as $viewer) {
            $this->removeViewer($viewer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRecipient(RecipientInterface $recipient): bool
    {
        return $this->recipients->contains($recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function addRecipient(RecipientInterface $recipient): void
    {
        $this->global = false;

        if (!$this->hasRecipient($recipient)) {
            $recipient->setMessage($this);
            $recipient->setSentAt($recipient->getSentAt() ?? new \DateTime());
            $this->recipients->add($recipient);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeRecipient(RecipientInterface $recipient): void
    {
        if ($this->hasRecipient($recipient)) {
            $recipient->setMessage(null);
            $this->recipients->removeElement($recipient);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    /**
     * @return array
     */
    private function getRecipientHashes(): array
    {
        $recipients = [];

        if ($this->global) {
            return ['all'];
        }

        /** @var RecipientInterface $value */
        foreach ($this->getRecipients()->toArray() as $value) {
            $recipients[] = $value->getUser()->getRecipientHash();
        }

        return $recipients;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): array
    {
        return [
            'global' => $this->global,
            'recipients' => $this->getRecipientHashes(),
            'expired_at' => $this->expiredAt ? $this->expiredAt->getTimestamp() : null,
            'message' => [
                'id' => $this->getId(),
                'type' => $this->getType(),
                'title' => $this->getTitle(),
                'metas' => $this->getMetas(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isSeen(UserInterface $user): bool
    {
        // Note: To optimize massive query
        // 1. add - dql.leftJoin('o.viewers', 'v', Join::WITH, 'v.user = :vUser')
        // 2. add - sql.addSelect('v')
        return $this->viewers->exists(function (int $n, ViewerInterface $viewer) use ($user) {
            return $viewer->getUser() === $user;
        });
    }
}
