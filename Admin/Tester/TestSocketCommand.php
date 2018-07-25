<?php

declare(strict_types=1);

namespace Chang\Admin\Tester;

use Chang\Messenger\Manager\MessageManagerInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestSocketCommand extends ContainerAwareCommand
{
    /**
     * @var MessageManagerInterface
     */
    private $sendManager;

    public function __construct(MessageManagerInterface $sendManager)
    {
        parent::__construct();

        $this->sendManager = $sendManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('chang:test:msg-socket')
            ->setDescription('Test send message');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var MessageRecipientInterface $user */
        $user = $this->getContainer()->get('sylius.repository.admin_user')->find(1);

        $msg = $this->sendManager->createPrivateMessage($user, 'demo', 'Demo Title สวัสดี');
        $msg->setContent('Test Socket !!ß');
        $message = TestSocketMessage::create($msg);
        $message->setRawMessage($msg);

        $this->sendManager->send($message);
    }
}
