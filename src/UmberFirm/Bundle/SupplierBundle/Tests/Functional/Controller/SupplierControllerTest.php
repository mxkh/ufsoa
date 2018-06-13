<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SupplierControllerTest
 *
 * @package UmberFirmManufacturerBundle\Tests\Controller
 */
class SupplierControllerTest extends BaseTestCase
{
    /**
     * @var object
     */
    public static $postResponse;

    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * @var array
     */
    public $put;

    protected function setUp()
    {
        $this->payload = [
            'isActive' => true,
            'username' => 'helen',
            'password' => 'qwerty',
            'translations' => [
                'ua' => [
                    'name' => 'Їжа',
                    'description' => '123',
                ],
                'ru' => [
                    'name' => 'Еда',
                    'description' => '123',
                ],
                'es' => [
                    'name' => 'Comida',
                    'description' => '123',
                ],
                'en' => [
                    'name' => 'Fod',
                    'description' => '123',
                ],
            ],
        ];

        $this->put = [
            'isActive' => true,
            'username' => 'helen',
            'password' => 'qwerty',
            'translations' => [
                'ua' => [
                    'name' => 'Їжа',
                    'description' => '123',
                ],
                'ru' => [
                    'name' => 'Еда',
                    'description' => '123',
                ],
                'es' => [
                    'name' => 'Comida',
                    'description' => '123',
                ],
                'en' => [
                    'name' => 'Food',
                    'description' => '123',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

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
            ' Тут представлено всё многообразие стилей — от классики до спортивных моделей'.
            ' — как европейских, так и американских брендов, в числе которых: Fabio Rusconi,'.
            ' Marc by Marc Jacobs, Vetiver, Eddy Daniele, Crime London, Veja, Stokton,'.
            ' Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar,'.
            ' Rebecca Minkoff и другие.',
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
            'Helen Marlen Ocean - магазин демократичною чоловічого та жіночого взуття,'.
            ' а також аксесуарів. Тут представлено все різноманіття стилів - від класики'.
            ' до спортивних моделей - як європейських, так і американських брендів,'.
            ' в числі яких: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele,'.
            ' Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka,'.
            ' Roberto Festa, Logan, Salar, Rebecca Minkoff і інші.',
            'en'
        );
        $storeOcean->setSchedule('пн. - вc. с 10:00 до 22:00', 'ru');
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setSchedule('monday - sunday from 10:00 till 22:00', 'en');
        $storeOcean->setIsActive(true);
        static::getObjectManager()->persist($storeOcean);

        // Contact
        $phoneHelenMarlenOcean = new Contact();
        $phoneHelenMarlenOcean->setValue('+38 044 247 70 78');
        $phoneHelenMarlenOcean->addStore($storeOcean);
        static::getObjectManager()->persist($phoneHelenMarlenOcean);

        // ContactEnum
        $contactEnum = new ContactEnum();
        $contactEnum->setValue('телефон', 'ua');
        $contactEnum->setValue('телефон', 'ru');
        $contactEnum->setValue('phone', 'en');
        $contactEnum->addContact($phoneHelenMarlenOcean);
        static::getObjectManager()->persist($contactEnum);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ua');
        static::getObjectManager()->persist($manufacturer);

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $babyShop = new Shop();
        $babyShop
            ->setShopGroup($hmGroup)
            ->setName('Baby Helen Marlen');
        $babyShop->setApiKey('11111111111111111111111111111111');
        static::getObjectManager()->persist($babyShop);

        static::getObjectManager()->flush();

        self::$entities = [
            'store' => $storeOcean,
            'contact' => $phoneHelenMarlenOcean,
            'contactEnum' => $contactEnum,
            'shop1' => $hmShop,
            'shop2' => $babyShop,
        ];
    }

    public function testGetSupplierList()
    {
        $uri = $this->router->generate('umberfirm__supplier__get_suppliers');

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
    public function testGetSupplierNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier',
            [
                'supplier' => $uuid,
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

    public function testPostAction()
    {
        $this->payload['shops'] = [
            static::$entities['shop1']->getId()->toString(),
            static::$entities['shop2']->getId()->toString(),
        ];
        $this->payload['stores'] = [
            static::$entities['store']->getId()->toString(),
        ];

        $uri = $this->router->generate('umberfirm__supplier__post_supplier');
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

        self::$postResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier',
            [
                'supplier' => self::$postResponse->id,
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

    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_translations',
            [
                'supplier' => self::$postResponse->id,
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
    public function testGetTranslationsNotFoundAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_translations',
            [
                'supplier' => $uuid,
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

    public function testPostSupplierBadRequest()
    {
        $uri = $this->router->generate('umberfirm__supplier__post_supplier');

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

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier',
            [
                'supplier' => self::$postResponse->id,
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
            json_encode($this->put)
        );

        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertEquals('Food', $putResponse->name);
    }

    public function testPutAttributeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier',
            [
                'supplier' => self::$postResponse->id,
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier',
            [
                'supplier' => $uuid,
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
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier',
            [
                'supplier' => self::$postResponse->id,
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
            'umberfirm__supplier__get_suppliers',
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
    public function testDeleteAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier',
            [
                'supplier' => $uuid,
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
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
