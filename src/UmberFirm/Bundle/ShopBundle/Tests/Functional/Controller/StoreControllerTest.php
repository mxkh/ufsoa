<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class StoreControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class StoreControllerTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $babyShop = new Shop();
        $babyShop->setShopGroup($hmGroup)
            ->setName('Baby Helen Marlen');
        $babyShop->setApiKey('11111111111111111111111111111111');
        static::getObjectManager()->persist($babyShop);

        $omeliaSupplier = new Supplier();
        $omeliaSupplier->setName('Omelia', 'en');
        $omeliaSupplier->setDescription('description Omelia', 'en');
        $omeliaSupplier->setIsActive(true);
        $omeliaSupplier->setUsername('supplier');
        $omeliaSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $omeliaSupplier->addShop($hmShop);
        static::getObjectManager()->persist($omeliaSupplier);

        $storeEnum1 = new StoreEnum();
        $storeEnum1->setName('магазин', 'ua');
        $storeEnum1->setName('магазин', 'ru');
        $storeEnum1->setName('store', 'en');
        static::getObjectManager()->persist($storeEnum1);

        $geolocation4 = new Geolocation();
        $geolocation4->setLatitude(50.447057);
        $geolocation4->setLongitude(30.525552);
        static::getObjectManager()->persist($geolocation4);

        $phoneHelenMarlenOcean = new Contact();
        $phoneHelenMarlenOcean->setValue('+38 044 247 70 78');
        static::getObjectManager()->persist($phoneHelenMarlenOcean);

        $socialProfileEnum1 = new SocialProfileEnum();
        $socialProfileEnum1->setName('facebook.com', 'en');
        $socialProfileEnum1->setAlias('фейсбучик', 'ua');
        $socialProfileEnum1->setAlias('фейсбук', 'ru');
        $socialProfileEnum1->setAlias('facebook', 'en');
        static::getObjectManager()->persist($socialProfileEnum1);

        $storeSocialProfile1 = new StoreSocialProfile();
        $storeSocialProfile1->setValue('https://www.facebook.com/OceanPlaza/');
        $storeSocialProfile1->setSocialProfileEnum($socialProfileEnum1);
        static::getObjectManager()->persist($storeSocialProfile1);

        // Store
        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress('Киев, ул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й этаж', 'ru');
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeOcean->setAddress('Kyiv, Antonovycha st., 176, "Ocean Plaza", 1-й floor', 'en');
        $storeOcean->setDescription(
            'Helen Marlen Ocean — магазин демократичной мужской и женской обуви, а также аксессуаров.'.
            ' Тут представлено всё многообразие стилей — от классики до спортивных моделей — как европейских,'.
            ' так и американских брендов, в числе которых: Fabio Rusconi, Marc by Marc Jacobs, Vetiver,'.
            ' Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka,'.
            ' Roberto Festa, Logan, Salar, Rebecca Minkoff и другие.',
            'ru'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models'.
            ' - both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU,'.
            ' Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - магазин демократичною чоловічого та жіночого взуття, а також аксесуарів.'.
            ' Тут представлено все різноманіття стилів - від класики до спортивних моделей - як європейських,'.
            ' так і американських брендів, в числі яких: Fabio Rusconi, Marc by Marc Jacobs, Vetiver,'.
            ' Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka,'.
            ' Roberto Festa, Logan, Salar, Rebecca Minkoff і інші.',
            'en'
        );
        $storeOcean->setSchedule('пн. - вc. с 10:00 до 22:00', 'ru');
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setSchedule('monday - sunday from 10:00 till 22:00', 'en');
        // TODO add relation between Supplier and Store
        $storeOcean->setSupplier($omeliaSupplier);
        $storeOcean->setIsActive(true);
        $storeOcean->setStoreEnum($storeEnum1);
        $storeOcean->addStoreSocialProfile($storeSocialProfile1);
        $storeOcean->setGeolocation($geolocation4);
        $storeOcean->addContact($phoneHelenMarlenOcean);
        $storeOcean->mergeNewTranslations();
        static::getObjectManager()->persist($storeOcean);

        static::getObjectManager()->flush();

        self::$entities['store'] = $storeOcean;
        self::$entities['shop'] = $hmShop;
        self::$entities['shop2'] = $babyShop;
        self::$entities['contact'] = $phoneHelenMarlenOcean;
        self::$entities['storeEnum'] = $storeEnum1;
        self::$entities['geolocation'] = $geolocation4;
        self::$entities['storeSocialProfile'] = $storeSocialProfile1;
        self::$entities['socialProfileEnum'] = $socialProfileEnum1;
        self::$entities['supplier'] = $omeliaSupplier;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return json_encode($this->content);
    }

    /**
     * Test Get Action for viewing list of Stores
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__shop__get_stores');
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
     * Test Get Action for viewing specified shop
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store',
            [
                'store' => static::$entities['store']->getId(),
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
    public function testGetWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store',
            [
                'store' => $uuid,
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

    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store_translations',
            [
                'store' => static::$entities['store']->getId(),
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
    public function testGetTranslationsWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store_translations',
            [
                'store' => $uuid,
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
     * Try to create store successfully
     */
    public function testPostAction()
    {
        $this->setContent(
            [
                'translations' => [
                    'ua' => [
                        'address' => 'вул. Антоновича, д. 175',
                        'description' => 'Найбільший торгово-розважальний центр Києва,'.
                            ' розташований в самому центрі столиці 3 поверхи',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'ru' => [
                        'address' => 'ул. Антоновича, д.175',
                        'description' => 'Крупнейший торгово-развлекательный центр Киева,'.
                            ' расположенный в самом центре столицы 3 этажа',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'en' => [
                        'address' => 'Antonovich str., 175',
                        'description' => 'The largest shopping and entertainment center Kiev,'.
                            ' located in the heart of the capital 3 floors',
                        'schedule' => '10:00 - 22:00',
                    ],
                ],
                'name' => 'Ocean Plaza 1',
                'supplier' => self::$entities['supplier']->getId()->toString(),
                'isActive' => true,
                'storeEnum' => static::$entities['storeEnum']->getId()->toString(),
                'geolocation' => static::$entities['geolocation']->getId()->toString(),
                'contacts' => [static::$entities['contact']->getId()->toString()],
                'storeSocialProfiles' => [static::$entities['storeSocialProfile']->getId()->toString()],
                'shops' => [
                    static::$entities['shop']->getId()->toString(),
                    static::$entities['shop2']->getId()->toString(),
                ],
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__post_store');
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionWithEmptyBodyParams()
    {
        //test with empty body params
        $this->setContent([]);

        $uri = $this->router->generate('umberfirm__shop__post_store');
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Test create action with invalid body params
     */
    public function testPostActionWithInvalidBodyParams()
    {
        //test with invalid body params
        $this->setContent(
            [
                'translations' => [
                    'ua' => [
                        'address' => 'ул. Антоновича, д.175',
                        'description' => 'Найбільший торгово-розважальний центр Києва,'.
                            ' розташований в самому центрі столиці 3 поверхи',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'ru' => [
                        'address' => 'ул. Антоновича, д.175',
                        'description' => 'Крупнейший торгово-развлекательный центр Киева,'.
                            ' расположенный в самом центре столицы 3 этажа',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'en' => [
                        'address' => 'Antonovich str., 175',
                        'description' => 'The largest shopping and entertainment center Kiev,'.
                            ' located in the heart of the capital 3 floors',
                        'schedule' => '10:00 - 22:00',
                    ],
                ],
                'name' => 'Ocean Plaza 1',
                'supplier' => self::$entities['supplier']->getId()->toString(),
                'storeEnum' => 'dsdsf',
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__post_store');
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to update store data successfully
     */
    public function testPutAction()
    {
        $this->setContent(
            [
                'translations' => [
                    'ua' => [
                        'address' => 'ул. Антоновича, д.175',
                        'description' => 'Найбільший торгово-розважальний центр Києва,'.
                            ' розташований в самому центрі столиці 3 поверхи',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'ru' => [
                        'address' => 'ул. Антоновича, д.175',
                        'description' => 'Крупнейший торгово-развлекательный центр Киева,'.
                            ' расположенный в самом центре столицы 3 этажа',
                        'schedule' => '10:00 - 22:00',
                    ],
                    'en' => [
                        'address' => 'Antonovich str., 175',
                        'description' => 'The largest shopping and entertainment center Kiev,'.
                            ' located in the heart of the capital 3 floors',
                        'schedule' => '10:00 - 22:00',
                    ],
                ],
                'name' => 'Ocean Plaza 2',
                'supplier' => static::$entities['supplier']->getId()->toString(),
                'isActive' => false,
                'storeEnum' => static::$entities['storeEnum']->getId()->toString(),
                'geolocation' => static::$entities['geolocation']->getId()->toString(),
                'contacts' => [static::$entities['contact']->getId()->toString()],
                'storeSocialProfiles' => [static::$entities['storeSocialProfile']->getId()->toString()],
                'shops' => [static::$entities['shop']->getId()->toString()],
            ]
        );

        $uri = $this->router->generate(
            'umberfirm__shop__put_store',
            [
                'store' => static::$entities['store']->getId()->toString(),
            ]
        );
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_store',
            [
                'store' => $uuid,
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

    public function testPutAttributeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_store',
            [
                'store' => self::$entities['store']->getId(),
            ]
        );
        $this->payload['attributeGroup'] = Uuid::NIL;
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode(['stores' => [213131321]])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to delete specified store
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_store',
            [
                'store' => static::$entities['store']->getId(),
            ]
        );
        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__shop__get_stores',
            [],
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
    public function testDeleteWithBadUuidFormatAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_store',
            [
                'store' => $uuid,
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
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
