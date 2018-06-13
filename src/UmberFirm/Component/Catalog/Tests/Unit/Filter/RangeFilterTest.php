<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Filter;

use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Filter\RangeFilter;

/**
 * Class RangeFilterTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Filter
 */
class RangeFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RangeFilter
     */
    private $rangeFilter;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->rangeFilter = new RangeFilter();

        parent::setUp();
    }

    public function testFilterQuery()
    {
        $query = $this->rangeFilter->getQuery();

        $this->assertInstanceOf(AbstractQuery::class, $query);
    }

    public function testDefaultMin()
    {
        $this->assertEquals(0, $this->rangeFilter->getMin());
    }

    public function testDefaultMax()
    {
        $this->assertEquals(0, $this->rangeFilter->getMax());
    }

    public function testMin()
    {
        $this->rangeFilter->setValue(['min' => 100]);

        $this->assertEquals(100, $this->rangeFilter->getMin());
    }

    public function testMax()
    {
        $this->rangeFilter->setValue(['max' => 99]);

        $this->assertEquals(99, $this->rangeFilter->getMax());
    }

    public function testMaxGreater()
    {
        $this->rangeFilter->setValue(
            [
                'min' => 98,
                'max' => 99,
            ]
        );

        $query = $this->rangeFilter->getQuery();

        $this->assertInstanceOf(AbstractQuery::class, $query);
    }
}
