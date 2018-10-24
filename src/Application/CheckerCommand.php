<?php

declare(strict_types=1);

namespace Chang\Application;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckerCommand extends ContainerAwareCommand
{
    /**
     * @var CheckerInterface[]
     */
    private $checkers;

    /**
     * @param CheckerInterface $checker
     */
    public function addChecker(CheckerInterface $checker): void
    {
        $this->checkers[] = $checker;
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
        $headers = ['Name', 'Enabled', 'Configure Status'];
        $rows = [];
        $io = new SymfonyStyle($input, $output);
        $io->table($headers, $rows);
    }
}
