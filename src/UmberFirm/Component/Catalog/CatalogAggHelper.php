<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog;

use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeTranslation;

/**
 * Class CatalogAggTrait
 *
 * @package UmberFirm\Component\Catalog
 */
class CatalogAggHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CatalogAggHelper constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $slug
     * @param string $facet
     * @param array $attributes
     *
     * @return bool
     */
    public function isSelectedFacet(string $slug, string $facet, array $attributes): bool
    {
        if (false === isset($attributes[$facet])) {
            return false;
        }

        return in_array($slug, $attributes[$facet]);
    }

    /**
     * @param string $facet
     * @param string $slug
     *
     * @return string
     */
    public function facetNameAdapter(string $facet, string $slug): string
    {
        $repository = $this->entityManager->getRepository(CatalogAggRepositoryMapper::repositoryMap($facet));

        /** @var Manufacturer|AttributeTranslation $object */
        $object = $repository->findOneBy(['slug' => $slug]);

        if (null !== $object) {
            return $facet === 'manufacturer' ? $object->getName() : $object->getName();
        }

        return '';
    }

    /**
     * @param string $facet
     *
     * @return string
     */
    public function facetKeyAdapter(string $facet): string
    {
        $repository = $this->entityManager->getRepository(AttributeGroup::class);
        $object = $repository->findOneBy(['code' => $facet]);

        if (null !== $object) {
            return $object->getName();
        }

        return '';
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
