<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChangBundle extends Bundle
{
    /**
     * @var string
     */
    protected $name = Configuration::NAME;

    /**
     * @var Extension
     */
    protected $extension;

    public function __construct()
    {
        $this->extension = new ChangExtension();
    }
}
