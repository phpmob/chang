<?php

declare(strict_types=1);

namespace Chang\Locale\Router;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;

class NoErrorLocaleProvider implements LocaleProviderInterface
{
    /**
     * @var LocaleProviderInterface
     */
    private $provider;

    /**
     * @var string
     */
    private $defaultLocaleCode;

    public function __construct(LocaleProviderInterface $provider, string $defaultLocaleCode)
    {
        $this->provider = $provider;
        $this->defaultLocaleCode = $defaultLocaleCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableLocalesCodes(): array
    {
        try {
            return $this->provider->getAvailableLocalesCodes();
        } catch (\Exception $e) {
            return [$this->defaultLocaleCode];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocaleCode(): string
    {
        try {
            return $this->provider->getDefaultLocaleCode();
        } catch (\Exception $e) {
            return $this->defaultLocaleCode;
        }
    }
}
