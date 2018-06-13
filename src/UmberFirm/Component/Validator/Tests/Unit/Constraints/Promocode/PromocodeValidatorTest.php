<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Tests\Unit\Constraints\Promocode;

use Symfony\Bridge\Doctrine\Tests\Fixtures\SingleIntIdEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use UmberFirm\Bundle\PublicBundle\Component\Order\Manager\PromocodeManagerInterface;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Component\Validator\Constraints\Promocode\Promocode;
use UmberFirm\Component\Validator\Constraints\Promocode\PromocodeValidator;

/**
 * Class PromocodeValidatorTest
 *
 * @package UmberFirm\Component\Validator\Tests\Unit\Constraints\Promocode
 */
class PromocodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var PromocodeManagerInterface|\PHPUnit_Framework_MockObject_MockObject￿
     */
    private $promocodeManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->promocodeManager = $this->createMock(PromocodeManagerInterface::class);

        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException
     */
    public function testValidateWithIncorrectObject()
    {
        $constraint = new Promocode(
            [
                'message' => 'myMessage Invalid',
                'promocode' => 'promocode',
            ]
        );

        $object = new SingleIntIdEntity(2, 'Foo');

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException
     */
    public function testValidateWithIncorrectConstraint()
    {
        $constraint = new UniqueEntity(
            [
                'message' => 'myMessage',
                'fields' => ['name', 'name2'],
                'ignoreNull' => false,
            ]
        );

        $object = new PublicOrder();
        $this->validator->validate($object, $constraint);
    }

    public function testValidateNoViolationPromocodeNull()
    {
        $constraint = new Promocode(
            [
                'message' => 'myMessage Invalid',
                'promocode' => 'promocode',
            ]
        );

        $object = new PublicOrder();
        $object->setPromocode(null);
        $this->validator->validate($object, $constraint);
        $this->assertNoViolation();
    }

    public function testValidateNoViolation()
    {
        $constraint = new Promocode(
            [
                'message' => 'myMessage Invalid',
                'promocode' => 'promocode',
            ]
        );

        $object = new PublicOrder();
        $object->setPromocode(new \UmberFirm\Bundle\OrderBundle\Entity\Promocode());

        $this->promocodeManager->expects($this->once())->method('verify')->willReturn(true);
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate($object, $constraint);
        $this->assertNoViolation();
    }

    public function testValidateViolation()
    {
        $constraint = new Promocode(
            [
                'message' => 'myMessage Invalid',
                'promocode' => 'promocode',
            ]
        );

        $object = new PublicOrder();
        $object->setPromocode(new \UmberFirm\Bundle\OrderBundle\Entity\Promocode());

        $this->promocodeManager->expects($this->once())->method('verify')->willReturn(false);
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate($object, $constraint);
        $this->buildViolation($constraint->message)
            ->atPath('property.path.promocode')
            ->assertRaised();
    }

    /**
     * @return PromocodeValidator
     */
    protected function createValidator(): PromocodeValidator
    {
        return new PromocodeValidator($this->promocodeManager);
    }
}
