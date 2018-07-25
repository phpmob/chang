<?php

declare(strict_types=1);

namespace Chang\Web\Form\Type;

use PhpMob\MediaBundle\Form\Type\ImageType;

class WebUserPictureType extends ImageType
{
    /**
     * {@inheritdoc}
     */
    public function getFilterSection()
    {
        return 'web_user_picture';
    }
}
