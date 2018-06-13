<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Component\Updater;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormBuilder;

/**
 * interface CsvUpdaterInterface
 *
 * @package UmberFirm\Bundle\ProductBundle\Component\Updater
 */
interface CsvUpdaterInterface
{
    /**
     * @param UploadedFile $csvFile
     * @param string $locale
     * 
     * @return bool
     */
    public function process(UploadedFile $csvFile, string $locale): bool;
    
    /**
     * @return FormBuilder
     */
    public function getCsvStructureFormBuilder(): FormBuilder;
    
    /**
     * @return array
     */
    public function getErrors(): array;
    
    /**
     * @return int
     */
    public function getNumberUpdatedRows(): int;
}
