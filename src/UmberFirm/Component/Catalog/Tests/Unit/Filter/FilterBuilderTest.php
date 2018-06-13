<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Repository\AttributeGroupRepository;
use UmberFirm\Component\Catalog\Filter\CheckboxFilter;
use UmberFirm\Component\Catalog\Filter\Factory\FilterFactoryInterface;
use UmberFirm\Component\Catalog\Filter\FilterBuilder;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\QueryStringFilter;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\RangeFilter;

/**
 * Class FilterBuilderTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Filter
 */
class FilterBuilderTest extends \PHPUnit_Framework_TestCase
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
     * @var AttributeGroupRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $attributes = [];

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

        $this->repository = $this->createMock(AttributeGroupRepository::class);
        $this->repository
            ->expects($this->any())
            ->method('findByCodes')
            ->willReturn([$attributeCheckbox, $attributeRange]);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->with(AttributeGroup::class)
            ->willReturn($this->repository);

        $this->filterFactory = $this->createMock(FilterFactoryInterface::class);

        $this->attributes = [
            'sale_price' => ['min' => 100, 'max' => 2500],
            'color' => ['black'],
            'size' => ['L', 'XS'],
        ];

        parent::setUp();
    }

    public function testBuildFacetRangeFilterCollection()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(new RangeFilter());

        $builder = new FilterBuilder($this->entityManager, $this->filterFactory);
        foreach ($builder->buildFacetFilterCollection(new ArrayCollection($this->attributes)) as $generator) {
            $this->assertInstanceOf(NestedQueryInterface::class, $generator);
        }
    }

    public function testBuildFacetCheckboxFilterCollection()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(new CheckboxFilter());

        $builder = new FilterBuilder($this->entityManager, $this->filterFactory);
        foreach ($builder->buildFacetFilterCollection(new ArrayCollection($this->attributes)) as $generator) {
            $this->assertInstanceOf(NestedQueryInterface::class, $generator);
        }
    }

    public function testBuildFacetNullFilterCollection()
    {
        $this->filterFactory
            ->expects($this->atLeastOnce())
            ->method('createFacetFilter')
            ->willReturn(null);

        $builder = new FilterBuilder($this->entityManager, $this->filterFactory);
        foreach ($builder->buildFacetFilterCollection(new ArrayCollection($this->attributes)) as $generator) {
            $this->assertInstanceOf(NestedQueryInterface::class, $generator);
        }
    }

    public function testBuildQueryStringFilter()
    {
        $this->filterFactory
            ->expects($this->once())
            ->method('createQueryStringFilter')
            ->willReturn(new QueryStringFilter());

        $builder = new FilterBuilder($this->entityManager, $this->filterFactory);
        $filter = $builder->buildQueryStringFilter('some phrase');

        $this->assertInstanceOf(QueryStringNestedQueryInterface::class, $filter);
    }
}
