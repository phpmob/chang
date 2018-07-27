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
    public static function loadPackages(Kernel $kernel, ContainerBuilder $container, LoaderInterface $loader)
    {
        $confDir = $kernel->getProjectDir() . '/config';
        $configExts = '.{php,xml,yaml,yml}';
        $container->setParameter('chang.dir', realpath(__DIR__ . '/..'));

        $loader->load($confDir . '/{chang}/*' . $configExts, 'glob');
        $loader->load($confDir . '/{chang}/' . $kernel->getEnvironment() . '/**/*' . $configExts, 'glob');

        $configs = $container->getExtensionConfig(Configuration::NAME);
        $config = (new Processor())->processConfiguration(new Configuration(), $configs);

        $container->setParameter('chang.driver', $config['driver']);

        foreach ($kernel->getBundles() as $bundle) {
            if ($bundle instanceof PrependConfigureInterface && $configDir = $bundle->getConfigDir()) {
                $loader->load(rtrim($configDir, '/') . '/**/*' . $configExts, 'glob');
            }
        }

        foreach ($config['packages'] as $package => $packages) {
            $package = OptionResolver::camelize($package);
            foreach ($packages as $feature => $cfg) {
                if (false === $cfg['enabled']) {
                    continue;
                }

                $resource = __DIR__ . sprintf('/../%s/Resources/config/%s' . $configExts, $package, $feature);
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
        }
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
