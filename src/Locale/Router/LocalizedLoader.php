<?php

declare(strict_types=1);

namespace Chang\Locale\Router;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;

class LocalizedLoader implements LoaderInterface
{
    const PREFIX_KEY = '_localized_';

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var string
     */
    private $configDir;

    public function __construct(LoaderInterface $loader, LocaleProviderInterface $localeProvider, string $configDir)
    {
        $this->loader = $loader;
        $this->localeProvider = $localeProvider;
        $this->configDir = $configDir;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        /** @var RouteCollection $routes */
        $routes = $this->loader->load(rtrim($this->configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $resource);

        $localized = clone $routes;
        $localized->addPrefix('{_locale}');
        $localized->addRequirements([
            '_locale' => join('|', $this->localeProvider->getAvailableLocalesCodes()),
        ]);

        foreach ($localized as $name => $route) {
            $route->setPath(trim($route->getPath(), '/'));
            $localized->add(self::PREFIX_KEY . $name, $route);
            $localized->remove($name);
        }

        $routes->addDefaults(['_locale' => $this->localeProvider->getDefaultLocaleCode()]);
        $routes->addCollection($localized);

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'localized' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
