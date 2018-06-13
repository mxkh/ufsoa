<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Filter\Factory;

use UmberFirm\Component\Catalog\Filter\Factory\FilterFactory;
use UmberFirm\Component\Catalog\Filter\NestedQueryInterface;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;

/**
 * Class FilterFactoryTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Filter\Factory
 */
class FilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterFactory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->factory = new FilterFactory();

        parent::setUp();
    }

    public function testCreateCheckboxFacetFilter()
    {
        $filter = $this->factory->createFacetFilter('checkbox');

        $this->assertInstanceOf(NestedQueryInterface::class, $filter);
        $this->assertEquals(NestedQueryInterface::FACET_STRING, $filter->getPath());
        $this->assertEquals(NestedQueryInterface::FILTER_STRING, $filter->getType());
    }

    public function testCreateRangeFacetFilter()
    {
        $filter = $this->factory->createFacetFilter('range');

        $this->assertInstanceOf(NestedQueryInterface::class, $filter);
        $this->assertEquals(NestedQueryInterface::FACET_NUMBER, $filter->getPath());
        $this->assertEquals(NestedQueryInterface::FILTER_RANGE, $filter->getType());
    }

    public function testCreateQueryStringFilter()
    {
        $filter = $this->factory->createQueryStringFilter();

        $this->assertInstanceOf(QueryStringNestedQueryInterface::class, $filter);
    }

    public function testCreateNotSupportedFacetFilter()
    {
        $filter = $this->factory->createFacetFilter('not_supported_facet_filter');

        $this->assertNull($filter);
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateFacetFilterWithWrongArgumentType()
    {
        $this->factory->createFacetFilter(100);
    }
}
