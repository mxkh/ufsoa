<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Filter\AbstractFilter;
use Elastica\Filter\Nested as NestedFilter;
use Elastica\Filter\Term as TermFilter;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Repository\AttributeGroupRepository;
use UmberFirm\Component\Catalog\Filter\Factory\FilterFactoryInterface;

/**
 * Class FilterBuilder
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
class FilterBuilder implements FilterBuilderInterface, QueryStringFilterBuilderInterface
{
    /**
     * @var AttributeGroupRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FilterFactoryInterface
     */
    private $filterFactory;

    /**
     * FilterBuilder constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FilterFactoryInterface $filterFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FilterFactoryInterface $filterFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->filterFactory = $filterFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTermQuery(array $term = []): AbstractQuery
    {
        return new Term($term);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTermsQuery(array $term = []): AbstractQuery
    {
        return new Terms($term);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTermFilter(array $term = []): AbstractFilter
    {
        return new TermFilter($term);
    }

    /**
     * {@inheritdoc}
     */
    public function buildCategoryQuery(array $categories): AbstractQuery
    {
        // TODO: Refactor, move to filter
        $nested = new Nested();
        $nested->setPath('categories');
        $nestedBool = new BoolQuery();
        $nestedBool->addMust(new Terms('categories.slug', $categories));
        $nested->setQuery($nestedBool);

        return $nested;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCategoryFilter(array $categories): AbstractFilter
    {
        // TODO: Refactor, move to filter
        $nested = new NestedFilter();
        $nested->setPath('categories');
        $nestedBool = new BoolQuery();
        $nestedBool->addMust(new Terms('categories.slug', $categories));
        $nested->setQuery($nestedBool);

        return $nested;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFacetFilterCollection(Collection $attributeCollection): \Generator
    {
        // TODO: Move to QueryParam(map=true, name="filters", requirements=@CatalogFilterConstraint, description="List of catalog filters")
        $doctrineAttributes = $this->getRepository()->findByCodes($attributeCollection->getKeys());

        foreach ($doctrineAttributes as $attribute) {
            // Creates facet collection filter
            $filter = $this->filterFactory->createFacetFilter($attribute->getAttributeGroupEnum()->getName());

            if (null === $filter) {
                continue;
            }

            $filter
                ->setName($attribute->getCode())
                ->setValue($attributeCollection->get($attribute->getCode()));

            //TODO: Add logger

            yield $filter;
        }
    }

    /**
     * @param string $phrase
     *
     * @return QueryStringNestedQueryInterface
     */
    public function buildQueryStringFilter(string $phrase): QueryStringNestedQueryInterface
    {
        $filter = $this->filterFactory->createQueryStringFilter();
        $filter->setPhrase($phrase)->setDefaultField(QueryStringNestedQueryInterface::DEFAULT_FIELD);

        return $filter;
    }

    /**
     * Lazy repository
     *
     * @return AttributeGroupRepository
     */
    private function getRepository(): AttributeGroupRepository
    {
        if (null === $this->repository) {
            $this->repository = $this->entityManager->getRepository(AttributeGroup::class);
        }

        return $this->repository;
    }
}
