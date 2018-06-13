<?php

namespace UmberFirm\Bundle\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UmberFirmDatabaseResetCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmDatabaseResetCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:database:reset')
            ->setDescription('Help to reset database with one command.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeDoctrineDatabaseDrop($output);
        $this->executeDoctrineDatabaseCreate($output);
        $this->executeDoctrineMigrationsMigrate($output);
        $this->executeDoctrineFixturesLoad($output);
    }

    /**
     * @param OutputInterface $output
     */
    private function executeDoctrineDatabaseDrop(OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:drop');
        $arguments = [
            'command' => 'doctrine:database:drop',
            '--force' => true,
            '--env' => 'dev',
        ];

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }

    /**
     * @param OutputInterface $output
     */
    private function executeDoctrineDatabaseCreate(OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = [
            'command' => 'doctrine:database:create',
            '--env' => 'dev',
        ];

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }

    /**
     * @param OutputInterface $output
     */
    private function executeDoctrineMigrationsMigrate(OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:migrations:migrate');
        $arguments = [
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
            '--query-time' => true,
            '--env' => 'dev',
        ];

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }

    /**
     * @param OutputInterface $output
     */
    private function executeDoctrineFixturesLoad(OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = [
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
            '--purge-with-truncate' => false,
            '--env' => 'dev',
        ];

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }
}
