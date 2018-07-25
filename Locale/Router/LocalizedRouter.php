<?php

declare(strict_types=1);

namespace Chang\Locale\Router;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class LocalizedRouter extends Router
{
    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var string
     */
    private $locales;

    /**
     * @param LocaleProviderInterface $localeProvider
     */
    public function setLocaleProvider(LocaleProviderInterface $localeProvider): void
    {
        $this->localeProvider = $localeProvider;
    }

    /**
     * @return null|string
     */
    private function getLocales(): ?string
    {
        if ($this->locales) {
            return $this->locales;
        }

        return $this->locales = join('|', $this->localeProvider->getAvailableLocalesCodes());
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $predict = strtolower($parameters['_locale'] ?? explode('/', $this->context->getPathInfo())[1]);
        $default = $this->localeProvider->getDefaultLocaleCode();

        if ($predict === $default || !$locales = $this->getLocales()) {
            return parent::generate($name, $parameters, $referenceType);
        }

        $prefix = LocalizedLoader::PREFIX_KEY;

        if (preg_match("/^(?!$prefix)/", $name) && preg_match("/$locales/", $predict)) {
            try {
                return parent::generate($prefix . $name, $parameters, $referenceType);
            } catch (RouteNotFoundException $e) {
                $name = preg_replace("/^$prefix/", '', $name);

                if ($this->logger) {
                    $this->logger->warning(
                        sprintf('Unable to generate a localized URL for the named route "%s" as such route does not exist.', $name)
                    );
                }

                return parent::generate($name, $parameters, $referenceType);
            }
        }

        return parent::generate($name, $parameters, $referenceType);
    }
}
