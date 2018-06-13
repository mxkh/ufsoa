<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Services\Format;

use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatFactory;
use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatInterface;

/**
 * Class FormatFactoryTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Services\Format
 */
class FormatFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormatFactory */
    private $formatFactory;

    /** @var string */
    private $data;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->data = file_get_contents(__DIR__.'/../../../Fixtures/importExampleFormatV1.xml');
        $this->formatFactory = new FormatFactory();
    }

    /**
     * @dataProvider invalidVersionDataProvider
     *
     * @param string $version
     */
    public function testCreateSuccess($version)
    {
        $this->assertInstanceOf(
            FormatInterface::class,
            $this->formatFactory->create($this->data, $version)
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateInvalidVersion()
    {
        $this->formatFactory->create($this->data, 'invalid-version');
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateDataTypeError()
    {
        $this->formatFactory->create(new \stdClass(), 'invalid-version');
    }

    /**
     * @expectedException \TypeError
     */
    public function testCreateVersionTypeError()
    {
        $this->formatFactory->create(new \stdClass(), null);
    }

    /**
     * @return array
     */
    public function invalidVersionDataProvider()
    {
        return [
            ['v1'], //bad format
        ];
    }
}
