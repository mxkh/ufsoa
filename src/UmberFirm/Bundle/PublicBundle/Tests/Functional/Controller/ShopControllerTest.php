<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Controller
 */
class ShopControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|Shop[]|ShopGroup[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        static::getObjectManager()->flush();

        self::$entities['hmGroup'] = $hmGroup;
        self::$entities['hmShop'] = $hmShop;
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_shop',
            [
                'shop' => self::$entities['hmShop']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['hmShop']->getApiKey()];

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
