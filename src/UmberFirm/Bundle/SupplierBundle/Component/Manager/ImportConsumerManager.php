<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Services\Import\ImportProductInterface;

/**
 * Class ImportConsumerManager
 *
 * @package UmberFirm\Bundle\SupplierBundle\Component\Manager
 */
class ImportConsumerManager implements ImportConsumerManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ImportProductInterface
     */
    private $importProduct;

    /**
     * ImportProductManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ImportProductInterface $importProduct
     *
     * @internal param ImportProductServiceInterface $importProductService
     * @internal param ImportFacadeServiceInterface $importFacadeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ImportProductInterface $importProduct
    ) {
        $this->entityManager = $entityManager;
        $this->importProduct = $importProduct;
    }

    /**
     * {@inheritdoc}
     */
    public function manage(string $importId): bool
    {
        $import = $this->entityManager->find(Import::class, $importId);
        if (null === $import) {
            return false;
        }
        $this->importProduct->loadData($import);

        if (false === $this->importProduct->validate()) {
            return false;
        }

        $this->importProduct->import();

        return true;
    }
}
