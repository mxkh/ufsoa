<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Payment;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class PaymentController
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Payment
 */
class PaymentControllerTest extends BaseTestCase
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

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($HMShop);

        $wayForPay = new Payment();
        $wayForPay->setCode('WayForPay');
        $wayForPay->setName('Оплата карткою онлайн Visa/MasterCard (WayForPay)', 'ru');
        $wayForPay->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ru');
        $wayForPay->setType(Payment::ONLINE);
        $wayForPay->mergeNewTranslations();
        static::getObjectManager()->persist($wayForPay);

        $liqPay = new Payment();
        $liqPay->setCode('LiqPay');
        $liqPay->setName('Оплата карткою онлайн Visa/MasterCard/Privat24 (LiqPay)', 'ru');
        $liqPay->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ru');
        $liqPay->setType(Payment::ONLINE);
        $liqPay->mergeNewTranslations();
        static::getObjectManager()->persist($liqPay);

        $payPal = new Payment();
        $payPal->setCode('PayPal');
        $payPal->setName('Оплата карткою онлайн Visa/MasterCard (PayPal)', 'ru');
        $payPal->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ru');
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
        ];
    }

    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__public__get_payments');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

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
}
