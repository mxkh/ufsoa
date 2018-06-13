<?php

namespace UmberFirm\Bundle\SupplierBundle\Tests\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use SimpleXMLElement;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;

/**
 * Class ImportProductTestFixtures
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Fixtures
 */
trait ImportProductTestFixtures
{
    /**
     * @var ObjectManager
     */
    private static $manager;

    public static function loadFixtures()
    {
        static::$manager = static::getObjectManager();

        self::createManufacturer();
        self::createSupplier();
        self::createSore();
        self::createShop();
        self::createFeatureValues();
        self::createColorAttributes();
        self::createSizeAttributes();

        static::$manager->flush();
    }

    /**
     * @return SimpleXMLElement
     */
    public function transformDataToXml()
    {
        static::$entities['article1'] = 'product2016/11';
        static::$entities['article2'] = 'product2016/12';
        static::$entities['article3'] = 'product2016/10';

        $fileContent = $this->getFileContent('importExampleFormatV1.xml');

        $body = new SimpleXMLElement($fileContent);
        $body->products->product[0]->article = static::$entities['article1'];
        $body->products->product[0]->brand_id = static::$entities['gucci']->getId()->toString();
        $body->products->product[0]->color_id = static::$entities['colorBlue']->getId()->toString();
        $body->products->product[0]->gender = static::$entities['man']->getId()->toString();
        $body->offers->offer[0]->size_id = static::$entities['LSize']->getId()->toString();
        $body->offers->offer[0]->store_id = static::$entities['storeOcean']->getId()->toString();

        $body->products->product[1]->article = static::$entities['article2'];
        $body->products->product[1]->brand_id = static::$entities['nike']->getId()->toString();
        $body->products->product[1]->color_id = static::$entities['colorGreen']->getId()->toString();
        $body->products->product[1]->gender = static::$entities['man']->getId()->toString();
        $body->offers->offer[1]->size_id = static::$entities['MSize']->getId()->toString();
        $body->offers->offer[1]->store_id = static::$entities['storeOcean']->getId()->toString();

        $body->products->product[2]->article = static::$entities['article3'];
        $body->products->product[2]->brand_id = static::$entities['nike']->getId()->toString();
        $body->products->product[2]->color_id = static::$entities['colorGreen']->getId()->toString();
        $body->products->product[2]->gender = static::$entities['woman']->getId()->toString();
        $body->offers->offer[2]->size_id = static::$entities['LSize']->getId()->toString();
        $body->offers->offer[2]->store_id = static::$entities['storeOcean']->getId()->toString();
        $body->offers->offer[3]->size_id = static::$entities['XLSize']->getId()->toString();
        $body->offers->offer[3]->store_id = static::$entities['storeMandarin']->getId()->toString();

        return $body;
    }

    /**
     * @return SimpleXMLElement
     */
    public function transformXmlToSupplierStoreData()
    {
        $body = $this->transformDataToXml();
        $body->offers->offer[0]->store_id = 'storeMandarin';
        $body->offers->offer[1]->store_id = 'storeMandarin';
        $body->offers->offer[2]->store_id = 'storeOcean';
        $body->offers->offer[3]->store_id = 'storeMandarin';

        return $body;
    }

    /**
     * @return SimpleXMLElement
     */
    public function transformXmlToSupplierManufacturerData()
    {
        $body = $this->transformDataToXml();
        $body->products->product[0]->brand_id = 'Gucci';
        $body->products->product[1]->brand_id = 'Nike';
        $body->products->product[2]->brand_id = 'Gucci';

        return $body;
    }

    public function removeOneVariant(SimpleXMLElement $xml)
    {
        unset($xml->offers->offer[3]);

        return $xml;
    }

    /**
     * @return SimpleXMLElement
     */
    public function transformXmlToSupplierFeatureValueData()
    {
        $body = $this->transformDataToXml();
        $body->products->product[0]->gender = 'Man';
        $body->products->product[1]->gender = 'Woman';
        $body->products->product[2]->gender = 'Man';

        return $body;
    }

