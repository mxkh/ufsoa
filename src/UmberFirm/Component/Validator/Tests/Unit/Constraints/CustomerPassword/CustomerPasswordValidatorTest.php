<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Tests\Unit\Constraints\CustomerPassword;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Validator\Constraints\CustomerPassword\CustomerPassword;
use UmberFirm\Component\Validator\Constraints\CustomerPassword\CustomerPasswordValidator;

/**
 * Class CustomerPasswordValidatorTest
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @package UmberFirm\Component\Validator\Tests\Unit\Constraints\CustomerPassword
 */
class CustomerPasswordValidatorTest extends ConstraintValidatorTestCase
{
    const PASSWORD = 's3Cr3t';

    const SALT = '^S4lt$';

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $user = $this->createUser();
        $this->tokenStorage = $this->createTokenStorage($user);
        $this->encoder = $this->createPasswordEncoder();
        $this->encoderFactory = $this->createEncoderFactory($this->encoder);

        parent::setUp();
    }

    public function testPasswordIsValid()
    {
        $constraint = new CustomerPassword(array(
            'message' => 'myMessage',
        ));

        $this->encoder->expects($this->once())
            ->method('isPasswordValid')
            ->with(static::PASSWORD, 'secret', static::SALT)
            ->will($this->returnValue(true));

        $this->validator->validate('secret', $constraint);

        $this->assertNoViolation();
    }

    public function testPasswordIsNotValid()
    {
        $constraint = new CustomerPassword(array(
            'message' => 'myMessage',
        ));

        $this->encoder->expects($this->once())
            ->method('isPasswordValid')
            ->with(static::PASSWORD, 'secret', static::SALT)
            ->will($this->returnValue(false));

        $this->validator->validate('secret', $constraint);

        $this->buildViolation('myMessage')
            ->assertRaised();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testUserIsNotValid()
    {
        $user = $this->createMock(Shop::class);

        $this->tokenStorage = $this->createTokenStorage($user);
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate('secret', new CustomerPassword());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUser(): \PHPUnit_Framework_MockObject_MockObject
    {
        $shop = $this->createMock(Shop::class);
        $customer = $this->createMock(Customer::class);

        $shop
            ->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $customer
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue(static::PASSWORD));

        $customer
            ->expects($this->any())
            ->method('getSalt')
            ->will($this->returnValue(static::SALT));

        return $shop;
    }

    /**
     * @param bool $isPasswordValid
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createPasswordEncoder($isPasswordValid = true): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->createMock(PasswordEncoderInterface::class);
    }

    /**
     * @param null $encoder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createEncoderFactory($encoder = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $mock = $this->createMock(EncoderFactoryInterface::class);

        $mock
            ->expects($this->any())
            ->method('getEncoder')
            ->will($this->returnValue($encoder));

        return $mock;
    }

    /**
     * @param null $user
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createTokenStorage($user = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $token = $this->createAuthenticationToken($user);

        $mock = $this->createMock(TokenStorageInterface::class);
        $mock
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        return $mock;
    }

    /**
     * @param null $user
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAuthenticationToken($user = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $mock = $this->createMock(TokenInterface::class);
        $mock
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        return $mock;
    }

    /**
     * @return CustomerPasswordValidator
     */
    protected function createValidator(): CustomerPasswordValidator
    {
        return new CustomerPasswordValidator($this->tokenStorage, $this->encoderFactory);
    }
}
