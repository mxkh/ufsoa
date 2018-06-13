<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class FastOrderControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order
 */
class FastOrderControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|Customer[]|Shop[]|ProductVariant[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->payload = [
            'phone' => self::$entities['customer']->getPhone(),
            'productVariant' => self::$entities['productVariant']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setShop($shop);
        static::getObjectManager()->persist($customer);

        $manufacturer = new Manufacturer();
        $manufacturer->addShop($shop);
        $manufacturer->setWebsite('www.dereklam.com');
        $manufacturer->mergeNewTranslations();
        static::getObjectManager()->persist($manufacturer);

        $product = new Product();
        $product->setManufacturer($manufacturer);
        $product->setName('noski', 'ua');
        $product->setOutOfStock(true);
        $product->setShop($shop);
        $product->mergeNewTranslations();
        static::getObjectManager()->persist($product);

        $productVariant = new ProductVariant();
        $productVariant
            ->setProduct($product)
            ->setPrice(1000.00)
            ->setSalePrice(999.99);
        static::getObjectManager()->persist($productVariant);

        static::getObjectManager()->flush();

        self::$entities = [
            'customer' => $customer,
            'shop' => $shop,
            'productVariant' => $productVariant,
        ];
    }

    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_fast-order');

        $token = [
            'shop' => self::$entities['shop']->getApiKey(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionWithEmpty()
    {
        $uri = $this->router->generate('umberfirm__public__post_fast-order');

        $token = [
            'shop' => self::$entities['shop']->getApiKey(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
