<?php

declare(strict_types=1);

namespace Chang\Locale\Provider;

use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TranslationLocaleProvider implements TranslationLocaleProviderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $defaultLocaleCode;

    public function __construct(ContainerInterface $container, string $defaultLocaleCode)
    {
        $this->container = $container;
        $this->defaultLocaleCode = $defaultLocaleCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinedLocalesCodes(): array
    {
        $locales = $this->container->get('sylius.repository.locale')->findAll();
        $locales = array_map(
            function (LocaleInterface $locale) {
                return $locale->getCode();
            },
            $locales
        );

        return array_unique(array_merge([$this->defaultLocaleCode], $locales));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocaleCode(): string
    {
        return $this->defaultLocaleCode;
    }
}
