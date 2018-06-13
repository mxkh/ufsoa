<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Format;

/**
 * Class ProductFormatV1
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Parser\Format
 *
 * @see XML format example /var/www/ufsoa-app/data/importExampleFormatV1.xml
 */
final class FormatV1 implements FormatInterface
{
    const VERSION = 'v1';

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    public $helpData;

    /**
     * @var int
     */
    private $countProducts;

    /**
     * @var int
     */
    private $countDepartments;

    /**
     * @var int
     */
    private $countVariants;

    /**
     * FormatV1 constructor.
     *
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = json_decode(json_encode(new \SimpleXMLElement($data)), true);
        $this->helpData['variants'] = [];
        $this->countProducts = count($this->data['products']['product']);
        $this->countDepartments = count($this->data['offers']['offer']);
    }

    /**
     * {@inheritdoc}
     */
    public function supplierFormat(): array
    {
        return [
            'store' => [
                'id',
                'name',
            ],
            'product' => [
                'id',
                'article',
                'brand_id',
                'category_description',
                'color_id',
                'material',
                'gender',
                'brand_color',
                'brand_material',
            ],
            'offer' => [
                'product_id',
                'store_id',
                'size_id',
                'brand_size_id',
                'barcode',
                'price',
                'sale_price',
                'quantity',
                'offer_id',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        $variants = [];
        foreach ($this->supplierFormat() as $key => $value) {
            foreach ($this->data[$key.'s'][$key] as $item) {
                if (true === isset($item['offer_id'])) {
                    $variants[$item['offer_id']] = null;
                }
                $flipped = array_flip($value);

                if (false === empty(array_diff_key($flipped, $item))) {
                    return false;
                }
            }
        }
        $this->countVariants = count($variants);
        unset($variants);

        return true;
    }

    /**
     * @return \Generator
     */
    public function buildProductStructure(): \Generator
    {
        foreach ($this->data['products']['product'] as $item) {
            $productSupplierReference = $this->normalize($item['id']);
            $product = [
                'productSupplierReference' => (string) $productSupplierReference,
                'article' => (string) $this->normalize($item['article']),
                'manufacturer' => (string) $this->normalize($item['brand_id']),
            ];

            $this->helpData['color'][$productSupplierReference] = $this->normalize($item['color_id']);
            $this->helpData['article'][$productSupplierReference] = $this->normalize($item['article']);

            yield $product;
        }
    }

    /**
     * @return \Generator
     */
    public function buildVariantStructure(): \Generator
    {
        foreach ($this->data['offers']['offer'] as $item) {
            $variantSupplierReference = $this->normalize($item['offer_id']);
            $productSupplierReference = $this->normalize($item['product_id']);

            //skip duplicated offer
            if (true === in_array($variantSupplierReference, $this->helpData['variants'])) {
                continue;
            }

            //TODO: cache in redis
            $this->helpData['variants'][] = $variantSupplierReference;

            $variant = [
                'variantSupplierReference' => (string) $variantSupplierReference,
                'productSupplierReference' => (string) $productSupplierReference,
                'color' => (string) $this->helpData['color'][$productSupplierReference],
                'size' => (string) $this->normalize($item['size_id']),
            ];

            yield $variant;
        }
    }

    /**
     * @return \Generator
     */
    public function buildDepartmentStructure(): \Generator
    {
        foreach ($this->data['offers']['offer'] as $item) {
            $variantSupplierReference = $this->normalize($item['offer_id']);
            $productSupplierReference = $this->normalize($item['product_id']);

            $article = true === isset($this->helpData['article'][$productSupplierReference]) ? (string) $this->helpData['article'][$productSupplierReference] : null;

            $department = [
                'variantSupplierReference' => (string) $variantSupplierReference,
                'productSupplierReference' => (string) $productSupplierReference,
                'article' => $article,
                'upc' => (string) $this->normalize($item['barcode']),
                'ean13' => null,
                'price' => (float) $this->normalize($item['price']),
                'salePrice' => (float) $this->normalize($item['sale_price']),
                'quantity' => (int) $this->normalize($item['quantity']),
                'store' => (string) $this->normalize($item['store_id']),
            ];

            yield $department;
        }
    }

    /**
     * @return int
     */
    public function getCountProducts(): int
    {
        return $this->countProducts;
    }

    /**
     * @return int
     */
    public function getCountDepartments(): int
    {
        return $this->countDepartments;
    }

    /**
     * @return int
     */
    public function getCountVariants(): int
    {
        return $this->countVariants;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    private function normalize($data): string
    {
        return true === is_array($data) ? '' : preg_replace('/\s+/', '', trim($data));
    }
}
