<?php

declare(strict_types=1);

namespace Chang\Admin\Tester;

use Chang\Messenger\Model\MessageInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use Chang\Messenger\Model\RecipientInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestMarkAllAsReadCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('chang:test:msg-mark-all-read')
            ->setDescription('Test make messages');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('chang.messenger.admin_message_manager');
        /** @var MessageRecipientInterface $user */
        $user = $this->getContainer()->get('sylius.repository.admin_user')->find(2);

        $manager->markAllAsRead($user);
    }
}
