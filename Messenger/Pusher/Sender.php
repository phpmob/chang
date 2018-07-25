<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Sender implements SenderInterface
{
    /**
     * @var SenderRegistryInterface
     */
    private $registry;

    /**
     * @var array
     */
    private $options;

    public function __construct(SenderRegistryInterface $registry, array $options)
    {
        $this->registry = $registry;
        $this->options = $this->configureOptions($options);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function configureOptions(array $config): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'device' => [],
        ]);

        $resolver->setRequired(['message']);
        $resolver->setAllowedTypes('message', 'array');
        $resolver->setAllowedTypes('device', 'array');

        $options = $resolver->resolve($config);

        $deviceResolver = new OptionsResolver();
        $options['device'] = $deviceResolver->resolve($options['device']);

        $messageResolver = new OptionsResolver();
        $messageResolver->setRequired(['defaultTitle', 'defaultMediaUrl', 'defaultActionUrl']);

        $messageResolver->setAllowedTypes('defaultTitle', 'string');
        $messageResolver->setAllowedTypes('defaultMediaUrl', 'string');
        $messageResolver->setAllowedTypes('defaultActionUrl', 'string');

        $options['message'] = $messageResolver->resolve($options['message']);

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void
    {
        $messageMeta = $message->getMetas();
        $options = array_replace_recursive($this->options, $options);
        $actionUrl = $messageMeta['actionUrl'] ?? $options['message']['defaultActionUrl'];
        $queries = ['__ref-msg-id' => $message->getId(), '__ref-msg-pf' => $device->getPlatform()];
        $urls = explode('?', $actionUrl);

        if (!empty($urls[1])) {
            $urls[1] .= '&' . http_build_query($queries);
        } else {
            $urls[1] = http_build_query($queries);
        }

        $message->setMetas(array_merge($message->getMetas(), [
            'actionUrl' => implode('?', $urls),
        ]));

        $this->registry->get($device->getPlatform())->sendTo($device, $message, $options);
    }
}
