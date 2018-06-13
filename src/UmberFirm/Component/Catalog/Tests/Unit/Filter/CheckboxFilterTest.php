<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Filter;

use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Filter\CheckboxFilter;

/**
 * Class CheckboxFilterTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Filter
 */
class CheckboxFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CheckboxFilter
     */
    private $checkboxFilter;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->checkboxFilter = new CheckboxFilter();

        parent::setUp();
    }

    public function testFilterQuery()
    {
        $query = $this->checkboxFilter->getQuery();

        $this->assertInstanceOf(AbstractQuery::class, $query);
    }
}