    /**
     * @return SimpleXMLElement
     */
    public function transformXmlToSupplierAttributesData()
    {
        $body = $this->transformDataToXml();
        $body->products->product[0]->color_id = 'blue';
        $body->offers->offer[0]->size_id = 'L';

        $body->products->product[1]->color_id = 'green';
        $body->offers->offer[1]->size_id = 'M';

        $body->products->product[2]->color_id = 'green';
        $body->offers->offer[2]->size_id = 'L';
        $body->offers->offer[3]->size_id = 'XL';

        return $body;
    }

    public static function createStoreMapping()
    {
        /* @var ObjectManager $manager */
        $manager = static::getObjectManager();

        $iconSupplier = $manager->find(Supplier::class, static::$entities['iconSupplier']->getId()->toString());
        $storeMandarin = $manager->find(Store::class, static::$entities['storeMandarin']->getId()->toString());
        $storeOcean = $manager->find(Store::class, static::$entities['storeOcean']->getId()->toString());

        $storeMandarinMapping = new SupplierStoreMapping();
        $storeMandarinMapping->setSupplier($iconSupplier);
        $storeMandarinMapping->setStore($storeMandarin);
        $storeMandarinMapping->setSupplierStore('storeMandarin');

        $storeOceanMapping = new SupplierStoreMapping();
        $storeOceanMapping->setSupplier($iconSupplier);
        $storeOceanMapping->setStore($storeOcean);
        $storeOceanMapping->setSupplierStore('storeOcean');

        $manager->persist($storeMandarinMapping);
        $manager->persist($storeOceanMapping);
        $manager->flush();
    }

    public static function createAttributeMapping()
    {
        /* @var ObjectManager $manager */
        $manager = static::getObjectManager();

        $iconSupplier = $manager->find(Supplier::class, static::$entities['iconSupplier']->getId()->toString());
        $MSize = $manager->find(Attribute::class, static::$entities['MSize']->getId()->toString());
        $XLSize = $manager->find(Attribute::class, static::$entities['XLSize']->getId()->toString());
        $LSize = $manager->find(Attribute::class, static::$entities['LSize']->getId()->toString());
        $colorBlue = $manager->find(Attribute::class, static::$entities['colorBlue']->getId()->toString());
        $colorGreen = $manager->find(Attribute::class, static::$entities['colorGreen']->getId()->toString());

        $MSizeMapping = new SupplierAttributeMapping();
        $MSizeMapping->setSupplier($iconSupplier);
        $MSizeMapping->setSupplierAttributeKey('size');
        $MSizeMapping->setSupplierAttributeValue('M');
        $MSizeMapping->setAttribute($MSize);

        $XLSizeMapping = new SupplierAttributeMapping();
        $XLSizeMapping->setSupplier($iconSupplier);
        $XLSizeMapping->setSupplierAttributeKey('size');
        $XLSizeMapping->setSupplierAttributeValue('XL');
        $XLSizeMapping->setAttribute($XLSize);

        $LSizeMapping = new SupplierAttributeMapping();
        $LSizeMapping->setSupplier($iconSupplier);
        $LSizeMapping->setSupplierAttributeKey('size');
        $LSizeMapping->setSupplierAttributeValue('L');
        $LSizeMapping->setAttribute($LSize);

        $colorBlueMapping = new SupplierAttributeMapping();
        $colorBlueMapping->setSupplier($iconSupplier);
        $colorBlueMapping->setSupplierAttributeKey('color');
        $colorBlueMapping->setSupplierAttributeValue('blue');
        $colorBlueMapping->setAttribute($colorBlue);

        $colorGreenMapping = new SupplierAttributeMapping();
        $colorGreenMapping->setSupplier($iconSupplier);
        $colorGreenMapping->setSupplierAttributeKey('color');
        $colorGreenMapping->setSupplierAttributeValue('green');
        $colorGreenMapping->setAttribute($colorGreen);

        $manager->persist($MSizeMapping);
        $manager->persist($XLSizeMapping);
        $manager->persist($LSizeMapping);
        $manager->persist($colorBlueMapping);
        $manager->persist($colorGreenMapping);
        $manager->flush();
    }

