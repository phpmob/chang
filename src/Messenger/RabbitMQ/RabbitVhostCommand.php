<?php

declare(strict_types=1);

namespace Chang\Messenger\RabbitMQ;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RabbitVhostCommand extends Command
{
    /**
     * @var ApiManager
     */
    private $manager;

    public function __construct(ApiManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('chang:rabbit:vhost')
            ->setDescription('Manage RabbitMQ Vhost')
            ->addArgument('cmd', InputArgument::OPTIONAL, 'Vhost commands', 'all')
            ->addArgument('arg', InputArgument::OPTIONAL, 'Command arguments')
            ->setHelp(<<<'EOF'
<info>php %command.full_name% <[all, get(name), create(name), delete(name), permissions(name), aliveness(name)]></info>
<info>php %command.full_name% get ARG</info>
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arg = $input->getArgument('arg');
        $cmd = $input->getArgument('cmd');

        if ('aliveness' === $cmd) {
            $result = $this->manager->aliveness($arg);
        } else {
            $result = call_user_func_array([$this->manager->vhosts, $cmd], [$arg]);
        }

        $output->writeln(json_encode($result));
    }
}
