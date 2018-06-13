<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog;

use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeTranslation;

/**
 * Class CatalogAggRepositoryMapper
 *
 * @package UmberFirm\Component\Catalog
 */
class CatalogAggRepositoryMapper
{
    /**
     * @param string $facet
     *
     * @return string
     */
    public static function repositoryMap(string $facet): string
    {
        if ($facet === 'manufacturer') {
            return Manufacturer::class;
        }

        return AttributeTranslation::class;
    }
}
