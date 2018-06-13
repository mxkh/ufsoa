<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
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
class ContactControllerTest extends BaseTestCase
{
    /**
     * @var object
     */
    public static $postResponse;

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

        $manager = static::getObjectManager();

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'en');
        $iconSupplier->setDescription('description icon', 'en');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();
        $manager->persist($iconSupplier);

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
        $storeOcean->setIsActive(true);
        $storeOcean->setSupplier($iconSupplier);
        $manager->persist($storeOcean);

        // ContactEnum
        $contactEnum = new ContactEnum();
        $contactEnum->setValue('телефон', 'ua');
        $contactEnum->setValue('телефон', 'ru');
        $contactEnum->setValue('phone', 'en');
        $manager->persist($contactEnum);

        // Contact
        $phoneHelenMarlenOcean = new Contact();
        $phoneHelenMarlenOcean->setValue('+38 044 247 70 78');
        $phoneHelenMarlenOcean->addStore($storeOcean);
        $phoneHelenMarlenOcean->setContactEnum($contactEnum);
        $manager->persist($phoneHelenMarlenOcean);

        $manager->flush();

        self::$entities = [
            'store' => $storeOcean,
            'contact' => $phoneHelenMarlenOcean,
            'contactEnum' => $contactEnum,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'value' => 'test@test.test',
            'stores' => [self::$entities['store']->getId()->toString()],
            'contactEnum' => self::$entities['contactEnum']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Test Get Action for viewing list of Contacts
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__shop__get_contacts');

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
            'umberfirm__shop__get_contact',
            [
                'contact' => static::$entities['contact']->getId()->toString(),
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
     * Try to get specified shop by wrong id
     */
    public function testGetNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_contact',
            [
                'contact' => Uuid::NIL,
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

    public function testGetWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_contact',
            [
                'contact' => 'bad-uuid-format',
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
        $this->payload['value'] = 'test@test.test';
        $this->payload['contactEnum'] = self::$entities['contactEnum']->getId()->toString();
        $this->payload['stores'] = [
            self::$entities['store']->getId()->toString(),
        ];

        $uri = $this->router->generate('umberfirm__shop__post_contact');
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

    /**
     * Test create action with invalid body params
     */
    public function testPostActionWithInvalidBodyParams()
    {
        $uri = $this->router->generate('umberfirm__shop__post_contact');

        //with empty params
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

    /**
     * Try to update store data successfully
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_contact',
            [
                'contact' => self::$entities['contact']->getId()->toString(),
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

        $response = $this->client->getResponse();
        $contact = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals('test@test.test', $contact->value);
    }

    public function testPutWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_contact',
            [
                'contact' => 'bad-uuid-format',
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
            'umberfirm__shop__put_contact',
            [
                'contact' => self::$entities['contact']->getId()->toString(),
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

    public function testPutAttributeNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_contact',
            [
                'contact' => Uuid::NIL,
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

    /**
     * Try to delete specified store
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_contact',
            [
                'contact' => static::$entities['contact']->getId()->toString(),
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
            'umberfirm__shop__get_contacts',
            [],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteWithNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_contact',
            [
                'contact' => Uuid::NIL,
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

    public function testDeleteWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_contact',
            [
                'contact' => 'bad-uuid-format',
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

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
