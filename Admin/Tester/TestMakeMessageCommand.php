<?php

declare(strict_types=1);

namespace Chang\Admin\Tester;

use Chang\Messenger\Model\MessageInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use Chang\Messenger\Model\RecipientInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestMakeMessageCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('chang:test:msg-make')
            ->setDescription('Test make messages');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var MessageRecipientInterface $user */
        $user = $this->getContainer()->get('sylius.repository.admin_user')->find(2);

        $manager = $this->getContainer()->get('chang.manager.admin_message');

        /** @var MessageInterface $message */
        $message = $this->getContainer()->get('chang.factory.admin_message')->createNew();
        $message->setType('demo');

        /** @var RecipientInterface $recipient */
        $recipient = $this->getContainer()->get('chang.factory.admin_recipient')->createNew();
        $recipient->setUser($user);

        $inbox = $this->getContainer()->get('chang.messenger.admin_inbox_manager');


        for ($i = 0; $i < 100; $i++) {
            $msg = clone $message;
            $msg->addRecipient(clone $recipient);
            $msg->setTitle(sprintf('%s. %s demo message title', $i, $user));
            $msg->setContent(sprintf('%s. %s demo message content', $i, $user));
            $manager->persist($msg);
        }

        for ($i = 0; $i < 100; $i++) {
            $msg = clone $message;
            $msg->setGlobal(true);
            $msg->setTitle(sprintf('%s. %s global message title', $i, $user));
            $msg->setContent(sprintf('%s. %s global message content', $i, $user));
            $manager->persist($msg);
        }

        $manager->flush();

        $inbox->markAsSent($user, false);
    }
}
