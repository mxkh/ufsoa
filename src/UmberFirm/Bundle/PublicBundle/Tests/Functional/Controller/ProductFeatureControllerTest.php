<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ProductFeatureControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class ProductFeatureControllerTest extends BaseTestCase
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

        $manager = static::getObjectManager();

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($hmShop);

        $poshShop = new Shop();
        $poshShop->setName('POSH');
        $poshShop->setApiKey('11111111111111111111111111111111');
        $manager->persist($poshShop);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('10 Crosby Derek Lam');
        $manufacturer->setAddress(
            'Derek Lam Online Customer Service 3040 East Ana Street Rancho Dominguez CA 90221',
            'ua'
        );
        $manufacturer->setWebsite('www.dereklam.com');
        $manufacturer->mergeNewTranslations();
        $manager->persist($manufacturer);

        $product = new Product();
        $product
            ->setManufacturer($manufacturer)
            ->setName('product1', 'ua')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product);

        $product2 = new Product();
        $product2
            ->setManufacturer($manufacturer)
            ->setName('product2', 'ua')
            ->setShop($poshShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        $styles = new Feature();
        $styles
            ->setName('Стилі', 'ua')
            ->mergeNewTranslations();
        $manager->persist($styles);

        $wool = new FeatureValue();
        $wool->setFeature($styles)
            ->setValue('Шерсть', 'ua')
            ->mergeNewTranslations();
        $manager->persist($wool);

        $productFeature = new ProductFeature();
        $productFeature->setProduct($product)
            ->setFeature($styles)
            ->addProductFeatureValue($wool);
        $manager->persist($productFeature);

        $manager->flush();

        self::$entities = [
            'Product:1' => $product,
            'Product:2' => $product2,
            'HMShop' => $hmShop
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_product_features',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['HMShop']->getApiKey()];

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

    public function testCgetActionNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_product_features',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['HMShop']->getApiKey()];

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
    public function testCgetActionNotFoundUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_product_features',
            [
                'product' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['HMShop']->getApiKey()];

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
