<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller;

use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class OrderControllerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller
 */
class OrderControllerTest extends BaseTestCase
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

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $shop = new Shop();
        $shop->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->mergeNewTranslations();
        static::getObjectManager()->persist($customerGroup);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setCustomerGroup($customerGroup);
        static::getObjectManager()->persist($customer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('10 Crosby Derek Lam');
        $manufacturer->setAddress(
            'Derek Lam Online Customer Service 3040 East Ana Street Rancho Dominguez CA 90221',
            'en'
        );
        $manufacturer->addShop($shop);
        $manufacturer->setWebsite('www.dereklam.com');
        $manufacturer->mergeNewTranslations();
        static::getObjectManager()->persist($manufacturer);

        $product10 = new Product();
        $product10->setManufacturer($manufacturer);
        $product10->setName('noski', 'ua');
        $product10->setOutOfStock(true);
        $product10->setShop($shop);
        $product10->mergeNewTranslations();
        static::getObjectManager()->persist($product10);

        $colorE = new AttributeGroupEnum();
        $colorE->setName('color');
        static::getObjectManager()->persist($colorE);

        $select = new AttributeGroupEnum();
        $select->setName('select');
        static::getObjectManager()->persist($select);

        $size = new AttributeGroup();
        $size->setIsColorGroup(false)
            ->setAttributeGroupEnum($select)
            ->setName('Розмiр', 'ua')
            ->setPublicName('Розмiр', 'ua')
            ->setName('Size', 'en')
            ->setPublicName('Size', 'en')
            ->setName('Tamaño', 'es')
            ->setPublicName('Tamaño', 'es')
            ->setName('Размер', 'ru')
            ->setPublicName('Размер', 'ru')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($size);

        $color = new AttributeGroup();
        $color->setIsColorGroup(true)
            ->setAttributeGroupEnum($colorE)
            ->setName('Колір', 'ua')
            ->setPublicName('Колір', 'ua')
            ->setName('Color', 'en')
            ->setPublicName('Color', 'en')
            ->setName('Color', 'es')
            ->setPublicName('Color', 'es')
            ->setName('Цвет', 'ru')
            ->setPublicName('Цвет', 'ru')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($color);

        $colorRed = new Attribute();
        $colorRed->setAttributeGroup($color)
            ->setColor('#FF0000')
            ->setName('Червоний', 'ua')
            ->setName('Red', 'en')
            ->setName('Rojo', 'es')
            ->setName('Красный', 'ru')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($colorRed);

        $sizeS = new Attribute();
        $sizeS->setAttributeGroup($size)
            ->setName('S', 'ua')
            ->setName('S', 'en')
            ->setName('S', 'es')
            ->setName('S', 'ru')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($sizeS);

        $productVariant1 = new ProductVariant();
        $productVariant1->setProduct($product10)
            ->setPrice(1000.00)
            ->setSalePrice(999.99)
            ->addProductVariantAttribute($sizeS)
            ->addProductVariantAttribute($colorRed);
        static::getObjectManager()->persist($productVariant1);

        $order = new Order();
        $order->setAmount(123.12);
        $order->setShop($shop);
        $order->setCustomer($customer);
        $order->setNumber('HM'.time());
        $order->setQuantity(1);
        static::getObjectManager()->persist($order);

        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProductVariant($productVariant1);
        $orderItem->setQuantity(1);
        $orderItem->setPrice(1000.11);
        $orderItem->setAmount(123.12);
        static::getObjectManager()->persist($orderItem);

        $currencyUAH = new Currency();
        $currencyUAH->setCode('UAH');
        $currencyUAH->setName('Гривня');
        static::getObjectManager()->persist($currencyUAH);

        $shopUAH = new ShopCurrency();
        $shopUAH->setIsDefault(false);
        $shopUAH->setCurrency($currencyUAH);
        $shopUAH->setShop($shop);
        static::getObjectManager()->persist($shopUAH);

        static::getObjectManager()->flush();

        self::$entities = [
            'customer' => $customer,
            'shop' => $shop,
            'order' => $order,
            'orderItem' => $orderItem,
            'shopCurrency' => $shopUAH,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'customer' => self::$entities['customer']->getId()->toString(),
            'shop' => self::$entities['shop']->getId()->toString(),
            'amount' => 1.123,
            'number' => 'HM#1232133132',
            'quantity' => 123,
            'orderItems' => [self::$entities['orderItem']->getId()->toString()],
            'shopCurrency' => self::$entities['shopCurrency']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of orders.
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__order__get_orders');

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
     * Try to get specified order.
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order',
            [
                'order' => self::$entities['order']->getId()->toString(),
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
     * Try to get specified order on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order',
            [
                'order' => $uuid,
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
     * Try to create order.
     *
     * @dataProvider amountStatusDataProvider
     */
    public function testPostAction($amount, $statusCode)
    {
        $uri = $this->router->generate('umberfirm__order__post_order');

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
     * Try to create order with invalid params.
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate('umberfirm__order__post_order');

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
     * Try to update order.
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_order',
            [
                'order' => self::$entities['order']->getId()->toString(),
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
     * Try to update order with not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_order',
            [
                'order' => $uuid,
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
            'umberfirm__order__put_order',
            [
                'order' => self::$entities['order']->getId()->toString(),
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
            'umberfirm__order__delete_order',
            [
                'order' => static::$entities['order']->getId()->toString(),
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
            'umberfirm__order__get_orders',
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
            'umberfirm__order__delete_order',
            [
                'order' => $uuid,
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
