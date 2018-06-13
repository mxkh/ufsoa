<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Manager;

use UmberFirm\Bundle\MediaBundle\Component\Manager\MediaManagerInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Interface ImportManagerInterface
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Manager
 */
interface ImportManagerInterface extends MediaManagerInterface
{
    /**
     * @param Import $import
     *
     * @return void
     */
    public function import(Import $import): void;
}
