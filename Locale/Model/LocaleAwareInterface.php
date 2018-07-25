<?php

declare(strict_types=1);

namespace Chang\Locale\Model;

interface LocaleAwareInterface
{
    /**
     * @return null|string
     */
    public function getLocaleCode(): ?string;

    /**
     * @param null|string $localeCode
     */
    public function setLocaleCode(?string $localeCode): void;
}
