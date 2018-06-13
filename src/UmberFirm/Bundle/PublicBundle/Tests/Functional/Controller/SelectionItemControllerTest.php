<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SelectionItemControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class SelectionItemControllerTest extends BaseTestCase
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

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($HMShop);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ua');
        static::getObjectManager()->persist($manufacturer);

        $suppliers = new Supplier();
        $suppliers
            ->setName('Supplier', 'ua')
            ->setIsActive(true)
            ->setUsername('Supplier')
            ->setPassword('Supplier');
        static::getObjectManager()->persist($suppliers);

        $product1 = new Product();
        $product1
            ->setManufacturer($manufacturer)
            ->setName('product1', 'ua')
            ->setShop($HMShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product1);

        $product2 = new Product();
        $product2
            ->setManufacturer($manufacturer)
            ->setName('product2', 'ua')
            ->setShop($HMShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        $selection = new Selection();
        $selection->setIsActive(true);
        $selection->setShop($HMShop);
        $selection->setName('US Dollar', 'en');
        $manager->persist($selection);

        $selectionItem = new SelectionItem();
        $selectionItem->setSelection($selection);
        $selectionItem->setProduct($product1);
        $manager->persist($selectionItem);

        $manager->flush();

        self::$entities = [
            'HMShop' => $HMShop,
            'selection' => $selection
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_selection-items',
            [
                'selection' => self::$entities['selection']->getId()->toString()
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
            'umberfirm__public__get_selection-items',
            [
                'selection' => $uuid,
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
