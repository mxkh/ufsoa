<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command\NovaPoshta;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UmberFirmNovaPoshtaImportCitiesCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command\NovaPoshta
 */
class UmberFirmNovaPoshtaImportCitiesCommand extends UmberFirmNovaPoshtaImportCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:novaposhta:import:cities')
            ->setDescription('Help to import cities from nova poshta.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importCities();
    }
}
