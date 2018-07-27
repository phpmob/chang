<?php

declare(strict_types=1);

namespace Chang\Locale\Provider;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;

class CachedLocaleProvider implements LocaleProviderInterface
{
    /**
     * @var LocaleProviderInterface
     */
    private $decoratedProvider;

    /**
     * @var null|array
     */
    private $locales;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->decoratedProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableLocalesCodes(): array
    {
        // TODO: cached with real cache storage (purge and persist)
        return $this->locales ?? ($this->locales = $this->decoratedProvider->getAvailableLocalesCodes());
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocaleCode(): string
    {
        return $this->decoratedProvider->getDefaultLocaleCode();
    }
}
