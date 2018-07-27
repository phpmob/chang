<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SpoolSender implements SenderInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * @var array
     */
    private $buffers = [];

    public function __construct(ObjectManager $manager, SenderInterface $sender)
    {
        $this->manager = $manager;
        $this->sender = $sender;
    }

    /**
     * {@inheritdoc}
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void
    {
        $this->buffers[] = [$device, $message, $options];
    }

    /**
     * Flush all pushes.
     */
    public function flush(): void
    {
        if (empty($this->buffers)) {
            return;
        }

        foreach ($this->buffers as $buffer) {
            try {
                $this->sender->sendTo($buffer[0], $buffer[1], $buffer[2]);
            } catch (SenderException $e) {
                if (404 === $e->getCode()) {
                    $this->manager->remove($buffer[0]);
                }
            }
        }

        // WARNING! this will flush every models.
        // Worst case if we have invalid state model and not empty of this.buffers
        // buffers and invalid state models will be flush (eg. in process of none form.isValid())
        $this->manager->flush();
    }
}
