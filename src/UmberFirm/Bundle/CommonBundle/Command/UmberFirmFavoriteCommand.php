<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class UmberFirmFavoriteCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmFavoriteCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:favorite:flush')
            ->setDescription('Flushing favorites');
    }

    //TODO: add flushing favorite by environment
}
