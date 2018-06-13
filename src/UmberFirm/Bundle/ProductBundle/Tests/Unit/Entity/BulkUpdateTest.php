<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ProductBundle\Entity\BulkUpdate;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class BulkUpdateTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Entity
 */
class BulkUpdateTest extends \PHPUnit_Framework_TestCase
{

    public function testFile()
    {
        $bulkUpdate = new BulkUpdate();
        $file = new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdate.csv', 'test.csv');
        $this->assertInstanceOf(BulkUpdate::class, $bulkUpdate->setFile($file));
        $this->assertInstanceOf(UploadedFile::class, $bulkUpdate->getFile());
    }
}
