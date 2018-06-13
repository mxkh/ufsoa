<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopPaymentSettingsControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopPaymentSettingsControllerTest extends BaseTestCase
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
            'publicKey' => 'test_merchant',
            'privateKey' => 'dhkq3vUi94{Z!5frxs(02ML',
            'returnUrl' => 'ufsoa.dev/return_url',
            'merchantAuthType' => 'SimpleSignature',
            'merchantTransactionType' => 'AUTH',
            'testMode' => false,
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

        $wayForPayHM = new ShopPayment();
        $wayForPayHM->setShop($HMShop);
        $wayForPayHM->setPayment($wayForPay);
        static::getObjectManager()->persist($wayForPayHM);

        $settings = new ShopPaymentSettings();
        $settings->setPublicKey('test_merchant');
        $settings->setPrivateKey('dhkq3vUi94{Z!5frxs(02ML');
        $settings->setReturnUrl('ufsoa.dev/return_url');
        $settings->setMerchantAuthType('SimpleSignature');
        $settings->setMerchantTransactionType('AUTH');
        $settings->setTestMode(true);
        $settings->setShopPayment($wayForPayHM);
        static::getObjectManager()->persist($settings);

        static::getObjectManager()->flush();

        self::$entities = [
            'WayForPay:HM' => $wayForPayHM,
        ];
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop-payment_settings',
            [
                'shopPayment' => static::$entities['WayForPay:HM']->getId(),
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
    public function testGetWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop-payment_settings',
            [
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

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-payment_settings',
            [
                'shopPayment' => static::$entities['WayForPay:HM']->getId()->toString(),
            ]
        );
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
        $object = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($this->payload['testMode'], $object->test_mode);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-payment_settings',
            [
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

    public function testPutAttributeAreEmptyIsNotFailure()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-payment_settings',
            [
                'shopPayment' => self::$entities['WayForPay:HM']->getId(),
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

}
