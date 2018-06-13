<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopLanguageControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopLanguageControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'isDefault' => true,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $languageUA = new Language();
        $languageUA->setCode('ua');
        $languageUA->setName('Українська');

        $languageEN = new Language();
        $languageEN->setCode('en');
        $languageEN->setName('English');

        $languageRU = new Language();
        $languageRU->setCode('ru');
        $languageRU->setName('Русский');

        $HMShopGroup = new ShopGroup();
        $HMShopGroup->setName('Helen Marlen Group');

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        $HMShop->setShopGroup($HMShopGroup);

        $POSHShop = new Shop();
        $POSHShop->setName('POSH.UA');
        $POSHShop->setApiKey('11111111111111111111111111111111');
        $POSHShop->setShopGroup($HMShopGroup);

        $hmShopUa = new ShopLanguage();
        $hmShopUa->setIsDefault(true);
        $hmShopUa->setLanguage($languageUA);
        $hmShopUa->setShop($HMShop);

        $hmShopEn = new ShopLanguage();
        $hmShopEn->setIsDefault(false);
        $hmShopEn->setLanguage($languageEN);
        $hmShopEn->setShop($HMShop);

        static::getObjectManager()->persist($languageUA);
        static::getObjectManager()->persist($languageEN);
        static::getObjectManager()->persist($languageRU);
        static::getObjectManager()->persist($HMShopGroup);
        static::getObjectManager()->persist($HMShop);
        static::getObjectManager()->persist($POSHShop);
        static::getObjectManager()->persist($hmShopEn);
        static::getObjectManager()->persist($hmShopUa);

        static::getObjectManager()->flush();

        self::$entities = [
            'shop' => $HMShop,
            'POSHShop' => $POSHShop,
            'hmShopEn' => $hmShopEn,
            'hmShopUa' => $hmShopUa,
            'languageRU' => $languageRU,
        ];
    }

    /**
     * Try to get list of shop languages
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_languages',
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
    public function testGetListLanguagesNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_languages',
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
     * Try to get list of shop languages
     */
    public function testGetSpecifiedLanguageAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_language',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'language' => self::$entities['hmShopEn']->getId()->toString(),
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
    public function testGetSpecifiedLanguageOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_language',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'language' => $uuid,
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
    public function testGetSpecifiedLanguageOnNotFoundWithLanguageBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_language',
            [
                'shop' => $uuid,
                'language' => self::$entities['hmShopEn']->getId()->toString(),
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
            'umberfirm__shop__post_shop_language',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        $this->payload['language'] = self::$entities['languageRU']->getId()->toString();

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testCreateFirsShopLanguageWithDefaultFalse()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_language',
            [
                'shop' => static::$entities['POSHShop']->getId()->toString(),
            ]
        );

        $this->payload['language'] = self::$entities['languageRU']->getId()->toString();
        $this->payload['isDefault'] = false;

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $POSHShopRu = static::getObjectManager()->getRepository(ShopLanguage::class)->findOneBy(
            [
                'shop' => static::$entities['POSHShop']->getId()->toString(),
                'language' => static::$entities['languageRU']->getId()->toString(),
            ]
        );

        $this->assertNotNull($POSHShopRu);
        $this->assertTrue($POSHShopRu->getIsDefault());

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostOnUniqueData()
    {
        /* @var ShopLanguage $hmShopEn */
        $hmShopEn = static::$entities['hmShopEn'];

        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_language',
            [
                'shop' => $hmShopEn->getShop()->getId()->toString(),
            ]
        );

        $this->payload = ['language' => $hmShopEn->getLanguage()->getId()->toString()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
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
            'umberfirm__shop__post_shop_language',
            [
                'shop' => $uuid,
            ]
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * Test create action on invalid body params
     */
    public function testInvalidPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_language',
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
            json_encode($this->payload)
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Testing update action
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_language',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'language' => static::$entities['hmShopEn']->getId()->toString(),
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
            json_encode($this->payload)
        );

        $shopLanguage = json_decode($this->client->getResponse()->getContent());

        /* @var ArrayCollection|ShopLanguage[] $languages */
        $languages = self::getObjectManager()->find(
            Shop::class,
            static::$entities['shop']->getId()->toString()
        )->getLanguages();
        foreach ($languages->toArray() as $language) {
            /* @var ShopLanguage $language */
            if ($language->getId()->toString() === $shopLanguage->id) {
                $this->assertEquals(true, $shopLanguage->is_default);
            } else {
                $this->assertEquals(false, $language->getIsDefault());
            }
        }

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
            'umberfirm__shop__put_shop_language',
            [
                'shop' => $uuid,
                'language' => static::$entities['hmShopEn']->getId()->toString(),
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutSpecifiedSettingOnNotFoundWithSettingBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_language',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'language' => $uuid,
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

    /**
     * Testing delete action
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_language',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'language' => static::$entities['hmShopEn']->getId()->toString(),
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
            'umberfirm__shop__get_shop_languages',
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
            'umberfirm__shop__put_shop_language',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'language' => $uuid,
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
            'umberfirm__shop__put_shop_language',
            [
                'shop' => $uuid,
                'language' => self::$entities['hmShopEn']->getId()->toString(),
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
}
