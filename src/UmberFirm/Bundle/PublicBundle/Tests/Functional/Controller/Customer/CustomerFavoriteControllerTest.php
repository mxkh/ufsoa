<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use FOS\ElasticaBundle\Elastica\Client;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite\FavoriteManagerInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use Elastica\Response as ElasticaResponse;

/**
 * Class CustomerFavoriteControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
 */
class CustomerFavoriteControllerTest extends BaseTestCase
{
    const FIXTURES_FILE = __DIR__.'/../../Fixtures/catalog_object_list.json';

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $elasticaClient;

    /**
     * @var array|Shop[]|Customer[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->elasticaClient = $this->getMockElastica();

        $favoriteManager = $this->getMockBuilder(FavoriteManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->getContainer()->set(
            'umberfirm.public.component.customer.manager.favorite_manager',
            $favoriteManager
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manager = static::getObjectManager();

        $hmShop = new Shop();
        $hmShop
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($hmShop);

        $customer = new Customer();
        $customer->setPhone('380503214567');
        $customer->setShop($hmShop);
        $customer->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $customer->setIsConfirmed(true);
        $manager->persist($customer);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ua');
        $manager->persist($manufacturer);

        $product = new Product();
        $product
            ->setManufacturer($manufacturer)
            ->setName('product1', 'ua')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        $manager->persist($product);

        $manager->flush();

        self::$entities = [
            'shop' => $hmShop,
            'customer' => $customer,
            'product' => $product,
        ];
    }

    public function testCgetAction()
    {
        $fixtures = file_get_contents(self::FIXTURES_FILE);

        $this->elasticaClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(
                new ElasticaResponse($fixtures)
            );

        $uri = $this->router->generate('umberfirm__public__get_favorites');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
        ];

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

    public function testPostFindAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_favorite_find');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
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
            json_encode(['products' => ['1', '2', '3']])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_favorite',
            [
                'product' => static::$entities['product']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
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
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate('umberfirm__public__delete_favorite');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => static::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['products' => ['1', '2', '3']])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }
}
