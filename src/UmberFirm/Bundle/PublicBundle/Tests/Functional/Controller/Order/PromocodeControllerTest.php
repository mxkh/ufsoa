<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class PromocodeControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order
 */
class PromocodeControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|Shop[]|Customer[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setShop($shop);
        static::getObjectManager()->persist($customer);

        $percent = new PromocodeEnum();
        /** @var string $locale */
        $percent->setCode('percent');
        $percent->setName('Percent', 'ua');
        $percent->setCalculate('%s * (%s / 100)');
        $percent->mergeNewTranslations();
        static::getObjectManager()->persist($percent);

        $percent30 = new Promocode();
        $percent30->setCode('SALE_30');
        $percent30->setValue(30);
        $percent30->setIsReusable(true);
        $percent30->setPromocodeEnum($percent);
        static::getObjectManager()->persist($percent30);

        $percent50 = new Promocode();
        $percent50->setCode('SALE_50');
        $percent50->setValue(30);
        $percent50->setIsReusable(false);
        $percent50->setFinish(new \DateTime('2000-01-01'));
        $percent50->setPromocodeEnum($percent);
        static::getObjectManager()->persist($percent50);

        static::getObjectManager()->flush();

        self::$entities = [
            'percent:30' => $percent30->getCode(),
            'percent:50' => $percent50->getCode(),
            'shop' => $shop,
            'customer' => $customer,
        ];
    }

    public function testVerificationSuccessAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_promocode_verification');

        $token = [
            'shop' => static::$entities['shop']->getApiKey()
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['promocode' => static::$entities['percent:30']])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testVerificationFailureAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_promocode_verification');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['promocode' => static::$entities['percent:50']])
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
    }

    public function testVerificationNotFoundCodeAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_promocode_verification');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['promocode' => 'not-found-code'])
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
    }
}
