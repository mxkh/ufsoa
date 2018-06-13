<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopCurrencyControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopCurrencyControllerTest extends BaseTestCase
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

        $currencyUSD = new Currency();
        $currencyUSD->setCode('USD');
        $currencyUSD->setName('US Dollar');

        $currencyUAH = new Currency();
        $currencyUAH->setCode('UAH');
        $currencyUAH->setName('Гривня');

        $currencyRUB = new Currency();
        $currencyRUB->setCode('RUB');
        $currencyRUB->setName('Рубль');

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

        $hmShopUSD = new ShopCurrency();
        $hmShopUSD->setIsDefault(false);
        $hmShopUSD->setCurrency($currencyUSD);
        $hmShopUSD->setShop($HMShop);

        $hmShopUAH = new ShopCurrency();
        $hmShopUAH->setIsDefault(true);
        $hmShopUAH->setCurrency($currencyUAH);
        $hmShopUAH->setShop($HMShop);

        static::getObjectManager()->persist($currencyUSD);
        static::getObjectManager()->persist($currencyUAH);
        static::getObjectManager()->persist($currencyRUB);
        static::getObjectManager()->persist($HMShopGroup);
        static::getObjectManager()->persist($HMShop);
        static::getObjectManager()->persist($POSHShop);
        static::getObjectManager()->persist($hmShopUSD);
        static::getObjectManager()->persist($hmShopUAH);

        static::getObjectManager()->flush();

        self::$entities = [
            'shop' => $HMShop,
            'POSHShop' => $POSHShop,
            'hmShopUSD' => $hmShopUSD,
            'hmShopUAH' => $hmShopUAH,
            'currencyRUB' => $currencyRUB,
        ];
    }

    /**
     * Try to get list of shop currencies
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_currencies',
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
            'umberfirm__shop__get_shop_currencies',
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
     * Try to get list of shop currencies
     */
    public function testGetSpecifiedLanguageAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_currency',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'currency' => self::$entities['hmShopUSD']->getId()->toString(),
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
            'umberfirm__shop__get_shop_currency',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'currency' => $uuid,
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
            'umberfirm__shop__get_shop_currency',
            [
                'shop' => $uuid,
                'currency' => self::$entities['hmShopUSD']->getId()->toString(),
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
            'umberfirm__shop__post_shop_currency',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
            ]
        );

        $this->payload['currency'] = self::$entities['currencyRUB']->getId()->toString();

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
            'umberfirm__shop__post_shop_currency',
            [
                'shop' => static::$entities['POSHShop']->getId()->toString(),
            ]
        );

        $this->payload['currency'] = self::$entities['currencyRUB']->getId()->toString();
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

        $POSHShopRUB = static::getObjectManager()->getRepository(ShopCurrency::class)->findOneBy(
            [
                'shop' => static::$entities['POSHShop']->getId()->toString(),
                'currency' => static::$entities['currencyRUB']->getId()->toString(),
            ]
        );

        $this->assertNotNull($POSHShopRUB);
        $this->assertTrue($POSHShopRUB->getIsDefault());

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostOnUniqueData()
    {
        /* @var ShopCurrency $hmShopUSD */
        $hmShopUSD = static::$entities['hmShopUSD'];

        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_currency',
            [
                'shop' => $hmShopUSD->getShop()->getId()->toString(),
            ]
        );

        $this->payload = ['currency' => $hmShopUSD->getCurrency()->getId()->toString()];

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
            'umberfirm__shop__post_shop_currency',
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
            'umberfirm__shop__post_shop_currency',
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
            'umberfirm__shop__put_shop_currency',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'currency' => static::$entities['hmShopUSD']->getId()->toString(),
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

        $shopCurrency = json_decode($this->client->getResponse()->getContent());

        $currencies = self::getObjectManager()->find(
            Shop::class,
            static::$entities['shop']->getId()->toString()
        )->getCurrencies();

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        foreach ($currencies->toArray() as $currency) {
            /* @var ShopCurrency $currency */
            if ($currency->getId()->toString() === $shopCurrency->id) {
                $this->assertEquals(true, $shopCurrency->is_default);
            } else {
                $this->assertEquals(false, $currency->getIsDefault());
            }
        }
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutSpecifiedSettingOnNotFoundWithShopBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_currency',
            [
                'shop' => $uuid,
                'currency' => static::$entities['hmShopUSD']->getId()->toString(),
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
            'umberfirm__shop__put_shop_currency',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'currency' => $uuid,
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
            'umberfirm__shop__delete_shop_currency',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'currency' => static::$entities['hmShopUSD']->getId()->toString(),
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
            'umberfirm__shop__get_shop_currencies',
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
            'umberfirm__shop__put_shop_currency',
            [
                'shop' => static::$entities['shop']->getId()->toString(),
                'currency' => $uuid,
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
            'umberfirm__shop__put_shop_currency',
            [
                'shop' => $uuid,
                'currency' => self::$entities['hmShopUSD']->getId()->toString(),
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
