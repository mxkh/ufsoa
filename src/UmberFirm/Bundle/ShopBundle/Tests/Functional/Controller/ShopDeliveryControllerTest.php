<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopDeliveryControllerTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Functional\Controller
 */
class ShopDeliveryControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $delivery = new Delivery();
        $delivery->setCode('np');
        $delivery->setName('NovaPoshta', 'en');

        $delivery2 = new Delivery();
        $delivery2->setCode('it');
        $delivery2->setName('Intime', 'en');

        $delivery3 = new Delivery();
        $delivery3->setCode('ne');
        $delivery3->setName('NightExpress', 'en');

        $delivery4 = new Delivery();
        $delivery4->setCode('up');
        $delivery4->setName('UkrPochta', 'en');

        $HMShopGroup = new ShopGroup();
        $HMShopGroup->setName('Helen Marlen Group');

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        $HMShop->setShopGroup($HMShopGroup);

        $POSHShop = new Shop();
        $POSHShop->setName('POSH.UA');
        $POSHShop->setApiKey('11111111111111111111111111111111');
        $POSHShop->setShopGroup($HMShopGroup);

        $shopDelivery = new ShopDelivery();
        $shopDelivery->setDelivery($delivery);
        $shopDelivery->setShop($HMShop);

        $shopDelivery2 = new ShopDelivery();
        $shopDelivery2->setDelivery($delivery2);
        $shopDelivery2->setShop($HMShop);

        static::getObjectManager()->persist($delivery);
        static::getObjectManager()->persist($delivery2);
        static::getObjectManager()->persist($delivery3);
        static::getObjectManager()->persist($delivery4);
        static::getObjectManager()->persist($HMShopGroup);
        static::getObjectManager()->persist($HMShop);
        static::getObjectManager()->persist($POSHShop);
        static::getObjectManager()->persist($shopDelivery);
        static::getObjectManager()->persist($shopDelivery2);

        static::getObjectManager()->flush();

        self::$entities = [
            'shop' => $HMShop,
            'POSHShop' => $POSHShop,
            'shopDelivery' => $shopDelivery,
            'shopDelivery2' => $shopDelivery2,
            'delivery' => $delivery,
            'delivery2' => $delivery2,
            'delivery3' => $delivery3,
            'delivery4' => $delivery4,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of shop delivery list
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_deliveries',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetListDeliveryNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_deliveries',
            [
                'shop' => $uuid,
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

    /**
     * Try to get list of shop delivery
     */
    public function testGetSpecifiedDeliveryAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_delivery',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'delivery' => self::$entities['shopDelivery']->getId()->toString(),
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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetSpecifiedDeliveryOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_delivery',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'delivery' => $uuid,
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetSpecifiedDeliveryOnNotFoundWithDeliveryBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_delivery',
            [
                'shop' => $uuid,
                'delivery' => self::$entities['shopDelivery']->getId()->toString(),
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

    /**
     * Testing create action
     */
    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_delivery',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
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
            json_encode(
                [
                    'delivery' => self::$entities['delivery4']->getId()->toString(),
                ]
            )
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostShopDeliveryWithUniqueFailure()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_delivery',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
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
            json_encode(
                [
                    'delivery' => self::$entities['delivery4']->getId()->toString(),
                ]
            )
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_delivery',
            [
                'shop' => $uuid
            ]
        );

        $this->client->request(
            'POST',
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

    /**
     * Test create action on invalid body params
     */
    public function testInvalidPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_delivery',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        //Validate on empty body params
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

    /**
     * Testing delete action
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_delivery',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'delivery' => static::$entities['shopDelivery']->getId()->toString(),
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
            'umberfirm__shop__get_shop_deliveries',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_delivery',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'delivery' => $uuid,
            ]
        );

        $this->client->request(
            'DELETE',
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_delivery',
            [
                'shop' => $uuid,
                'delivery' => self::$entities['shopDelivery']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
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
}
