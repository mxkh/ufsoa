<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use PHPUnit_Framework_MockObject_MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportManagerInterface;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ImportProductController
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class ImportProductControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ImportManagerInterface
     */
    private $importManager;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $file = new UploadedFile(__DIR__.'/../../Fixtures/test.txt', 'text.txt');
        $this->payload = ['file' => $file];

        $this->importManager = $this->getMockBuilder(ImportManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'delete', 'import'])
            ->getMock();
        $this->client->getContainer()->set('umber_firm_supplier.component.manager.import', $this->importManager);
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');

        $application = new MediaEnum();
        $application->setName('application');

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'en');
        $iconSupplier->setDescription('description icon', 'en');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();

        $import = new Import();
        $import->setStatus(Import::STATUS_SUCCESS);
        $import->setFilename('some_file.xml');
        $import->setExtension('xml');
        $import->setVersion('v1');
        $import->setMimeType('application/xml');
        $import->setMediaEnum($application);
        $import->setSupplier($iconSupplier);
        $import->setShop($hmShop);

        static::getObjectManager()->persist($hmShop);
        static::getObjectManager()->persist($application);
        static::getObjectManager()->persist($iconSupplier);
        static::getObjectManager()->persist($import);

        static::getObjectManager()->flush();

        static::$entities = [
            'import' => $import,
            'shop' => $hmShop,
            'iconSupplier' => $iconSupplier,
            'application' => $application,
            'version' => 'v1',
        ];
    }

    public function testGetList()
    {
        $uri = $this->router->generate('umberfirm__supplier__get_import-products');

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

    public function testPostImportAction()
    {
        $this->importManager
            ->expects($this->once())
            ->method('create')
            ->willReturn(sprintf("%s.%s", md5(Uuid::uuid4()->toString()), 'jpg'));

        $this->importManager
            ->expects($this->once())
            ->method('import');

        $uri = $this->router->generate(
            'umberfirm__supplier__post_import-product',
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->payload['file'] = new UploadedFile(
            __DIR__.'/../../Fixtures/importExampleFormatV1.xml',
            'importExampleFormatV1.xml'
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(
                [
                    'version' => static::$entities['version'],
                    'shop' => static::$entities['shop']->getId()->toString(),
                ]
            )
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostImportValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_import-product',
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
            ]
        );
        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(
                [
                    'version' => static::$entities['version'],
                    'shop' => static::$entities['shop']->getId()->toString(),
                ]
            )
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostImportOnNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_import-product',
            [
                'supplier' => $uuid,
            ]
        );
        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(
                [
                    'version' => static::$entities['version'],
                    'shop' => static::$entities['shop']->getId()->toString(),
                ]
            )
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostImportInternalServerError()
    {
        $this->importManager
            ->expects($this->once())
            ->method('create')
            ->willReturn(null);

        $uri = $this->router->generate(
            'umberfirm__supplier__post_import-product',
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->payload['file'] = new UploadedFile(
            __DIR__.'/../../Fixtures/importExampleFormatV1.xml',
            'importExampleFormatV1.xml'
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(
                [
                    'version' => static::$entities['version'],
                    'shop' => static::$entities['shop']->getId()->toString(),
                ]
            )
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_import-product',
            [
                'import' => static::$entities['import']->getId()->toString(),
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

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__supplier__get_import-products',
            [],
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
    public function testDeleteNotFoundAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_import-product',
            [
                'import' => $uuid,
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
