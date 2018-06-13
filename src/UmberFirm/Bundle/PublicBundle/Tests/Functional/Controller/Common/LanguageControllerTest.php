<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Common;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class LanguageControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class LanguageControllerTest extends BaseTestCase
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

        $languageUSD = new Language();
        $languageUSD->setCode('USD');
        $languageUSD->setName('US Dollar');
        $manager->persist($languageUSD);

        $languageUAH = new Language();
        $languageUAH->setCode('UAH');
        $languageUAH->setName('Гривня');
        $manager->persist($languageUAH);

        $hmUSD = new ShopLanguage();
        $hmUSD->setShop($HMShop);
        $hmUSD->setLanguage($languageUSD);
        $hmUSD->setIsDefault(true);
        $manager->persist($hmUSD);

        $hmUAH = new ShopLanguage();
        $hmUAH->setShop($HMShop);
        $hmUAH->setLanguage($languageUAH);
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
            'umberfirm__public__get_languages'
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