    public static function createFeatureValueMapping()
    {
        /* @var ObjectManager $manager */
        $manager = static::getObjectManager();

        $iconSupplier = $manager->getRepository(Supplier::class)->find(
            static::$entities['iconSupplier']->getId()->toString()
        );
        $man = $manager->getRepository(FeatureValue::class)->find(static::$entities['man']->getId()->toString());
        $woman = $manager->getRepository(FeatureValue::class)->find(static::$entities['woman']->getId()->toString());

        $manMapping = new SupplierFeatureMapping();
        $manMapping->setSupplier($iconSupplier);
        $manMapping->setFeatureValue($man);
        $manMapping->setSupplierFeatureKey('gender');
        $manMapping->setSupplierFeatureValue('Man');

        $womanMapping = new SupplierFeatureMapping();
        $womanMapping->setSupplier($iconSupplier);
        $womanMapping->setFeatureValue($woman);
        $womanMapping->setSupplierFeatureKey('gender');
        $womanMapping->setSupplierFeatureValue('Woman');

        $manager->persist($manMapping);
        $manager->persist($womanMapping);

        $manager->flush();
    }

    public static function createManufacturerMapping()
    {
        /* @var ObjectManager $manager */
        $manager = static::getObjectManager();

        $iconSupplier = $manager->getRepository(Supplier::class)->find(
            static::$entities['iconSupplier']->getId()->toString()
        );
        $nike = $manager->getRepository(Manufacturer::class)->find(static::$entities['nike']->getId()->toString());
        $gucci = $manager->getRepository(Manufacturer::class)->find(static::$entities['gucci']->getId()->toString());

        $nikeMapping = new SupplierManufacturerMapping();
        $nikeMapping->setSupplier($iconSupplier);
        $nikeMapping->setManufacturer($nike);
        $nikeMapping->setSupplierManufacturer('Nike');

        $gucciMapping = new SupplierManufacturerMapping();
        $gucciMapping->setSupplier($iconSupplier);
        $gucciMapping->setManufacturer($gucci);
        $gucciMapping->setSupplierManufacturer('Gucci');

        $manager->persist($gucciMapping);
        $manager->persist($nikeMapping);
        $manager->flush();
    }

    private static function createManufacturer()
    {
        $gucci = new Manufacturer();
        $gucci->setName('Gucci');
        $gucci->setAddress('Via Alessandro Manzoni, 43, 20121 Milano, Italy', 'ua');
        $gucci->setWebsite('www.43cycles.com');
        $gucci->mergeNewTranslations();

        $nike = new Manufacturer();
        $nike->setName('Nike');
        $nike->setAddress('Ukraine', 'ua');
        $nike->setWebsite('www.ukraine.com');
        $nike->mergeNewTranslations();

        static::$manager->persist($gucci);
        static::$manager->persist($nike);

        static::$entities['gucci'] = $gucci;
        static::$entities['nike'] = $nike;
    }

    private static function createShop()
    {
        $hmShop = new Shop();
        $hmShop->setName('HM SHOP');
        $hmShop->setApiKey('00000000000000000000000000000000');

        static::$manager->persist($hmShop);

        static::$entities['hmShop'] = $hmShop;
    }

    private static function createSore()
    {
        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models'.
            ' - both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU,'.
            ' Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setIsActive(true);
        $storeOcean->mergeNewTranslations();

        $storeMandarin = new Store();
        $storeMandarin->setName('Mandarin Store');
        $storeMandarin->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeMandarin->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models'.
            ' - both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU,'.
            ' Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeMandarin->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeMandarin->setIsActive(true);
        $storeMandarin->mergeNewTranslations();

        static::$manager->persist($storeOcean);
        static::$manager->persist($storeMandarin);

        static::$entities['storeOcean'] = $storeOcean;
        static::$entities['storeMandarin'] = $storeMandarin;
    }

