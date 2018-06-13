<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Repository\AttributeGroupRepository;
use UmberFirm\Component\Catalog\Filter\CheckboxFilter;
use UmberFirm\Component\Catalog\Filter\Factory\FilterFactoryInterface;
use UmberFirm\Component\Catalog\Filter\FilterBuilder;
use UmberFirm\Component\Catalog\Filter\QueryStringFilter;
use UmberFirm\Component\Catalog\Filter\RangeFilter;
use UmberFirm\Component\Catalog\Query\QueryBuilder;
use UmberFirm\Component\Catalog\Query\QueryBuilderInterface;
use UmberFirm\Component\Catalog\Query\SortBuilder;

/**
 * Class QueryBuilderTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Query
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FilterFactoryInterface
     */
    private $filterFactory;

    /**
     * @var Collection
     */
    private $attributes;

    /**
     * @var Collection
     */
    private $sort;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $attributeEnumCheckbox = $this->createMock(AttributeGroupEnum::class);
        $attributeEnumCheckbox
            ->expects($this->any())
            ->method('getName')
            ->willReturn('checkbox');

        $attributeEnumRange = $this->createMock(AttributeGroupEnum::class);
        $attributeEnumRange
            ->expects($this->any())
            ->method('getName')
            ->willReturn('range');

        $attributeCheckbox = $this->createMock(AttributeGroup::class);
        $attributeCheckbox
            ->expects($this->any())
            ->method('getAttributeGroupEnum')
            ->willReturnOnConsecutiveCalls($attributeEnumCheckbox);
        $attributeCheckbox
            ->expects($this->any())
            ->method('getCode')
            ->willReturn('color');

        $attributeRange = $this->createMock(AttributeGroup::class);
        $attributeRange
            ->expects($this->any())
            ->method('getAttributeGroupEnum')
            ->willReturn($attributeEnumRange);
        $attributeRange
            ->expects($this->any())
            ->method('getCode')
            ->willReturn('sale_price');

        $repository = $this->createMock(AttributeGroupRepository::class);
        $repository
            ->expects($this->any())
            ->method('findByCodes')
            ->willReturn([$attributeCheckbox, $attributeRange]);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->with(AttributeGroup::class)
            ->willReturn($repository);

        $this->filterFactory = $this->createMock(FilterFactoryInterface::class);

        $this->attributes = new ArrayCollection(
            [
                'sale_price' => ['min' => 100, 'max' => 2500],
                'color' => ['black'],
                'size' => ['L', 'XS'],
            ]
        );

        $this->sort = new ArrayCollection(['sale_price' => 'asc']);

        parent::setUp();
    }

    public function testBuildRangeFilterQuery()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(new RangeFilter());

        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryContext($this->attributes);

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildCheckboxFilterQuery()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(new CheckboxFilter());

        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryContext($this->attributes);

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildFilterQueryEmptyCollection()
    {
        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryContext(new ArrayCollection());

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildFilterQueryWhenFilterNull()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(null);

        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryContext($this->attributes);

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildSortQuery()
    {
        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildSortQuery($this->sort);

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildSortQueryEmptyCollection()
    {
        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildSortQuery(new ArrayCollection());

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildQueryStringQuery()
    {
        $this->filterFactory
            ->expects($this->once())
            ->method('createQueryStringFilter')
            ->willReturn(new QueryStringFilter());

        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryStringQuery('some phrase');

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testBuildQueryStringQueryEmptyPhrase()
    {
        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $result = $builder->buildQueryStringQuery('');

        $this->assertInstanceOf(QueryBuilderInterface::class, $result);
    }

    public function testGetQuery()
    {
        $builder = new QueryBuilder(
            new FilterBuilder($this->entityManager, $this->filterFactory),
            new SortBuilder()
        );

        $query = $builder->getQuery();

        $this->assertInstanceOf(Query::class, $query);
    }
}
