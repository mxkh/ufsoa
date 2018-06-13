<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopSettingsController
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopSettingsControllerTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $content;

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

        $email = new SettingsAttribute();
        $email
            ->setName('email')
            ->setType('string');
        static::getObjectManager()->persist($email);

        $phone = new SettingsAttribute();
        $phone
            ->setName('phone')
            ->setType('string');
        static::getObjectManager()->persist($phone);

        $hmShopEmail = new ShopSettings();
        $hmShopEmail->setAttribute($email);
        $hmShopEmail->setValue('hm@mail.com');
        static::getObjectManager()->persist($hmShopEmail);

        $hmShop = new Shop();
        $hmShop
            ->addShopSettings($hmShopEmail)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        static::getObjectManager()->flush();

        self::$entities = [
            'shop' => $hmShop,
            'email' => $email,
            'phone' => $phone,
            'hmShopEmail' => $hmShopEmail,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of shop settings
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_settings',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
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
    public function testGetListSettingsNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_settings',
            [
                'shop' => $uuid,
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
     * Try to get list of shop settings
     */
    public function testGetSpecifiedSettingAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_setting',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'settings' => self::$entities['hmShopEmail']->getId()->toString(),
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
    public function testGetSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_setting',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'settings' => $uuid,
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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_setting',
            [
                'shop' => $uuid,
                'settings' => self::$entities['hmShopEmail']->getId()->toString(),
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
     * Testing create action
     */
    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        $this->setContent(
            ['attribute' => static::$entities['phone']->getId()->toString(), 'value' => '+38(050)000-00-00']
        );

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

    public function testPostOnUniqueData()
    {
        /* @var ShopSettings $setting */
        $setting = static::$entities['hmShopEmail'];

        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_setting',
            [
                'shop' => $setting->getShop()->getId()->toString(),
            ]
        ); //shopDefaultLanguage

        $this->setContent(
            ['attribute' => $setting->getAttribute()->getId()->toString(), 'value' => $setting->getValue()]
        );

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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostSpecifiedSettingOnNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_setting',
            [
                'shop' => $uuid,
            ]
        );

        $this->setContent(
            ['attribute' => static::$entities['email']->getId()->toString(), 'value' => 'some@email.com']
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testInvalidPostActionWithEmptyBody()
    {
        $this->setContent([]);

        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        //Validate on empty body params
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
     * Test create action on invalid body params
     */
    public function testInvalidPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        //Validate on not existing attribute
        $this->setContent(['attribute' => Uuid::NIL, 'value' => 'some@email.com']);
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
     * Testing update action
     */
    public function testPutAction()
    {
        $this->setContent(['attribute' => static::$entities['email']->getId()->toString(), 'value' => 'posh@hm.com']);

        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'settings' => static::$entities['hmShopEmail']->getId()->toString(),
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
    public function testPutSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_setting',
            [
                'shop' => $uuid,
                'settings' => static::$entities['hmShopEmail']->getId()->toString(),
            ]
        );

        $this->setContent(
            ['attribute' => static::$entities['email']->getId()->toString(), 'value' => 'some@email.com']
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
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'settings' => $uuid,
            ]
        );

        $this->setContent(
            ['attribute' => static::$entities['email']->getId()->toString(), 'value' => 'some@email.com']
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
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * Testing delete action
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'settings' => static::$entities['hmShopEmail']->getId()->toString(),
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
            'umberfirm__shop__get_shop_settings',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ],
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
    public function testDeleteSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_setting',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'settings' => $uuid,
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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_setting',
            [
                'shop' => $uuid,
                'settings' => self::$entities['hmShopEmail']->getId()->toString(),
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
     * @return string
     */
    public function getContent()
    {
        return json_encode($this->content);
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
