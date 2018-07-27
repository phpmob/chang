<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use PhpMob\ThaiBulkSms\SenderInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;

class PinSender implements TokenSenderInterface
{
    /**
     * @var GeneratorInterface
     */
    private $pinGenerator;

    /**
     * @var SenderInterface
     */
    private $smsSender;

    /**
     * @var bool
     */
    private $disabled;

    public function __construct(GeneratorInterface $generator, SenderInterface $sender, bool $disabled = true)
    {
        $this->pinGenerator = $generator;
        $this->smsSender = $sender;
        $this->disabled = $disabled;
    }

    /**
     * @param NumberAwareInterface $user
     *
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function send(NumberAwareInterface $user): void
    {
        $token = $this->pinGenerator->generate();
        $user->setPhoneNumberVerificationToken($token);
        $user->setPhoneNumberRequestedAt(new \DateTime());

        if (!$this->disabled) {
            $this->smsSender->send($user->getPhoneNumber(), 'Your token is: ' . $token);
        }
    }
}
