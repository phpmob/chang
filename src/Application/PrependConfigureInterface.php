<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

interface PrependConfigureInterface extends BundleInterface
{
    /**
     * @return null|string
     */
    public function getConfigDir(): ?string;
}
