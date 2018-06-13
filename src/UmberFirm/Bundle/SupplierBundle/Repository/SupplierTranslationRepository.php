<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierTranslation;

/**
 * Class SupplierTranslationRepository
 *
 * @package UmberFirm\Bundle\SupplierBundle\Repository
 */
class SupplierTranslationRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param string $locale
     *
     * @return null|object|SupplierTranslation
     */
    public function findOneByName(string $name, string $locale): ?SupplierTranslation
    {
        return $this->findOneBy(
            [
                'name' => $name,
                'locale' => $locale,
            ]
        );
    }
}
