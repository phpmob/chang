<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;
use Sly\NotificationPusher\ApnsPushService;
use Sly\NotificationPusher\PushManager;

class ApnSender implements SenderInterface
{
    /**
     * @var string
     */
    private $certificate;

    /**
     * @var string
     */
    private $environment;

    public function __construct(string $certificate, bool $dev = true)
    {
        $this->certificate = $certificate;
        $this->environment = $dev ? PushManager::ENVIRONMENT_DEV : PushManager::ENVIRONMENT_PROD;
    }

    /**
     * {@inheritdoc}
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void
    {
        $push = new ApnsPushService($this->certificate, null, $this->environment);
        $push->push([$device->getToken()], [$message->getContent()], [
            'device' => [
                'badge' => 90,
            ],
            'message' => [
                'badge' => 0,
                'title' => $message->getTitle(),
            ],
        ]);
    }
}
