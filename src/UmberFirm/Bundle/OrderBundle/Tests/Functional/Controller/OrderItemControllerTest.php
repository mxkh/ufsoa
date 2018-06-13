<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class OrderItemControllerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller
 */
class OrderItemControllerTest extends BaseTestCase
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
        $order->setQuantity(1);
        $order->setAmount(123.12);
        $order->setNumber('SHOP#0123456789012');
        $order->setShop($shop);
        static::getObjectManager()->persist($order);

        $order2 = new Order();
        $order2->setQuantity(1);
        $order2->setAmount(123.12);
        $order2->setNumber('SHOP#0123456342342');
        $order2->setShop($shop);
        static::getObjectManager()->persist($order2);

        $orderItem = new OrderItem();
        $orderItem->setProductVariant($productVariant1);
        $orderItem->setQuantity(1);
        $orderItem->setPrice(1000.11);
        $orderItem->setOrder($order);
        $orderItem->setAmount(123.12);
        static::getObjectManager()->persist($orderItem);

        static::getObjectManager()->flush();

        self::$entities = [
            'order' => $order,
            'productVariant' => $productVariant1,
            'orderItem' => $orderItem,
            'order2' => $order2
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'amount' => 333.12,
            'price' => 111,
            'productVariant' => self::$entities['productVariant']->getId(),
            'quantity' => 3,
            'order' => self::$entities['order']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of orderItems.
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order_order-items',
            [
                'order' => static::$entities['order']->getId()->toString(),
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
     * Try to get specified orderItem.
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
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

    public function testGetNotFoundOrderAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order_order-item',
            [
                'order' => static::$entities['order2']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
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
    public function testGetOnBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => $uuid,
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
     * Try to create orderItem.
     */
    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__post_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
            ]
        );

        unset($this->payload['order']);
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    /**
     * Try to create orderItem with invalid params.
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__post_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
            ]
        );

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
     * Try to update orderItem.
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
            ]
        );

        unset($this->payload['order']);
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

    public function testPutNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_order_order-item',
            [
                'order' => static::$entities['order2']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
            ]
        );

        unset($this->payload['order']);
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
            'umberfirm__order__put_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => self::$entities['orderItem']->getId()->toString(),
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testOnBadUuidFormatPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => $uuid,
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

    public function testDeleteNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_order_order-item',
            [
                'order' => static::$entities['order2']->getId()->toString(),
                'orderItem' => static::$entities['orderItem']->getId()->toString(),
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

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => static::$entities['orderItem']->getId()->toString(),
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
            'umberfirm__order__get_order_order-items',
            [
                'order' => static::$entities['order']->getId()->toString(),
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
    public function testOnBadUuidFormatDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_order_order-item',
            [
                'order' => static::$entities['order']->getId()->toString(),
                'orderItem' => $uuid,
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
