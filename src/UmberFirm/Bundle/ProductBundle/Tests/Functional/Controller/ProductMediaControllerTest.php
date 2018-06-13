<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;

/**
 * Class ProductMediaController
 *
 * @package Functional\Controller
 */
class ProductMediaControllerTest extends BaseTestCase
{
    /**
     * @var ProductMedia[]|Shop[]|Product[]|Media[]
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
            'product' => self::$entities['product2']->getId()->toString(),
            'shop' => self::$entities['shop']->getId()->toString(),
            'media' => self::$entities['media']->getId()->toString(),
            'translations' => [
                'ru' => [
                    'alt' => 'Куртка',
                ],
                'es' => [
                    'alt' => 'Chaqueta',
                ],
                'en' => [
                    'alt' => 'Jacket',
                ],
            ],
        ];
    }

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
        $shop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $mediaEnum = new MediaEnum();
        $mediaEnum->setName('image');
        static::getObjectManager()->persist($mediaEnum);

        $media = new Media();
        $media->setMediaEnum($mediaEnum);
        $media->setFilename('1.jpg');
        $media->setExtension('jpg');
        $media->setMimeType('image/jpeg');
        static::getObjectManager()->persist($media);

        $manufacturer = new Manufacturer();
        $manufacturer->setWebsite('http://site.com');
        static::getObjectManager()->persist($manufacturer);

        $product = new Product();
        $product->setManufacturer($manufacturer);
        $product->setShop($shop);
        $product->setName('slug', 'ru');
        $product->setOutOfStock(true);
        $product->mergeNewTranslations();
        static::getObjectManager()->persist($product);

        $product2 = new Product();
        $product2->setManufacturer($manufacturer);
        $product2->setShop($shop);
        $product2->setName('slug_2', 'ru');
        $product2->setOutOfStock(true);
        $product2->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        $productMedia = new ProductMedia();
        $productMedia->setProduct($product);
        $productMedia->setShop($shop);
        $productMedia->setMedia($media);
        $productMedia->setAlt('Jacket', 'en');
        $productMedia->setAlt('Chaqueta', 'es');
        static::getObjectManager()->persist($productMedia);

        static::getObjectManager()->flush();
        self::$entities['productMedia'] = $productMedia;
        self::$entities['media'] = $media;
        self::$entities['product'] = $product;
        self::$entities['product2'] = $product2;
        self::$entities['shop'] = $shop;
    }

    public function testCGetProductMedias()
    {
        $uri = $this->router->generate(
            'umberfirm__product__cget_product_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
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

    public function testGetProductMedia()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
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
    public function testGetProductNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_media',
            [
                'product' => $uuid,
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

    public function testPostProductMedia()
    {
        $uri = $this->router->generate('umberfirm__product__post_product_media');

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

    public function testPostProductMediaValidation()
    {
        $uri = $this->router->generate('umberfirm__product__post_product_media');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutProductMedia()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
            ]
        );

        unset($this->payload['product']);
        unset($this->payload['shop']);
        unset($this->payload['media']);
        unset($this->payload['position']);

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

    public function testPutProductMediaValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
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
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutProductMediaPosition()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_media_position',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
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
            json_encode(['position' => 0])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutProductMediaPositionValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_media_position',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
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
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteProductMediaWithInvalidProductId()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_media',
            [
                'product' => self::$entities['product2']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
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

    public function testDeleteProductMedia()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productMedia' => self::$entities['productMedia']->getId()->toString(),
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
            'umberfirm__product__cget_product_media',
            ['product' => self::$entities['product']->getId()->toString()],
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
    public function testDeleteProductMediaNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_media',
            [
                'product' => $uuid,
                'productMedia' => $uuid,
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
