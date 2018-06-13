<?php

declare(strict_types=1);

namespace Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ProductVariantMediaControllerTest
 *
 * @package Functional\Controller
 */
class ProductVariantMediaControllerTest extends BaseTestCase
{
    /**
     * @var UuidEntityInterface[]
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
            'productMedia' => self::$entities['productMedia']->getId()->toString(),
            'productVariant' => self::$entities['productVariant']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

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

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $shop = new Shop();
        $shop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $shop2 = new Shop();
        $shop2
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen2');
        $shop2->setApiKey('11111111111111111111111111');
        static::getObjectManager()->persist($shop2);

        $product = new Product();
        $product->setManufacturer($manufacturer);
        $product->setName('slug', 'ua');
        $product->setShop($shop);
        $product->setOutOfStock(true);
        $product->mergeNewTranslations();
        static::getObjectManager()->persist($product);

        $product2 = new Product();
        $product2->setManufacturer($manufacturer);
        $product2->setName('slug_2', 'ua');
        $product2->setShop($shop2);
        $product2->setOutOfStock(true);
        $product2->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        $productVariant = new ProductVariant();
        $productVariant->setProduct($product)
            ->setPrice(1000.00)
            ->setSalePrice(999.99);
        static::getObjectManager()->persist($productVariant);

        $productVariant2 = new ProductVariant();
        $productVariant2->setProduct($product2)
            ->setPrice(1000.00)
            ->setSalePrice(999.99);
        static::getObjectManager()->persist($productVariant2);

        $productMedia = new ProductMedia();
        $productMedia->setProduct($product);
        $productMedia->setShop($shop);
        $productMedia->setMedia($media);
        $productMedia->setAlt('Jacket', 'en');
        $productMedia->setAlt('Chaqueta', 'es');
        static::getObjectManager()->persist($productMedia);

        $productMedia2 = new ProductMedia();
        $productMedia2->setProduct($product);
        $productMedia2->setShop($shop2);
        $productMedia2->setMedia($media);
        $productMedia2->setAlt('Jacket', 'en');
        $productMedia2->setAlt('Chaqueta', 'es');
        static::getObjectManager()->persist($productMedia2);

        $productVariantMedia = new ProductVariantMedia();
        $productVariantMedia->setProductVariant($productVariant2);
        $productVariantMedia->setProductMedia($productMedia2);
        static::getObjectManager()->persist($productVariantMedia);

        static::getObjectManager()->flush();

        self::$entities['productVariantMedia'] = $productVariantMedia;
        self::$entities['productVariant'] = $productVariant;
        self::$entities['productMedia'] = $productMedia;
        self::$entities['media'] = $media;
        self::$entities['product'] = $product;
        self::$entities['product2'] = $product2;
        self::$entities['shop'] = $shop;
    }

    public function testCGetProductVariantMedias()
    {
        $uri = $this->router->generate(
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productVariant' => self::$entities['productVariant']->getId()->toString(),
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

    public function testCGetProductVariantMediasWithWrongProduct()
    {
        $uri = $this->router->generate(
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => self::$entities['product2']->getId()->toString(),
                'productVariant' => self::$entities['productVariant']->getId()->toString(),
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
    public function testCGetProductNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => $uuid,
                'productVariant' => self::$entities['productVariant']->getId()->toString(),
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
    public function testCGetProductVariantNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productVariant' => $uuid,
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

    public function testPostProductVariantMedia()
    {
        $uri = $this->router->generate('umberfirm__product__post_product_variant_media');

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

    public function testPostProductVariantMediaValidation()
    {
        $uri = $this->router->generate('umberfirm__product__post_product_variant_media');

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

    public function testDeleteProductMedia()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productVariant' => self::$entities['productVariant']->getId()->toString(),
                'productVariantMedia' => self::$entities['productVariantMedia']->getId()->toString(),
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
            'umberfirm__product__cget_product_variant_media',
            [
                'product' => self::$entities['product']->getId()->toString(),
                'productVariant' => self::$entities['productVariant']->getId()->toString(),
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
    public function testDeleteProductNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant_media',
            [
                'product' => $uuid,
                'productVariant' => $uuid,
                'productVariantMedia' => $uuid,
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
