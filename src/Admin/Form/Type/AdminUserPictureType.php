<?php

declare(strict_types=1);

namespace Chang\Admin\Form\Type;

use PhpMob\MediaBundle\Form\Type\ImageType;

class AdminUserPictureType extends ImageType
{
    /**
     * {@inheritdoc}
     */
    public function getFilterSection()
    {
        return 'web_user_picture';
    }
}
