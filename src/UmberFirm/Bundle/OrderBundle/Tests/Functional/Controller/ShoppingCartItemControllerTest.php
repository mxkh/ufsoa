<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShoppingCartItemControllerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller
 */
class ShoppingCartItemControllerTest extends BaseTestCase
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

        $shoppingCart = new ShoppingCart();
        $shoppingCart->setQuantity(1);
        $shoppingCart->setAmount(123.12);
        $shoppingCart->setShop($shop);
        static::getObjectManager()->persist($shoppingCart);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setQuantity(1);
        $shoppingCart2->setAmount(123.12);
        $shoppingCart2->setShop($shop);
        static::getObjectManager()->persist($shoppingCart2);

        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setQuantity(1);
        $shoppingCart1->setAmount(123.123);
        $shoppingCart1->setShop($shop);
        static::getObjectManager()->persist($shoppingCart1);

        $shoppingCartItem = new ShoppingCartItem();
        $shoppingCartItem->setShoppingCart($shoppingCart);
        $shoppingCartItem->setProductVariant($productVariant1);
        $shoppingCartItem->setQuantity(1);
        $shoppingCartItem->setPrice(1000.11);
        $shoppingCartItem->setAmount(123.123);
        static::getObjectManager()->persist($shoppingCartItem);

        static::getObjectManager()->flush();

        self::$entities = [
            'shoppingCart' => $shoppingCart,
            'shoppingCart1' => $shoppingCart1,
            'productVariant' => $productVariant1,
            'shoppingCartItem' => $shoppingCartItem,
            'shoppingCart2' => $shoppingCart2
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
            'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
            'shoppingCartItem' => self::$entities['shoppingCartItem']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of shoppingCartItems.
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart_items',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
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
     * Try to get specified shoppingCartItem.
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
                'shoppingCartItem' => self::$entities['shoppingCartItem']->getId()->toString(),
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

    public function testGetNotFoundShoppingCartAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart2']->getId()->toString(),
                'shoppingCartItem' => self::$entities['shoppingCartItem']->getId()->toString(),
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
            'umberfirm__order__get_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
                'shoppingCartItem' => $uuid,
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

    public function testGetShoppingCartItemNotFoundShoppingCart()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_shopping-cart_item',
            [
                'shoppingCart' => self::$entities['shoppingCart1']->getId()->toString(),
                'shoppingCartItem' => self::$entities['shoppingCartItem']->getId()->toString(),
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
     * Try to create shoppingCartItem.
     */
    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__post_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
            ]
        );

        unset($this->payload['shoppingCart']);
        unset($this->payload['shoppingCartItem']);
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
     * Try to create shoppingCartItem.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostShoppingCartItemNotFoundShoppingCart($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__post_shopping-cart_item',
            [
                'shoppingCart' => $uuid,
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
     * Try to create shoppingCartItem with invalid params.
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__post_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
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

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_shopping-cart_item',
            [
                'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
                'shoppingCartItem' => static::$entities['shoppingCartItem']->getId()->toString(),
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
            'umberfirm__order__get_shopping-cart_items',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
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
    public function testOnNotFoundShoppingCartDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_shopping-cart_item',
            [
                'shoppingCart' => $uuid,
                'shoppingCartItem' => static::$entities['shoppingCartItem']->getId()->toString(),
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
    public function testOnBadUuidFormatDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__delete_shopping-cart_item',
            [
                'shoppingCart' => static::$entities['shoppingCart']->getId()->toString(),
                'shoppingCartItem' => $uuid,
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
