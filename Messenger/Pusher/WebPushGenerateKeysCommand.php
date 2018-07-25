<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Minishlink\WebPush\VAPID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebPushGenerateKeysCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('chang:msg:webpush-keys')
            ->setDescription('Generate VAPID keys for WebPush.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = VAPID::createVapidKeys();
        $output->writeln(sprintf('Your public key is: <info>%s</info> ', $keys['publicKey']));
        $output->writeln(sprintf('Your private key is: <info>%s</info>', $keys['privateKey']));
    }
}
