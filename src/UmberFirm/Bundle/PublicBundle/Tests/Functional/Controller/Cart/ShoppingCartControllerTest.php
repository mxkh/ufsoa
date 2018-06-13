<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Cart;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShoppingCartControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class ShoppingCartControllerTest extends BaseTestCase
{
    /**
     * @var object
     */
    public static $postResponse;

    /**
     * @var array|ShoppingCart[]|Shop[]
     */
    public static $entities;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'quantity' => 1,
            'amount' => 100.10,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('POSH');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $shopHM = new Shop();
        $shopHM->setName('HM');
        $shopHM->setApiKey('11111111111111111111111111111111');
        static::getObjectManager()->persist($shopHM);

        $customer = new Customer();
        $customer->setEmail('test@gmail.com');
        $customer->setPhone('380951234567');
        $customer->setShop($shop);
        static::getObjectManager()->persist($customer);

        $shoppingCart = new ShoppingCart();
        $shoppingCart->setCustomer($customer);
        $shoppingCart->setShop($shop);
        $shoppingCart->setAmount(123.123);
        $shoppingCart->setQuantity(1);
        static::getObjectManager()->persist($shoppingCart);

        static::getObjectManager()->flush();

        self::$entities = [
            'shoppingCart' => $shoppingCart,
            'shop' => $shop,
            'shop:HM' => $shopHM,
            'customer' => $customer,
        ];
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_cart',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
            ]
        );

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

    public function testGetActionOnNotRelative()
    {
        $this->markTestSkipped('add relative validation');

        $uri = $this->router->generate(
            'umberfirm__public__get_cart',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['shop:HM']->getApiKey()];

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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetActionOnNotFoundShop($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_cart',
            [
                'shoppingCart' => $uuid,
            ]
        );

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostActionWithEmptyData()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_cart',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_cart',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
        print_r($this->client->getResponse()->getContent());
    }

    public function testPutActionWithEmptyData()
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_cart',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutActionWithWrongUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_cart',
            [
                'shoppingCart' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_cart',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
            ]
        );

        $this->payload['amount'] = 100000.23;
        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $object = json_decode($this->client->getResponse()->getContent());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $this->assertEquals($object->amount, $this->payload['amount']);
    }
}
