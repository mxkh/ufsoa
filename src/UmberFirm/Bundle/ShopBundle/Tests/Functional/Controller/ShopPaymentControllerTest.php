<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopPaymentControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopPaymentControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'payment' => self::$entities['LiqPay']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($HMShop);

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

        $payPal = new Payment();
        $payPal->setCode('PayPal');
        $payPal->setName('Оплата карткою онлайн Visa/MasterCard (PayPal)', 'ua');
        $payPal->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ua');
        $payPal->setType(Payment::ONLINE);
        $payPal->mergeNewTranslations();
        static::getObjectManager()->persist($payPal);

        $wayForPayHM = new ShopPayment();
        $wayForPayHM->setShop($HMShop);
        $wayForPayHM->setPayment($wayForPay);
        static::getObjectManager()->persist($wayForPayHM);

        static::getObjectManager()->flush();

        self::$entities = [
            'shop' => $HMShop,
            'WayForPay:HM' => $wayForPayHM,
            'LiqPay' => $liqPay,
            'PayPal' => $payPal,
        ];
    }

    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_payments',
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
    public function testGetListShopPaymentsNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_payments',
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
     * Try to get list of shop ShopPayments
     */
    public function testGetSpecifiedShopPaymentAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_payment',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'shopPayment' => self::$entities['WayForPay:HM']->getId()->toString(),
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
    public function testGetSpecifiedShopPaymentOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_payment',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'shopPayment' => $uuid,
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
    public function testGetSpecifiedShopPaymentOnNotFoundWithShopPaymentBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_payment',
            [
                'shop' => $uuid,
                'shopPayment' => self::$entities['WayForPay:HM']->getId()->toString(),
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
            'umberfirm__shop__post_shop_payment',
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostOnUniqueData()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_payment',
            [
                'shop' => static::$entities['WayForPay:HM']->getShop()->getId()->toString(),
            ]
        );

        $this->payload = [
            'payment' => static::$entities['WayForPay:HM']->getPayment()->getId()->toString(),
        ];

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostSpecifiedSettingOnNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_payment',
            [
                'shop' => $uuid,
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
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * Test create action on invalid body params
     */
    public function testInvalidPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_payment',
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
     * Testing update action
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_payment',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'shopPayment' => static::$entities['WayForPay:HM']->getId()->toString(),
            ]
        );

        $this->payload['payment'] = self::$entities['PayPal']->getId()->toString();

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_payment',
            [
                'shop' => $uuid,
                'shopPayment' => static::$entities['WayForPay:HM']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_payment',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'shopPayment' => $uuid,
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * Testing delete action
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_payment',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'shopPayment' => static::$entities['WayForPay:HM']->getId()->toString(),
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
            'umberfirm__shop__get_shop_payments',
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
            'umberfirm__shop__put_shop_payment',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'shopPayment' => $uuid,
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
            'umberfirm__shop__put_shop_payment',
            [
                'shop' => $uuid,
                'shopPayment' => self::$entities['WayForPay:HM']->getId()->toString(),
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
