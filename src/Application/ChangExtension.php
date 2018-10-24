<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ChangExtension extends Extension
{
    private static $configExts = '.{php,xml,yaml,yml}';
    private static $config = [];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * {@inheritdoc}
     */
    public static function loadPrependConfigure(Kernel $kernel, ContainerBuilder $container, LoaderInterface $loader)
    {
        $confDir = $kernel->getProjectDir() . '/config';
        $container->setParameter('chang.dir', realpath(__DIR__ . '/..'));

        $loader->load($confDir . '/{chang}/*' . self::$configExts, 'glob');
        $loader->load($confDir . '/{chang}/' . $kernel->getEnvironment() . '/**/*' . self::$configExts, 'glob');

        $configs = $container->getExtensionConfig(Configuration::NAME);
        self::$config = (new Processor())->processConfiguration(new Configuration(), $configs);

        $container->setParameter('chang.driver', self::$config['driver']);
        $container->setParameter('chang.packages', self::$config['packages']);

        foreach ($kernel->getBundles() as $bundle) {
            if ($bundle instanceof PrependConfigureInterface && $configDir = $bundle->getConfigDir()) {
                $loader->load(rtrim($configDir, '/') . '/**/*' . self::$configExts, 'glob');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function loadPackages(ContainerBuilder $container, LoaderInterface $loader)
    {
        if (!isset(self::$config['packages'])) {
            throw new \RuntimeException('"chang.packages" could not be loaded. May be the `loadPrependConfigure` are you forgot to call?');
        }

        $compilers = [];
        foreach (self::$config['packages'] as $package => $packages) {
            $package = OptionResolver::camelize($package);
            foreach ($packages as $feature => $cfg) {
                if (false === $cfg['enabled']) {
                    continue;
                }

                $resource = __DIR__ . sprintf('/../%s/Resources/config/%s' . self::$configExts, $package, $feature);
                $loader->load($resource, 'glob');

                // override parameters
                if ('full' === $feature) {
                    foreach ($cfg['options'] as $option => $value) {
                        self::overrideOptions($container, $package, $option, $value);
                    }
                } else {
                    self::overrideOptions($container, $package, $feature, $cfg['options']);
                }
            }

            // add compilers
            $packageCompilers = sprintf('chang.packages.%s.compilers', OptionResolver::underscore($package));
            if ($container->hasParameter($packageCompilers)) {
                $compilers = array_merge($compilers, (array)$container->getParameter($packageCompilers));
            }
        }

        $container->setParameter('chang.compilers', $compilers);
    }

    /**
     * @param ContainerBuilder $container
     * @param $package
     * @param $feature
     * @param array $config
     */
    private static function overrideOptions(ContainerBuilder $container, $package, $feature, array $config): void
    {
        $parameterName = OptionResolver::makeParameterName($package, $feature);
        $options = $config;

        if ($container->hasParameter($parameterName)) {
            $defaultOptions = $container->getParameter($parameterName);
            $options = [];

            foreach ($defaultOptions as $option => $value) {
                $options[$option] = array_key_exists($option, $config) ? $config[$option] : $value;
            }
        }

        $container->setParameter($parameterName, $options);
    }
}
