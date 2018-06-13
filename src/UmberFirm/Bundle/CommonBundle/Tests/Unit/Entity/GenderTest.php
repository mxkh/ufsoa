<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\Gender;

/**
 * Class GenderTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class GenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Gender
     */
    private $gender;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->gender = new Gender();
    }

    public function testName()
    {
        $this->assertInstanceOf(Gender::class, $this->gender->setName('name', 'en'));
        $this->assertEquals('name', $this->gender->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongNameArgumentType()
    {
        $this->gender->setName(123, 'en');
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWithWrongLocaleArgumentType()
    {
        $this->gender->setName('name', 123);
    }

    public function testNameReturnsNullable()
    {
        $this->assertEquals(null, $this->gender->getName());
    }
}
