<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Component\Updater;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use Symfony\Component\Form\FormBuilder;
use UmberFirm\Bundle\ProductBundle\Entity\BulkUpdateCsvDataInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CsvUpdater
 *
 * @package UmberFirm\Bundle\ProductBundle\Component\Updater
 */
class CsvUpdater implements CsvUpdaterInterface
{
    /**
     * @var Serializer 
     */
    private $csvSerializer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var RecursiveValidator
     */
    private $validator;
    
    /**
     * @var FormBuilder
     */
    private $csvStructure;

    /**
     * @var array
     */
    private $csvFileContent;
    
    /**
     * @var array
     */
    private $errors = [];
    
    /**
     * @var int
     */
    private $rowsUpdated = 0;

    /**
     * @param Serializer $csvSerializer
     * @param EntityManagerInterface $entityManager
     * @param RecursiveValidator $validator
     * @param FormFactory $formFactory
     * @param BulkUpdateCsvDataInterface $csvStructure
     */
    public function __construct(
        Serializer $csvSerializer, 
        EntityManagerInterface $entityManager, 
        RecursiveValidator $validator,
        FormFactory $formFactory,
        BulkUpdateCsvDataInterface $csvStructure
    ) {
        $this->csvSerializer = $csvSerializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->csvStructure = $formFactory->createBuilder(FormType::class, $csvStructure);
    }
    
    /**
     * @return FormBuilder
     */
    public function getCsvStructureFormBuilder(): FormBuilder
    {
        return $this->csvStructure;
    }
    
    /**
     * @param UploadedFile $csvFile
     * @param string $locale
     * 
     * @return bool
     */
    public function process(UploadedFile $csvFile, string $locale): bool
    {
        if(false === $this->validate($csvFile, $locale)) {
            return false;
        }
        $productsData = $this->getCsvFileContent($csvFile);
        foreach ($productsData as $productData) {
            $csvStructureForm = $this->csvStructure->getForm();
            $csvStructureForm->submit($productData);

            if (false === $csvStructureForm->isValid()) {
                // TODO: Add logger
                continue;
            }
            $csvData = $csvStructureForm->getData();
            $product = $this->getProduct($csvData);
            
            if(null === $product) {
                continue;
            }
            $this->setData($product, $csvData, $locale);
            $errors = $this->validator->validate($product);

            if (0 !== count($errors)) {
                // TODO: Add logger
                continue;
            }
            $this->entityManager->persist($product);
            $this->rowsUpdated++;
        }
        $this->entityManager->flush();

        return true;
    }
    
    /**
     * @param UploadedFile $csvFile
     * @param string $locale
     * 
     * @return bool
     */
    protected function validate(UploadedFile $csvFile, string $locale): bool
    {
        $productsData = $this->getCsvFileContent($csvFile);
        foreach ($productsData as $productData) {
            $csvStructureForm = $this->csvStructure->getForm();
            $csvStructureForm->submit($productData);
            
            if (false === $csvStructureForm->isValid()) {
                $this->addError($csvStructureForm);
                continue;
            }
            $csvData = $csvStructureForm->getData();
            $product = $this->getProduct($csvData);
            
            if(null === $product) {
                continue;
            }
            $this->setData($product, $csvData, $locale);
            $errors = $this->validator->validate($product);

            if (0 !== count($errors)) {
                $this->addError($errors);
            }
        }
        
        return (0 === count($this->getErrors())) ? true : false;
    }
    
    /**
     * @param BulkUpdateCsvDataInterface $csvData
     * 
     * @return Product|null
     */
    protected function getProduct(BulkUpdateCsvDataInterface &$csvData): ?Product
    {
        $department = $this->entityManager
          ->getRepository(Department::class)
          ->findByBarcode($csvData->getCode());

        if(false === ($department instanceof Department)) {
            // TODO: Add logger
            return null;
        }

        if(false === ($department->getProductVariant() instanceof ProductVariant)) {
            // TODO: Add logger
            return null;
        }

        if(false === (($product = $department->getProductVariant()->getProduct()) instanceof Product)) {
            // TODO: Add logger
            return null;
        }
        
        return $product;
    }
    
    /**
     * @param Product $product
     * @param BulkUpdateCsvDataInterface $csvData
     * @param string $locale
     */
    protected function setData(Product $product, BulkUpdateCsvDataInterface &$csvData, string $locale): void
    {
        $product->setName($csvData->getName(), $locale);
        $product->setDescription($csvData->getDescription(), $locale);
        $product->setShortDescription($csvData->getShortDescription(), $locale);
        $product->mergeNewTranslations();
    }
    
    /**
     * @param UploadedFile $csvFile
     * 
     * @return array
     */
    protected function getCsvFileContent(UploadedFile $csvFile): array
    {
        if(null === $this->csvFileContent) {
            $csvFileContent = $this->csvSerializer->decode(file_get_contents($csvFile->getPathname()), 'csv');
            $this->csvFileContent = $this->normalizeCsvData($csvFileContent);
        }
        
        return $this->csvFileContent;
    }
    
    /**
     * @param array $csvData
     * 
     * @return array
     */
    protected function normalizeCsvData(array $csvData): array
    {
        $item = array_slice($csvData, 0, 1);
        if(true === is_array($csvData) && false === empty($csvData) && false === is_array(array_shift($item))) {
            return [$csvData];
        }
        
        return $csvData;
    }
    
    /**
     * @param mixed $error
     */
    protected function addError($error): void
    {
        $this->errors[] = $error;
    }
    
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * @return int
     */
    public function getNumberUpdatedRows(): int
    {
        return $this->rowsUpdated;
    }
}
