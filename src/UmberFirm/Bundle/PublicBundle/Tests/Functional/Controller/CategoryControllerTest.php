<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategoryControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller
 */
class CategoryControllerTest extends BaseTestCase
{
    /**
     * @var Shop[]|Category[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('Shop Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $category = new Category();
        $category->setTitle('Їжа', 'ua');
        $category->setTitle('Еда', 'ru');
        $category->setTitle('Comida', 'es');
        $category->setTitle('Food', 'en');
        $category->setShop($shop);
        $category->mergeNewTranslations();
        static::getObjectManager()->persist($category);

        static::getObjectManager()->flush();

        self::$entities['Shop'] = $shop;
        self::$entities['Category:Food'] = $category;
    }

    public function testCategoryList()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_categories'
        );

        $token = ['shop' => static::$entities['Shop']->getApiKey()];

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

    public function testGetCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_category',
            [
                'category' => self::$entities['Category:Food']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['Shop']->getApiKey()];

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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetCategoryNotFoundCategory($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_category',
            [
                'category' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['Shop']->getApiKey()];

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testGetCategoryProducts()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_category_products',
            [
                'category' => self::$entities['Category:Food']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['Shop']->getApiKey()];

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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetCategoryProductsNotFoundCategory($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_category_products',
            [
                'category' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['Shop']->getApiKey()];

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
