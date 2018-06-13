<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShoppingCartControllerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller
 */
class ShoppingCartControllerTest extends BaseTestCase
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

        $shop = new Shop();
        $shop->setName('POSH');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

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
            'customer' => $customer,
            'shop' => $shop,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'amount' => 1.12,
            'quantity' => 123,
            'customer' => static::$entities['customer']->getId()->toString(),
            'shop' => static::$entities['shop']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of shopping carts.
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__order__get_shopping-carts');

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
     * Try to get specified shopping cart.
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart',
            [
                'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
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
     * Try to get specified shopping cart on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart',
            [
                'shoppingCart' => $uuid,
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
     * Try to create shopping cart.
     *
     * @dataProvider amountStatusDataProvider
     */
    public function testPostAction($amount, $statusCode)
    {
        $uri = $this->router->generate('umberfirm__order__post_shopping-cart');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(array_merge($this->payload, ['amount' => $amount]))
        );

        $this->assertJsonResponse($this->client->getResponse(), $statusCode);
    }

    public function amountStatusDataProvider()
    {
        return [
            [0, Response::HTTP_CREATED],
            [0.00, Response::HTTP_CREATED],
            [0.01, Response::HTTP_CREATED],
            [100, Response::HTTP_CREATED],
            [100.12, Response::HTTP_CREATED],
            [null, Response::HTTP_BAD_REQUEST],
            ['big_amount', Response::HTTP_BAD_REQUEST],
        ];
    }

    /**
     * Try to create shopping cart with invalid params.
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate('umberfirm__order__post_shopping-cart');

        //with empty params
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to update shopping cart.
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_shopping-cart',
            [
                'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * Try to update shopping cart with not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_shopping-cart',
            [
                'shoppingCart' => $uuid,
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

    public function testInvalidParamsPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_shopping-cart',
            [
                'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
            ]
        );

        //with empty params
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_shopping-cart',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
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

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__order__get_shopping-carts',
            [],
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
    public function testNotFoundDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_shopping-cart',
            [
                'shoppingCart' => $uuid,
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
