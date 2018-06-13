<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CatalogBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Component\Catalog\Client\Elastic\Client;
use Elastica\Response as ElasticaResponse;

/**
 * Class CatalogControllerTest
 *
 * @package UmberFirm\Bundle\CatalogBundle\Tests\Functional\Controller
 */
class CatalogControllerTest extends BaseTestCase
{
    const FIXTURES_FILE = __DIR__.'/../Fixtures/catalog_object_list.json';

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $elasticaClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->elasticaClient = $this->getMockElastica();
        $this->loginEmployee();
    }

    public function testCatalogObjectList()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__catalog__get_catalogs');

        $this->client->request(
            'GET',
            $uri,
            [
                'categories' => ['dzhinsy'],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testEmptyCatalogObjectList()
    {
        $fixtures = '{"took":0,"timed_out":false,"hits":{"total":0,"max_score":0,"hits":[]}}';

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__catalog__get_catalogs');

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPaginatedCatalogObjectList()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__catalog__get_catalogs');

        $this->client->request(
            'GET',
            $uri,
            [
                'categories' => ['dzhinsy'],
                'limit' => 2,
                'page' => 3,
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testFilteredCatalogObjectList()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__catalog__get_catalogs');

        $this->client->request(
            'GET',
            $uri,
            [
                'categories' => ['dzhinsy'],
                'filter' => [
                    'size' => ['L', 'XS'],
                    'sale_price' => ['min' => 1500, 'max' => 5000],
                ],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testCatalogAggregations()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__catalog__get_catalog_aggregations');

        $this->client->request(
            'GET',
            $uri,
            [
                'categories' => ['dzhinsy'],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }
}
