<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Common;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CurrencyControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class CurrencyControllerTest extends BaseTestCase
{
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

        $manager = static::getObjectManager();

        $HMShop = new Shop();
        $HMShop->setName('HELEN-MARLEN.COM');
        $HMShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($HMShop);

        $currencyUSD = new Currency();
        $currencyUSD->setCode('USD');
        $currencyUSD->setName('US Dollar');
        $manager->persist($currencyUSD);

        $currencyUAH = new Currency();
        $currencyUAH->setCode('UAH');
        $currencyUAH->setName('Гривня');
        $manager->persist($currencyUAH);

        $hmUSD = new ShopCurrency();
        $hmUSD->setShop($HMShop);
        $hmUSD->setCurrency($currencyUSD);
        $hmUSD->setIsDefault(true);
        $manager->persist($hmUSD);

        $hmUAH = new ShopCurrency();
        $hmUAH->setShop($HMShop);
        $hmUAH->setCurrency($currencyUAH);
        $hmUAH->setIsDefault(false);
        $manager->persist($hmUAH);

        $manager->flush();

        self::$entities = [
            'HMShop' => $HMShop
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_currencies'
        );

        $token = ['shop' => static::$entities['HMShop']->getApiKey()];

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
}
