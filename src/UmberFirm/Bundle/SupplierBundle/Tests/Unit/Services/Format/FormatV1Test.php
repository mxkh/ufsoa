<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Services\Format;

use UmberFirm\Bundle\SupplierBundle\Services\Format\FormatV1;

/**
 * Class FormatV1Test
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Services\Format
 */
class FormatV1Test extends \PHPUnit_Framework_TestCase
{
    public function testSupplierFormat()
    {
        $data = file_get_contents(__DIR__.'/../../../Fixtures/importExampleFormatV1.xml');
        $formatV1 = new FormatV1($data);
        $this->assertInternalType('array', $formatV1->supplierFormat());
    }

    public function testIsValidSuccess()
    {
        $data = file_get_contents(__DIR__.'/../../../Fixtures/importExampleFormatV1.xml');
        $formatV1 = new FormatV1($data);
        $this->assertTrue($formatV1->isValid());
    }

    public function testIsValidFailed()
    {
        $data = file_get_contents(__DIR__.'/../../../Fixtures/ImportNotCorrectFormatV1.xml');
        $formatV1 = new FormatV1($data);
        $this->assertFalse($formatV1->isValid());
    }

    public function testBuildProductStructure()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    public function testBuildVariantStructure()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    public function testBuildDepartmentStructure()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function validateTransformedData(array $data)
    {
        foreach ($data as $item) {
            $result = array_diff_key($item, $this->transformedItemData());
            $this->assertTrue(empty($result));

            foreach ($this->transformedItemData() as $fieldName => $fileType) {
                $this->assertInternalType($fileType, $item[$fieldName]);
            }

            $this->validateTransformedFeatureData($item['features']);
            $this->validateTransformedVariantsData($item['variants']);
        }

        return true;
    }

    /**
     * @param array $features
     */
    private function validateTransformedFeatureData(array $features)
    {
        foreach ($features as $key => $values) {
            foreach ($values as $i => $value) {
                $this->assertInternalType('integer', $i);
                $this->assertInternalType('string', $value);
            }
        }
    }

    /**
     * @param array $variants
     */
    private function validateTransformedVariantsData(array $variants)
    {
        foreach ($variants as $variant) {
            $result = array_diff_key($variant, $this->transformedItemVariantsData());
            $this->assertTrue(empty($result));

            foreach ($this->transformedItemVariantsData() as $fieldName => $fileType) {
                $this->assertInternalType($fileType, $variant[$fieldName]);
            }

            $this->validateTransformedAttributesData($variant['attributes']);
        }
    }

    /**
     * @param array $attributes
     */
    private function validateTransformedAttributesData(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->assertInternalType('string', $key);
            $this->assertInternalType('string', $value);
        }
    }

    private function transformedItemData()
    {
        return [
            'id' => 'string',
            'article' => 'string',
            'name' => 'string',
            'manufacturer' => 'string',
            'salePrice' => 'float',
            'price' => 'float',
            'description' => 'string',
            'features' => 'array',
            'variants' => 'array',
        ];
    }

    private function transformedItemVariantsData()
    {
        return [
            'hash' => 'string',
            'article' => 'string',
            'price' => 'float',
            'salePrice' => 'float',
            'ean13' => 'string',
            'upc' => 'string',
            'quantity' => 'integer',
            'store' => 'string',
            'attributes' => 'array',
        ];
    }
}
