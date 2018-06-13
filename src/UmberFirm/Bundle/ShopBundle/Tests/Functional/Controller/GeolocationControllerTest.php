<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class StoreControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class GeolocationControllerTest extends BaseTestCase
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

        $omeliaSupplier = new Supplier();
        $omeliaSupplier->setName('Omelia', 'en');
        $omeliaSupplier->setDescription('description Omelia', 'en');
        $omeliaSupplier->setIsActive(true);
        $omeliaSupplier->setUsername('supplier');
        $omeliaSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        static::getObjectManager()->persist($omeliaSupplier);

        // Geolocation
        $geolocation1 = new Geolocation();
        $geolocation1->setLatitude(50.4121237);
        $geolocation1->setLongitude(30.5204166);
        static::getObjectManager()->persist($geolocation1);

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
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models -'.
            ' both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU,'.
            ' Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
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
        $storeOcean->setSupplier($omeliaSupplier);
        $storeOcean->setIsActive(true);
        $storeOcean->setGeolocation($geolocation1);
        static::getObjectManager()->persist($storeOcean);

        static::getObjectManager()->flush();

        self::$entities = [
            'store' => $storeOcean,
            'geolocation' => $geolocation1,
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
        $uri = $this->router->generate('umberfirm__shop__get_geolocations');
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
            'umberfirm__shop__get_geolocation',
            [
                'geolocation' => self::$entities['geolocation']->getId(),
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
            'umberfirm__shop__get_geolocation',
            [
                'geolocation' => Uuid::NIL,
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
            'umberfirm__shop__get_geolocation',
            [
                'geolocation' => 'bad-uuid-format',
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
                'latitude' => 50.407015,
                'longitude' => 30.638845,
                'stores' => [static::$entities['store']->getId()],
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__post_geolocation');

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
    public function testPostActionWithInvalidBodyParams()
    {
        $uri = $this->router->generate('umberfirm__shop__post_geolocation');

        //test with empty body params
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
        $this->setContent(
            [
                'latitude' => 50.407215,
                'longitude' => 30.638245,
                'stores' => [static::$entities['store']->getId()],
            ]
        );

        $uri = $this->router->generate(
            'umberfirm__shop__put_geolocation',
            [
                'geolocation' => static::$entities['geolocation']->getId(),
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

    public function testPutWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_geolocation',
            [
                'geolocation' => 'bad-uuid-format',
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
            'umberfirm__shop__put_geolocation',
            [
                'geolocation' => self::$entities['geolocation']->getId(),
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
            'umberfirm__shop__put_geolocation',
            [
                'geolocation' => Uuid::NIL,
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
            'umberfirm__shop__delete_geolocation',
            [
                'geolocation' => static::$entities['geolocation']->getId(),
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

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteWithNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_geolocation',
            [
                'geolocation' => Uuid::NIL,
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
            'umberfirm__shop__delete_geolocation',
            [
                'geolocation' => 'bad-uuid-format',
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
