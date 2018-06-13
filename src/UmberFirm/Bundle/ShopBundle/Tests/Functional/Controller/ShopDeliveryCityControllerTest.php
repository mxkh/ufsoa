<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopDeliveryCityControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopDeliveryCityControllerTest extends BaseTestCase
{
    /**
     * @var UuidEntityInterface[]
     */
    private static $entities;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'city' => self::$entities['slavutych']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $kyiv = new City();
        $kyiv->setName('Kyiv');
        $kyiv->setRef('some-ref-id');
        static::getObjectManager()->persist($kyiv);

        $slavutych = new City();
        $slavutych->setName('Slavutych');
        $slavutych->setRef('some-ref-id');
        static::getObjectManager()->persist($slavutych);

        $hm = new Shop();
        $hm->setName('HM');
        $hm->setApiKey('000000000000000000000000000000000000');
        static::getObjectManager()->persist($hm);

        $novaPoshtaWarehouse = new Delivery();
        $novaPoshtaWarehouse->setCode('nova_poshta_warehouse');
        $novaPoshtaWarehouse->setName('NV', 'en');
        $novaPoshtaWarehouse->setDescription('Some description for NV', 'en');
        $novaPoshtaWarehouse->mergeNewTranslations();
        static::getObjectManager()->persist($novaPoshtaWarehouse);

        $shopDelivery = new ShopDelivery();
        $shopDelivery->setShop($hm);
        $shopDelivery->setDelivery($novaPoshtaWarehouse);
        static::getObjectManager()->persist($shopDelivery);

        $shopDeliveryCity = new ShopDeliveryCity();
        $shopDeliveryCity->setShop($hm);
        $shopDeliveryCity->setCity($kyiv);
        $shopDeliveryCity->setShopDelivery($shopDelivery);
        static::getObjectManager()->persist($shopDeliveryCity);

        self::$entities['kyiv'] = $kyiv;
        self::$entities['slavutych'] = $slavutych;
        self::$entities['hm'] = $hm;
        self::$entities['novaPoshtaWarehouse'] = $novaPoshtaWarehouse;
        self::$entities['shopDelivery'] = $shopDelivery;
        self::$entities['shopDeliveryCity'] = $shopDeliveryCity;

        static::getObjectManager()->flush();
    }

    public function testCGetDeliveryCities()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shopdelivery_cities',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
            ]
        );

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

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testCGetDeliveryCitiesNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shopdelivery_cities',
            [
                'shopDelivery' => $uuid,
            ]
        );

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shopdelivery_city',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shopdelivery_city',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shopdelivery_city',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__shop__get_shopdelivery_cities',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
            ],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testDeleteActionNotFoundShopDelivery(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shopdelivery_city',
            [
                'shopDelivery' => $uuid,
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testDeleteActionNotFoundShopDeliveryCity(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shopdelivery_city',
            [
                'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
                'shopDeliveryCity' => $uuid,
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
