<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CheckerCommand extends ContainerAwareCommand
{
    /**
     * @var CheckerInterface[]
     */
    private $checkers = [];

    /**
     * @var ContainerBuilder|null
     */
    protected $containerBuilder;

    /**
     * @param CheckerInterface $checker
     */
    public function addChecker(CheckerInterface $checker): void
    {
        $this->checkers[$checker::getName()] = $checker;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('chang:checker')
            ->setDescription('Chang configuration checker.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packages = $this->getContainer()->getParameter('chang.packages');
        $headers = ['Package', 'Feature', 'Enabled', 'Configure Status'];
        $rows = [];

        foreach (array_keys($this->checkers) as $package) {
            if (array_key_exists($package, $packages)) {
                continue;
            }

            $packages[$package] = [];
        }

        ksort($packages);

        foreach ($packages as $package => $features) {
            $rows[] = ["<info>$package</info>", null, empty($features) ? '<comment>No</comment>' : null, null];

            foreach ($features as $feature => $config) {
                $rows[] = [
                    null,
                    $feature,
                    $config['enabled'] ? '<info>Yes</info>' : '<comment>No</comment>',
                    $this->getCustomChecker($package, $feature, $config),
                ];
            }

            $rows[] = new TableSeparator();
        }

        $io = new Table($output);
        $io->setHeaders($headers);
        $io->setRows($rows);
        $io->render();
    }

    private function getCustomChecker(string $package, string $feature, array $config): string
    {
        if (array_key_exists($package, $this->checkers)) {
            return $this->checkers[$package]->check($package, $feature, $config, $this->getContainerBuilder());
        }

        return '';
    }

    private function getContainerBuilder()
    {
        if ($this->containerBuilder) {
            return $this->containerBuilder;
        }

        $kernel = $this->getApplication()->getKernel();

        if (!$kernel->isDebug() || !(new ConfigCache($kernel->getContainer()->getParameter('debug.container.dump'), true))->isFresh()) {
            $buildContainer = \Closure::bind(function () { return $this->buildContainer(); }, $kernel, \get_class($kernel));
            $container = $buildContainer();
            $container->getCompilerPassConfig()->setRemovingPasses(array());
            $container->compile();
        } else {
            (new XmlFileLoader($container = new ContainerBuilder(), new FileLocator()))->load($kernel->getContainer()->getParameter('debug.container.dump'));
        }

        return $this->containerBuilder = $container;
    }
}
