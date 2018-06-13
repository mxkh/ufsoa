<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Entity\StoreEnum;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class StoreControllerTest.
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class StoreSocialProfileControllerTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $content;

    public static $entities;

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

        $storeEnum1 = new StoreEnum();
        $storeEnum1->setName('магазин', 'ua');
        $storeEnum1->setName('магазин', 'ru');
        $storeEnum1->setName('store', 'en');
        static::getObjectManager()->persist($storeEnum1);

        // Store
        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress('Киев, ул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й этаж', 'ru');
        $storeOcean->setAddress('Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх', 'ua');
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
        // TODO add relation between Supplier and Store
        $storeOcean->setSupplier($omeliaSupplier);
        $storeOcean->setIsActive(true);
        $storeOcean->setStoreEnum($storeEnum1);
        $storeOcean->addStoreSocialProfile($storeSocialProfile1);
        static::getObjectManager()->persist($storeOcean);

        static::getObjectManager()->flush();

        self::$entities['store'] = $storeOcean;
        self::$entities['storeSocialProfile'] = $storeSocialProfile1;
        self::$entities['socialProfileEnum'] = $socialProfileEnum1;
        self::$entities['storeEnum'] = $storeEnum1;
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
     * Test Get Action for viewing list of Stores.
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__shop__get_store-social-profiles');
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
     * Test Get Action for viewing specified shop.
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store-social-profile',
            [
                'storeSocialProfile' => static::$entities['storeSocialProfile']->getId(),
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

    public function testGetWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store-social-profile',
            [
                'storeSocialProfile' => 'bad-uuid-format',
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
     * Try to get specified shop by wrong id
     */
    public function testGetNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_store-social-profile',
            [
                'storeSocialProfile' => Uuid::NIL,
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
     * Try to create store successfully.
     */
    public function testPostAction()
    {
        $this->setContent(
            [
                'value' => 'https://www.facebook.com/OceanPlaza/',
                'socialProfileEnum' => static::$entities['socialProfileEnum']->getId(),
                'stores' => [static::$entities['store']->getId()],
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__post_store-social-profile');
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

        $uri = $this->router->generate('umberfirm__shop__post_store-social-profile');
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
     * Test create action with invalid body params.
     */
    public function testPostActionWithInvalidBodyParams()
    {
        //test with invalid body params
        $this->setContent(
            [
                'value' => 121212,
                'socialProfileEnum' => 'test',
                'stores' => 300,
            ]
        );

        $uri = $this->router->generate('umberfirm__shop__post_store-social-profile');
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
     * Try to update store data successfully.
     */
    public function testPutAction()
    {
        $this->setContent(
            [
                'value' => 'https://www.facebook.com/OceanPlaza/',
                'socialProfileEnum' => static::$entities['socialProfileEnum']->getId(),
                'stores' => [static::$entities['store']->getId()],
            ]
        );

        $uri = $this->router->generate(
            'umberfirm__shop__put_store-social-profile',
            [
                'storeSocialProfile' => static::$entities['storeSocialProfile']->getId(),
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

    public function testPutAttributeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_store-social-profile',
            [
                'storeSocialProfile' => self::$entities['storeSocialProfile']->getId(),
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

    public function testPutAttributeNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_store-social-profile',
            [
                'storeSocialProfile' => Uuid::NIL,
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

    public function testPutAttributeNotFoundWithBadUuidFormat()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_store-social-profile',
            [
                'storeSocialProfile' => 'bad-uuid-format',
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
     * Try to delete specified store.
     */
    public function testDeleteAction()
    {
//        get Store social profile
//        $storeSocialProfiles = $this->getStoreSocialProfiles();
//        $storeSocialProfile = $storeSocialProfiles[0];

        $uri = $this->router->generate(
            'umberfirm__shop__delete_store-social-profile',
            [
                'storeSocialProfile' => static::$entities['storeSocialProfile']->getId(),
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
            'umberfirm__shop__get_store-social-profiles',
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
            'umberfirm__shop__delete_store-social-profile',
            [
                'storeSocialProfile' => Uuid::NIL,
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
            'umberfirm__shop__delete_store-social-profile',
            [
                'storeSocialProfile' => 'bad-uuid-format',
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
