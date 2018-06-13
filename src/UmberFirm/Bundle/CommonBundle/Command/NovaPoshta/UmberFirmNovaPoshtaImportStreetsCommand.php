<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command\NovaPoshta;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UmberFirmNovaPoshtaImportStreetsCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command\NovaPoshta
 */
class UmberFirmNovaPoshtaImportStreetsCommand extends UmberFirmNovaPoshtaImportCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:novaposhta:import:streets')
            ->setDescription('Help to import streets from nova poshta.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importStreets();
    }
}
