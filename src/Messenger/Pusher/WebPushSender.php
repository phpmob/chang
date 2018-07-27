<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushSender implements SenderInterface
{
    /**
     * @var WebPush
     */
    private $sender;

    /**
     * @param array $vapid
     *
     * @throws \ErrorException
     */
    public function __construct(array $vapid)
    {
        $this->sender = new WebPush(['VAPID' => $vapid]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void
    {
        try {
            $deviceMeta = $device->getMetas();
            $messageMeta = $message->getMetas();
            $title = $message->getTitle();
            $body = $message->getContent();

            $response = $this->sender->sendNotification(Subscription::create([
                'endpoint' => $deviceMeta['endpoint'],
                'publicKey' => $deviceMeta['keys']['p256dh'],
                'authToken' => $deviceMeta['keys']['auth'],
            ]), json_encode([
                'notification' => [
                    'title' => $body ? $title : $options['message']['defaultTitle'],
                    'body' => $body ?? $title,
                    'icon' => $messageMeta['mediaUrl'] ?? $options['message']['defaultMediaUrl'],
                    'badge' => $messageMeta['mediaUrl'] ?? $options['message']['defaultMediaUrl'],
                    'image' => $messageMeta['mediaUrl'] ?? $options['message']['defaultMediaUrl'],
                    'data' => [
                        'link' => $messageMeta['actionUrl'] ?? $options['message']['defaultActionUrl'],
                    ],
                ],
            ]), true);

            if ($response['expired']) {
                throw new SenderException($response['reasonPhrase'], 404);
            }
        } catch (\Exception $e) {
            throw new SenderException($e->getMessage(), $e->getCode());
        }
    }
}
