<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog;

use Doctrine\Common\Collections\ArrayCollection;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\FinderInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;
use UmberFirm\Bundle\ProductBundle\Model\Elastica\ProductModel;
use UmberFirm\Component\Catalog\Elastica\Filter\BoolFilter;
use UmberFirm\Component\Catalog\Query\AggregationInterface;
use UmberFirm\Component\Catalog\Query\FilteredFacetAggregation;
use UmberFirm\Component\Catalog\Query\QueryBuilder;

/**
 * Class Catalog
 *
 * @package UmberFirm\Component\Catalog
 */
final class Catalog implements PaginatedCatalogInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var FinderInterface
     */
    private $finder;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var int
     */
    private $perPage = 10;

    /**
     * @var PagerfantaFactory
     */
    private $pagerfantaFactory;

    /**
     * @var CatalogAggHelper
     */
    private $catalogAggHelper;

    /**
     * Catalog constructor.
     *
     * @param RequestStack $requestStack
     * @param TransformedFinder $finder
     * @param QueryBuilder $queryBuilder
     * @param CatalogAggHelper $catalogAggHelper
     */
    public function __construct(
        RequestStack $requestStack,
        TransformedFinder $finder,
        QueryBuilder $queryBuilder,
        CatalogAggHelper $catalogAggHelper
    ) {
        $this->finder = $finder;
        $this->queryBuilder = $queryBuilder;
        $this->requestStack = $requestStack;
        $this->pagerfantaFactory = new PagerfantaFactory();
        $this->catalogAggHelper = $catalogAggHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(string $slug, ?bool $isActive, ?bool $isOutOfStock): ?ProductModel
    {
        $this->queryBuilder->addTermsQuery([['slug' => $slug]]);

        if (null !== $isActive) {
            $this->queryBuilder->addTermsQuery([['is_active' => $isActive]]);
        }

        if (null !== $isOutOfStock) {
            $this->queryBuilder->addTermsQuery([['is_out_of_stock' => $isOutOfStock]]);
        }

        $product = $this->finder->find($this->queryBuilder->getQuery());

        return true === empty($product) ? null : $product['0'];
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $attributes = [], array $sort = [], string $phrase = ''): array
    {
        //TODO: In future add data provider and attribute parser
        $query = $this->getQuery([], $attributes, $sort, [['slug' => 'krossovki-5082']], $phrase);

        return $this->finder->find($query);
    }

    /**
     * {@inheritdoc}
     */
    public function findPaginated(
        array $categories = [],
        array $attributes = [],
        array $sort = [],
        array $terms = [],
        string $phrase = ''
    ): PaginatedRepresentation {
        //TODO: In future add data provider and attribute parser
        $query = $this->getQuery($categories, $attributes, $sort, $terms, $phrase);

        $pager = $this->getPager($query);

        $representation = $this->pagerfantaFactory->createRepresentation(
            $pager,
            new Route(
                $this->requestStack->getCurrentRequest()->get('_route'),
                [
                    'q' => $phrase,
                    'filter' => $attributes,
                    'sort' => $sort,
                    'categories' => $categories,
                    'terms' => $terms,
                ],
                true
            )
        );

        return $representation;
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregations(
        array $categories = [],
        array $attributes = [],
        array $sort = [],
        array $terms = [],
        string $phrase = ''
    ): array {
        //TODO: In future add data provider and attribute parser
        $commonQuery = $this->getAggregationalQuery($categories, $phrase)->setSize(0);

        $commonPager = $this->getPager($commonQuery);

        /** @var FantaPaginatorAdapter $commonAdapter */
        $commonAdapter = $commonPager->getAdapter();

        $defaultAggregations = $commonAdapter->getAggregations();

        $queryBuilder = $this->queryBuilder
            ->createNewQueryBuilder()
            ->buildFilterContext(new ArrayCollection($attributes))
            ->addTermsQuery($terms)
            ->buildCategoryFilter($categories);

        $query = $queryBuilder->getQuery()->setSize(0);
        $filter = $queryBuilder->getFilter();

        $query = $this->addAggregationToQuery($query, $filter, $defaultAggregations);

        if (null === $query) {
            return $defaultAggregations;
        }

        $pager = $this->getPager($query);

        /** @var FantaPaginatorAdapter $adapter */
        $adapter = $pager->getAdapter();

        $aggregations = $adapter->getAggregations();

        $facets = [];
        // Normalize aggregation response
        $i = 0;
        foreach ($aggregations as $facet => $aggregation) {
            // Prepare facet response
            $facets[$i] = [
                'slug' => $facet,
                'name' => $this->catalogAggHelper->facetKeyAdapter($facet),
                'values' => [],
            ];

            // Adding values to the facet
            foreach ($aggregation[$facet.'_facet']['facet_key']['facet_value']['buckets'] as $bucket) {
                $facets[$i]['values'][] = [
                    'slug' => $bucket['key'],
                    'name' => $this->catalogAggHelper->facetNameAdapter($facet, $bucket['key']),
                    'count' => $bucket['doc_count'],
                    'selected' => $this->catalogAggHelper->isSelectedFacet($bucket['key'], $facet, $attributes),
                ];
            }
            ++$i;
        }

        return $facets;
    }


    /**
     * @param Query $query
     * @param BoolFilter $filter
     * @param array $defaultAggregations
     *
     * @return Query|null
     */
    private function addAggregationToQuery(Query $query, BoolFilter $filter, array $defaultAggregations): ?Query
    {
        if (false === isset($defaultAggregations[AggregationInterface::DEFAULT_AGG_NAME])) {
            return null;
        }

        foreach ($defaultAggregations
                 [AggregationInterface::DEFAULT_AGG_NAME]
                 [AggregationInterface::DEFAULT_FACET_KEY_NAME]['buckets'] as $facet) {

            $agg = new FilteredFacetAggregation($facet['key'], $filter);
            $query->addAggregation($agg->getAggregation());
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentPage(int $currentPage): PaginatedCatalogInterface
    {
        if ($currentPage > 0) {
            $this->currentPage = $currentPage;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPerPage(int $perPage): PaginatedCatalogInterface
    {
        if ($perPage > 0) {
            $this->perPage = $perPage;
        }

        return $this;
    }

    /**
     * Class helper method
     *
     * @param Query $query
     *
     * @return Pagerfanta
     */
    private function getPager(Query $query): Pagerfanta
    {
        $pager = $this->finder->findPaginated($query);
        $pager
            ->setMaxPerPage($this->perPage)
            ->setCurrentPage($this->currentPage);

        return $pager;
    }

    /**
     * Class helper method
     *
     * @param array $categories
     * @param array $attributes
     * @param array $sort
     * @param array $terms
     * @param string $phrase
     *
     * @return Query
     */
    private function getQuery(
        array $categories = [],
        array $attributes = [],
        array $sort = [],
        array $terms = [],
        string $phrase = ''
    ): Query {
        $query = $this->queryBuilder
            ->buildQueryContext(new ArrayCollection($attributes))
            ->buildSortQuery(new ArrayCollection($sort))
            ->buildQueryStringQuery($phrase)
            ->buildCategoryQuery($categories)
            ->addTermsQuery($terms)
            ->getQuery();

        return $query;
    }

    /**
     * Class helper method
     *
     * @param array $categories
     * @param string $phrase
     *
     * @return Query
     */
    private function getAggregationalQuery(array $categories, string $phrase = ''): Query
    {
        $query = $this->queryBuilder
            ->buildQueryStringQuery($phrase)
            ->buildCategoryQuery($categories)
            ->buildDefaultAggregationalQuery()
            ->getQuery();

        return $query;
    }
}
