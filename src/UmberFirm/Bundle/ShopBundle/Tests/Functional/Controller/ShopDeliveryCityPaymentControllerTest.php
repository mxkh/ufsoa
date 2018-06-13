<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopDeliveryCityControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopDeliveryCityPaymentControllerTest extends BaseTestCase
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
            'shopPayment' => self::$entities['liqPayHM']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $wayForPay = new Payment();
        $wayForPay->setCode('WayForPay');
        $wayForPay->setName('Оплата карткою онлайн Visa/MasterCard (WayForPay)', 'ua');
        $wayForPay->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ua');
        $wayForPay->setType(Payment::ONLINE);
        $wayForPay->mergeNewTranslations();
        static::getObjectManager()->persist($wayForPay);

        $liqPay = new Payment();
        $liqPay->setCode('LiqPay');
        $liqPay->setName('Оплата карткою онлайн Visa/MasterCard/Privat24 (LiqPay)', 'ua');
        $liqPay->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ua');
        $liqPay->setType(Payment::ONLINE);
        $liqPay->mergeNewTranslations();
        static::getObjectManager()->persist($liqPay);

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

        $wayForPayHM = new ShopPayment();
        $wayForPayHM->setShop($hm);
        $wayForPayHM->setPayment($wayForPay);
        static::getObjectManager()->persist($wayForPayHM);

        $liqPayHM = new ShopPayment();
        $liqPayHM->setShop($hm);
        $liqPayHM->setPayment($liqPay);
        static::getObjectManager()->persist($liqPayHM);

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

        $shopDeliveryCityPayment = new ShopDeliveryCityPayment();
        $shopDeliveryCityPayment->setShop($hm);
        $shopDeliveryCityPayment->setShopPayment($wayForPayHM);
        $shopDeliveryCityPayment->setShopDeliveryCity($shopDeliveryCity);
        static::getObjectManager()->persist($shopDeliveryCityPayment);

        self::$entities['liqPayHM'] = $liqPayHM;
        self::$entities['wayForPayHM'] = $wayForPayHM;
        self::$entities['hm'] = $hm;
        self::$entities['novaPoshtaWarehouse'] = $novaPoshtaWarehouse;
        self::$entities['shopDeliveryCity'] = $shopDeliveryCity;
        self::$entities['shopDeliveryCityPayment'] = $shopDeliveryCityPayment;

        static::getObjectManager()->flush();
    }

    public function testCGetDeliveryCities()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shopdeliverycity_payments',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
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
            'umberfirm__shop__get_shopdeliverycity_payments',
            [
                'shopDeliveryCity' => $uuid,
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
            'umberfirm__shop__post_shopdeliverycity_payment',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
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
            'umberfirm__shop__post_shopdeliverycity_payment',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
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
            'umberfirm__shop__delete_shopdeliverycity_payment',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
                'shopDeliveryCityPayment' => self::$entities['shopDeliveryCityPayment']->getId()->toString(),
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
            'umberfirm__shop__get_shopdeliverycity_payments',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
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
            'umberfirm__shop__delete_shopdeliverycity_payment',
            [
                'shopDeliveryCity' => $uuid,
                'shopDeliveryCityPayment' => self::$entities['shopDeliveryCityPayment']->getId()->toString(),
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
            'umberfirm__shop__delete_shopdeliverycity_payment',
            [
                'shopDeliveryCity' => self::$entities['shopDeliveryCity']->getId()->toString(),
                'shopDeliveryCityPayment' => $uuid,
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
