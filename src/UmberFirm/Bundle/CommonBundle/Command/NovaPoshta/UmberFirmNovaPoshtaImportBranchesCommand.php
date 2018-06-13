<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command\NovaPoshta;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UmberFirmNovaPoshtaImportBranchesCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command\NovaPoshta
 */
class UmberFirmNovaPoshtaImportBranchesCommand extends UmberFirmNovaPoshtaImportCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:novaposhta:import:branches')
            ->setDescription('Help to import branches from nova poshta.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importBranches();
    }
}
