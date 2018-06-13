<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UmberFirm\Bundle\ProductBundle\Validator\Constraints as IsBarCodeExistAssert;

/**
 * Class BulkUpdateCsvData
 *
 * @package UmberFirm\Bundle\ProductBundle\Entity
 */
class BulkUpdateCsvData implements BulkUpdateCsvDataInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $short_description;

    /**
     * @param string $code
     * 
     * @return BulkUpdateCsvData
     */
    public function setCode(string $code): BulkUpdateCsvData
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
    
    /**
     * @param string $name
     * 
     * @return BulkUpdateCsvData
     */
    public function setName(string $name): BulkUpdateCsvData
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * @param string $description
     * 
     * @return BulkUpdateCsvData
     */
    public function setDescription(string $description): BulkUpdateCsvData
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string $short_description
     * 
     * @return BulkUpdateCsvData
     */
    public function setShortDescription(string $short_description): BulkUpdateCsvData
    {
        $this->short_description = $short_description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }
}
