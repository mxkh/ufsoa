<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CustomerOrderControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
 */
class CustomerOrderControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|CustomerProfile[]|Shop[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manager = static::getObjectManager();

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        $manager->persist($hmGroup);

        $shop = new Shop();
        $shop->setShopGroup($hmGroup)
            ->setName('Helen Marlen')
            ->setApiKey('00000000000000000000000000000000');
        $manager->persist($shop);

        $shop1 = new Shop();
        $shop1->setShopGroup($hmGroup)
            ->setName('Helen Marlen1')
            ->setApiKey('00000000000000000000000000000001');
        $manager->persist($shop1);

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->mergeNewTranslations();
        $manager->persist($customerGroup);

        $gender = new Gender();
        $gender->setName('women', 'en');
        $gender->mergeNewTranslations();
        $manager->persist($gender);

        $customerProfile = new CustomerProfile();
        $customerProfile->setFirstname('John')
            ->setLastname('Doe')
            ->setBirthday(new \DateTime())
            ->setGender($gender);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setProfile($customerProfile)
            ->setShop($shop)
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer);

        $customerProfile1 = new CustomerProfile();
        $customerProfile1->setFirstname('John1')
            ->setLastname('Doe1')
            ->setBirthday(new \DateTime());

        $customer1 = new Customer();
        $customer1
            ->setEmail('test1@gmail.com')
            ->setPhone('+380501234567')
            ->setProfile($customerProfile1)
            ->setShop($shop1)
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer1);

        $order = new Order();
        $order
            ->setShop($shop)
            ->setCustomer($customer)
            ->setQuantity(1)
            ->setAmount(123.12)
            ->setNumber('SHOP#0123456789012');
        $manager->persist($order);

        $order1 = new Order();
        $order1
            ->setShop($shop)
            ->setCustomer($customer1)
            ->setQuantity(1)
            ->setAmount(123.12)
            ->setNumber('SHOP#0123456789012');
        $manager->persist($order1);

        $orderItem = new OrderItem();
        $orderItem
            ->setOrder($order)
            ->setQuantity(2)
            ->setPrice(50.00)
            ->setAmount(100.00);
        $manager->persist($orderItem);

        $orderItem1 = new OrderItem();
        $orderItem1
            ->setOrder($order1)
            ->setQuantity(2)
            ->setPrice(50.00)
            ->setAmount(100.00);
        $manager->persist($orderItem1);

        $manager->flush();

        self::$entities = [
            'shop' => $shop,
            'customer' => $customer,
            'gender' => $gender,
            'customer1' => $customer1,
            'order' => $order,
            'order1' => $order1,
            'orderItem' => $orderItem,
            'orderItem1' => $orderItem1,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'firstname' => self::$entities['customer']->getProfile()->getFirstname(),
            'lastname' => self::$entities['customer']->getProfile()->getLastname(),
            'birthday' => '1969-01-01',
            'gender' => self::$entities['gender']->getId()->toString(),
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate('umberfirm__public__get_customer-orders');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order',
            [
                'order' => self::$entities['order']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    /**
     * @param $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testGetActionOrderNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order',
            [
                'order' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer1']->getToken(),
        ];

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

    public function testGetActionOrderNotMatchCustomer()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order',
            [
                'order' => self::$entities['order']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer1']->getToken(),
        ];

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

    public function testCgetActionOrderItems()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-orders_items',
            [
                'order' => self::$entities['order']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testCgetActionOrderItemsInvalidOrder(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-orders_items',
            [
                'order' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testCgetActionOrderItemsInvalidCustomer()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-orders_items',
            [
                'order' => self::$entities['order']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer1']->getToken(),
        ];

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

    public function testGetActionOrderItem()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order_item',
            [
                'order' => self::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    /**
     * @param string $uuid
     *
     * @dataProvider InvalidUuidDataProvider
     */
    public function testGetActionOrderItemInvalidOrder(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order_item',
            [
                'order' => $uuid,
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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
     * @param string $uuid
     *
     * @dataProvider InvalidUuidDataProvider
     */
    public function testGetActionOrderItemInvalidOrderItem(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order_item',
            [
                'order' => self::$entities['order']->getId()->toString(),
                'orderItem' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testGetActionOrderItemInvalidCustomer()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order_item',
            [
                'order' => self::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer1']->getToken(),
        ];

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

    public function testGetActionOrderItemNotMatch()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-order_item',
            [
                'order' => self::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem1']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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
