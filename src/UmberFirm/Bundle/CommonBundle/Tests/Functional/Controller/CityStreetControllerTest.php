<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class CityStreetControllerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller
 */
class CityStreetControllerTest extends BaseTestCase
{
    /**
     * @var Currency[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $e1 = new City();
        $e1->setName('Kiev');
        $e1->setRef('some ref');
        static::getObjectManager()->persist($e1);

        $e2 = new Street();
        $e2->setName('Хрещатик');
        $e2->setRef('some ref');
        $e2->setType('ул.');
        $e2->setCity($e1);
        static::getObjectManager()->persist($e2);
        static::getObjectManager()->flush();

        self::$entities['city'] = $e1;
    }

    public function testList()
    {
        $uri = $this->router->generate(
            'umberfirm__common__search_city_streets_suggestions',
            [
                'city' => self::$entities['city']->getId()->toString(),
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
}
