<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Tests\Unit\Filter;

use Elastica\Query\AbstractQuery;
use UmberFirm\Component\Catalog\Filter\QueryStringFilter;
use UmberFirm\Component\Catalog\Filter\QueryStringNestedQueryInterface;

/**
 * Class QueryStringFilterTest
 *
 * @package UmberFirm\Component\Catalog\Tests\Unit\Filter
 */
class QueryStringFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryStringFilter
     */
    private $queryStringFilter;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->queryStringFilter = new QueryStringFilter();

        parent::setUp();
    }

    public function testFilterQuery()
    {
        $query = $this->queryStringFilter->getQuery();

        $this->assertInstanceOf(AbstractQuery::class, $query);
    }

    public function testSetDefaultField()
    {
        $data = 'search_data.full_text';
        $filter = $this->queryStringFilter->setDefaultField('full_text');

        $this->assertInstanceOf(QueryStringNestedQueryInterface::class, $filter);
        $this->assertEquals($data, $filter->getDefaultField());
    }

    public function testSetDefaultOperator()
    {
        $operator = $this->queryStringFilter->getDefaultOperator();

        $this->assertEquals(
            QueryStringNestedQueryInterface::OPERATOR_AND,
            $operator
        );

        $filter = $this->queryStringFilter->setDefaultOperator('OR');

        $this->assertInstanceOf(QueryStringNestedQueryInterface::class, $filter);
        $this->assertEquals(
            QueryStringNestedQueryInterface::OPERATOR_OR,
            $filter->getDefaultOperator()
        );
    }

    public function testPhrase()
    {
        $phrase = 'Manufacturer brand name';
        $filter = $this->queryStringFilter->setPhrase($phrase);

        $this->assertInstanceOf(QueryStringNestedQueryInterface::class, $filter);
        $this->assertEquals($phrase, $filter->getPhrase());
    }
}
