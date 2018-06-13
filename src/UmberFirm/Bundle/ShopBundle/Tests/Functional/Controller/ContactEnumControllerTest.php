<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class StoreControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ContactEnumControllerTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var array
     */
    private static $entities;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'en');
        $iconSupplier->setDescription('description icon', 'en');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();
        static::getObjectManager()->persist($iconSupplier);

        // Store
        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress('Киев, ул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й этаж', 'ru');
        $storeOcean->setAddress('Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх', 'ua');
        $storeOcean->setAddress('Kyiv, Antonovycha st., 176, "Ocean Plaza", 1-й floor', 'en');
        $storeOcean->setDescription(
            'Helen Marlen Ocean — магазин демократичной мужской и женской обуви, а также аксессуаров.'.
            ' Тут представлено всё многообразие стилей — от классики до спортивных моделей — как европейских,'.
            ' так и американских брендов, в числе которых: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele,'.
            ' Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar,'.
            ' Rebecca Minkoff и другие.',
            'ru'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories. Here is presented'.
            ' the whole variety of styles - from classics to sports models - both European and American brands,'.
            ' including: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele, Crime London, Veja, Stokton,'.
            ' Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - магазин демократичною чоловічого та жіночого взуття, а також аксесуарів.'.
            ' Тут представлено все різноманіття стилів - від класики до спортивних моделей - як європейських,'.
            ' так і американських брендів, в числі яких: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele,'.
            ' Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar,'.
            ' Rebecca Minkoff і інші.',
            'en'
        );
        $storeOcean->setSchedule('пн. - вc. с 10:00 до 22:00', 'ru');
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setSchedule('monday - sunday from 10:00 till 22:00', 'en');
        // TODO add relation between Supplier and Store
        $storeOcean->setSupplier($iconSupplier);
        $storeOcean->setIsActive(true);
        static::getObjectManager()->persist($storeOcean);

        // ContactEnum
        $contactEnum = new ContactEnum();
        $contactEnum->setValue('телефон', 'ua');
        $contactEnum->setValue('телефон', 'ru');
        $contactEnum->setValue('phone', 'en');
        static::getObjectManager()->persist($contactEnum);

        // Contact
        $phoneHelenMarlenOcean = new Contact();
        $phoneHelenMarlenOcean->setValue('+38 044 247 70 78');
        $phoneHelenMarlenOcean->addStore($storeOcean);
        $phoneHelenMarlenOcean->setContactEnum($contactEnum);
        static::getObjectManager()->persist($phoneHelenMarlenOcean);

        static::getObjectManager()->flush();

        self::$entities = [
            'store' => $storeOcean,
            'contact' => $phoneHelenMarlenOcean,
            'contactEnum' => $contactEnum,
        ];
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
        $uri = $this->router->generate('umberfirm__shop__get_contact-enums');
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
            'umberfirm__shop__get_contact-enum',
            [
                'contactEnum' => static::$entities['contactEnum']->getId()->toString(),
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
    public function testGetNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_contact-enum',
            [
                'contactEnum' => $uuid,
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
            'umberfirm__shop__get_contact-enum_translations',
            [
                'contactEnum' => static::$entities['contactEnum']->getId()->toString(),
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
    public function testGetTranslationsNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_contact-enum_translations',
            [
                'contactEnum' => $uuid,
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
                        'value' => 'iмейл',
                    ],
                    'ru' => [
                        'value' => 'имейл',
                    ],
                    'en' => [
                        'value' => 'email',
                    ],
                ],
                'contacts' => [static::$entities['contact']->getId()->toString()],
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__get_contact-enums');

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


    /**
     * Test create action with invalid body params
     */
    public function testPostActionWithEmptyBodyParams()
    {
        //test with empty body params
        $this->setContent([]);

        $uri = $this->router->generate('umberfirm__shop__get_contact-enums');

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
                        'value' => 121212,
                    ],
                ],
                'contactEnum' => 'test',
                'stores' => 300,
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__get_contact-enums');

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
                        'value' => 'скайп',
                    ],
                    'ru' => [
                        'value' => 'скайп',
                    ],
                    'en' => [
                        'value' => 'skype',
                    ],
                ],
                'contacts' => [static::$entities['contact']->getId()->toString()],
            ]
        );

        $uri = $this->router->generate(
            'umberfirm__shop__put_contact-enum',
            [
                'contactEnum' => static::$entities['contactEnum']->getId()->toString(),
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
            'umberfirm__shop__put_contact-enum',
            [
                'contactEnum' => $uuid,
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

    public function testPutWithInvalidParamsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_contact-enum',
            [
                'contactEnum' => self::$entities['contactEnum']->getId()->toString(),
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
