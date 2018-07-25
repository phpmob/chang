<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

class TokenSendListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var TokenSenderInterface
     */
    private $sender;

    public function __construct(EventDispatcherInterface $dispatcher, ObjectManager $manager, TokenSenderInterface $sender)
    {
        $this->dispatcher = $dispatcher;
        $this->manager = $manager;
        $this->sender = $sender;
    }

    /**
     * @param ResourceControllerEvent $event
     */
    public function sendToken(ResourceControllerEvent $event): void
    {
        $subject = $event->getSubject();
        Assert::implementsInterface($subject, NumberAwareInterface::class);

        // Don't use this approach when take send token on multiple times as one process.
        $this->dispatcher->addListener(KernelEvents::TERMINATE, function () use ($subject) {
            $this->sender->send($subject);

            // WARNING! to secure flush data with valid state, you need to register this listener on `resource.postUpdate`
            // to make sure our model is valid (form.isValid()).
            $this->manager->flush();
        });
    }
}
