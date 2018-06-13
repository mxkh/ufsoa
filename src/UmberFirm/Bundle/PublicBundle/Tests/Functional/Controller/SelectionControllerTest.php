<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SelectionControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class SelectionControllerTest extends BaseTestCase
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

        $selection = new Selection();
        $selection->setIsActive(true);
        $selection->setShop($HMShop);
        $selection->setName('US Dollar', 'en');
        $manager->persist($selection);

        $selection2 = new Selection();
        $selection2->setShop($HMShop);
        $selection2->setName('Гривня', 'ru');
        $manager->persist($selection2);

        $manager->flush();

        self::$entities = [
            'HMShop' => $HMShop
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_selections'
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
