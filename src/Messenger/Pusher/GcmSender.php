<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;
use Sly\NotificationPusher\GcmPushService;
use Sly\NotificationPusher\PushManager;

class GcmSender implements SenderInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $environment;

    public function __construct(string $apiKey, bool $dev = true)
    {
        $this->apiKey = $apiKey;
        $this->environment = $dev ? PushManager::ENVIRONMENT_DEV : PushManager::ENVIRONMENT_PROD;
    }

    /**
     * {@inheritdoc}
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void
    {
        $push = new GcmPushService($this->apiKey, $this->environment);
        $response = $push->push([$device->getToken()], [$message->getTitle()], [
            'device' => [],
            'message' => [],
        ]);

        // FIXME & TODO
        dump($response);
    }
}
