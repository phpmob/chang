<?php

declare(strict_types=1);

namespace Chang\User;

final class UserEvents
{
    const PRE_USERNAME_CHANGE = 'chang.user.pre_username_change';
    const POST_USERNAME_CHANGE = 'chang.user.post_username_change';

    const PRE_EMAIL_CHANGE = 'chang.user.pre_email_change';
    const POST_EMAIL_CHANGE = 'chang.user.post_email_change';

    const REQUEST_VERIFICATION_TOKEN = \Sylius\Bundle\UserBundle\UserEvents::REQUEST_VERIFICATION_TOKEN;
}
