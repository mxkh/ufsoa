<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCode;

/**
 * Class CustomerConfirmationCodeTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\DataTransferObject
 */
class CustomerConfirmationCodeTest extends \PHPUnit_Framework_TestCase
{
    /** @var Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->customer = $this->createPartialMock(
            Customer::class,
            [
                'getId',
                'getPhone',
                'getConfirmationCode',
            ]
        );
    }

    public function testGetId()
    {
        $uuid = Uuid::uuid4();
        $this->customer
            ->method('getId')
            ->willReturn($uuid);

        $customerConfirmationCode = new CustomerConfirmationCode($this->customer);

        $this->assertInstanceOf(UuidInterface::class, $customerConfirmationCode->getId());
        $this->assertEquals($uuid, $customerConfirmationCode->getId());
    }

    public function testGetPhone()
    {
        $phone = '0500123456';
        $this->customer
            ->method('getPhone')
            ->willReturn($phone);

        $customerConfirmationCode = new CustomerConfirmationCode($this->customer);

        $this->assertEquals($phone, $customerConfirmationCode->getPhone());
        $this->assertInternalType('string', $customerConfirmationCode->getPhone());
    }

    public function testGetCode()
    {
        $confirmationCode = '0123';
        $this->customer
            ->method('getConfirmationCode')
            ->willReturn($confirmationCode);

        $customerConfirmationCode = new CustomerConfirmationCode($this->customer);

        $this->assertEquals($confirmationCode, $customerConfirmationCode->getCode());
        $this->assertInternalType('string', $customerConfirmationCode->getCode());
    }
}
