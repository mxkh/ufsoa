<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class AttributeControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class AttributeControllerTest extends BaseTestCase
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

        //HELEN-MARLEN.COM
        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($HMShop);

        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ru'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.
        Here is presented the whole variety of styles - from classics to sports models - both European and
        American brands, including: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele, Crime London, Veja,
        Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ru'
        );
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ru');
        $storeOcean->setIsActive(true);
        $storeOcean->mergeNewTranslations();
        $manager->persist($storeOcean);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ru');
        $manager->persist($manufacturer);

        $supplier = new Supplier();
        $supplier
            ->setName('Supplier', 'ru')
            ->setIsActive(true)
            ->setUsername('Supplier')
            ->setPassword('Supplier');
        $manager->persist($supplier);

        $product = new Product();
        $product->setManufacturer($manufacturer);
        $product->setName('pidjack', 'ru');
        $product->setIsHidden(true);
        $product->setShop($HMShop);
        $product->mergeNewTranslations();
        $manager->persist($product);

        $product2 = new Product();
        $product2->setManufacturer($manufacturer);
        $product2->setName('product2', 'ru');
        $product2->setIsHidden(true);
        $product2->setShop($HMShop);
        $product2->mergeNewTranslations();
        $manager->persist($product2);

        $attributeGroupEnumSelect = new AttributeGroupEnum();
        $attributeGroupEnumSelect->setName('select');
        $manager->persist($attributeGroupEnumSelect);

        $attributeGroupEnumColor = new AttributeGroupEnum();
        $attributeGroupEnumColor->setName('color');
        $manager->persist($attributeGroupEnumColor);

        $attributeGroupSize = new AttributeGroup();
        $attributeGroupSize->setIsColorGroup(false)
            ->setAttributeGroupEnum($attributeGroupEnumSelect)
            ->setName('Розмiр', 'ru')
            ->setPublicName('Розмiр', 'ru')
            ->setName('Size', 'en')
            ->setPublicName('Size', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeGroupSize);

        $attributeGroupColor = new AttributeGroup();
        $attributeGroupColor->setIsColorGroup(true)
            ->setAttributeGroupEnum($attributeGroupEnumColor)
            ->setName('Колір', 'ru')
            ->setPublicName('Колір', 'ru')
            ->setName('Color', 'en')
            ->setPublicName('Color', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeGroupColor);

        $attributeSizeS = new Attribute();
        $attributeSizeS->setAttributeGroup($attributeGroupSize)
            ->setName('S', 'ru')
            ->setName('S', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeSizeS);

        $attributeSizeM = new Attribute();
        $attributeSizeM->setAttributeGroup($attributeGroupSize)
            ->setName('M', 'ru')
            ->setName('M', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeSizeM);

        $attributeColorGreen = new Attribute();
        $attributeColorGreen->setAttributeGroup($attributeGroupColor)
            ->setColor('#00FF00')
            ->setName('Зелений', 'ru')
            ->setName('Green', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeColorGreen);

        $attributeColorBlue = new Attribute();
        $attributeColorBlue->setAttributeGroup($attributeGroupColor)
            ->setColor('#0000FF')
            ->setName('Синій', 'ru')
            ->setName('Blue', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeColorBlue);

        $supplierProductUid = Uuid::uuid1()->toString();

        $productVariant1 = new ProductVariant();
        $productVariant1->setProduct($product)
            ->setPrice(1000.00)
            ->setSalePrice(999.99)
            ->addProductVariantAttribute($attributeSizeS)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant1);

        $productVariant2 = new ProductVariant();
        $productVariant2->setProduct($product)
            ->setPrice(1000.00)
            ->setSalePrice(999.98)
            ->addProductVariantAttribute($attributeSizeM)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant2);

        $productVariant3 = new ProductVariant();
        $productVariant3->setProduct($product2)
            ->setPrice(1000.00)
            ->setSalePrice(999.99)
            ->addProductVariantAttribute($attributeSizeM)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant3);

        $manager->flush();

        self::$entities = [
            'HMShop' => $HMShop,
            'Product:1' => $product,
            'Product:2' => $product2,
            'ProductVariant:1' => $productVariant1,
            'ProductVariant:2' => $productVariant2,
            'ProductVariant:3' => $productVariant3,
            'Attribute:Size:S' => $attributeSizeS,
            'Attribute:Size:M' => $attributeSizeM,
            'Attribute:Color:Green' => $attributeColorGreen,
            'Attribute:Color:Blue' => $attributeColorBlue,
            'supplier' => $supplier,
            'storeOcean' => $storeOcean,
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_product_attributes',
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testCgetActionNotFoundUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_product_attributes',
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