    private static function createSupplier()
    {
        static::$entities['supplierNameIcon'] = 'icon';
        static::$entities['supplierNameFlow'] = 'flow';
        static::$entities['supplierPassword'] = '1234567890';
        static::$entities['supplierManufacturerValue'] = 'supplier-manufacturer-id';

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'ua');
        $iconSupplier->setDescription('description icon', 'ua');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername(static::$entities['supplierNameIcon']);
        $iconSupplier->setPassword(password_hash(static::$entities['supplierPassword'], PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();

        $flowSupplier = new Supplier();
        $flowSupplier->setName('flow', 'ua');
        $flowSupplier->setDescription('description flow', 'ua');
        $flowSupplier->setIsActive(true);
        $flowSupplier->setUsername(static::$entities['supplierNameFlow']);
        $flowSupplier->setPassword(password_hash(static::$entities['supplierPassword'], PASSWORD_DEFAULT));
        $flowSupplier->mergeNewTranslations();

        static::$manager->persist($iconSupplier);
        static::$manager->persist($flowSupplier);

        static::$entities['iconSupplier'] = $iconSupplier;
        static::$entities['flowSupplier'] = $flowSupplier;
    }

    private static function createFeatureValues()
    {
        $gender = new Feature();
        $gender->setName('Стать', 'ua');
        $gender->mergeNewTranslations();

        $woman = new FeatureValue();
        $woman->setFeature($gender);
        $woman->setValue('Жінка', 'ua');
        $woman->mergeNewTranslations();

        $man = new FeatureValue();
        $man->setFeature($gender);
        $man->setValue('Чоловік', 'ua');
        $man->mergeNewTranslations();

        static::$manager->persist($gender);
        static::$manager->persist($woman);
        static::$manager->persist($man);

        static::$entities['man'] = $man;
        static::$entities['woman'] = $woman;
    }

    private static function createColorAttributes()
    {
        $color = new AttributeGroupEnum();
        $color->setName('color');

        $colorGroup = new AttributeGroup();
        $colorGroup->setIsColorGroup(true)
            ->setAttributeGroupEnum($color)
            ->setName('Колір', 'ua')
            ->setPublicName('Колір', 'ua')
            ->mergeNewTranslations();

        $colorGreen = new Attribute();
        $colorGreen->setAttributeGroup($colorGroup)
            ->setColor('#00FF00')
            ->setName('Зелений', 'ua')
            ->mergeNewTranslations();

        $colorBlue = new Attribute();
        $colorBlue->setAttributeGroup($colorGroup)
            ->setColor('#0000FF')
            ->setName('Синій', 'ua')
            ->mergeNewTranslations();

        static::$manager->persist($color);
        static::$manager->persist($colorGroup);
        static::$manager->persist($colorGreen);
        static::$manager->persist($colorBlue);

        static::$entities['colorBlue'] = $colorBlue;
        static::$entities['colorGreen'] = $colorGreen;
    }

    private static function createSizeAttributes()
    {
        $size = new AttributeGroupEnum();
        $size->setName('size');

        $sizeGroup = new AttributeGroup();
        $sizeGroup->setIsColorGroup(true)
            ->setAttributeGroupEnum($size)
            ->setName('Розмір', 'ua')
            ->setPublicName('Розмір', 'ua')
            ->mergeNewTranslations();

        $LSize = new Attribute();
        $LSize->setAttributeGroup($sizeGroup)
            ->setName('L', 'ua')
            ->mergeNewTranslations();

        $XLSize = new Attribute();
        $XLSize->setAttributeGroup($sizeGroup)
            ->setName('XL', 'ua')
            ->mergeNewTranslations();

        $MSize = new Attribute();
        $MSize->setAttributeGroup($sizeGroup)
            ->setName('M', 'ua')
            ->mergeNewTranslations();

        static::$manager->persist($size);
        static::$manager->persist($sizeGroup);
        static::$manager->persist($LSize);
        static::$manager->persist($XLSize);
        static::$manager->persist($MSize);

        static::$entities['LSize'] = $LSize;
        static::$entities['XLSize'] = $XLSize;
        static::$entities['MSize'] = $MSize;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private function getFileContent($filename)
    {
        return file_get_contents(__DIR__.'/'.$filename);
    }
}
