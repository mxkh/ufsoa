<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use Elastica\Response as ElasticaResponse;
use UmberFirm\Component\Catalog\Client\Elastic\Client;

/**
 * Class ProductControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class ProductControllerTest extends BaseTestCase
{
    const FIXTURES_FILE = __DIR__.'/../Fixtures/catalog_object_one.json';
    const FIXTURES_FILE_NO_RESULT = __DIR__.'/../Fixtures/catalog_object_no_result.json';

    /**
     * @var object
     */
    public static $postResponse;

    /**
     * @var array|Shop[]|Product[]
     */
    public static $entities;

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $elasticaClient;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        static::getObjectManager()->flush();

        self::$entities = [
            'hmShop' => $hmShop,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->elasticaClient = $this->getMockElastica();
    }

    public function testGetAction()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate(
            'umberfirm__public__get_product',
            [
                'slug' => 'solntsiezashchitnyie-ochki',
            ]
        );

        $token = ['shop' => static::$entities['hmShop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetActionOnNotFoundProduct()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE_NO_RESULT);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate(
            'umberfirm__public__get_product',
            [
                'slug' => 'solntsiezashchitnyie-ochki',
            ]
        );

        $token = ['shop' => static::$entities['hmShop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
