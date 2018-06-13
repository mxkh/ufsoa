<?php

declare(strict_types=1);

namespace UmberFirm\Component\Validator\Tests\Unit\Constraints\ShoppingCart;

use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Tests\Fixtures\SingleIntIdEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Validator\Constraints\ShoppingCart\ShoppingCart;
use UmberFirm\Component\Validator\Constraints\ShoppingCart\ShoppingCartValidator;

/**
 * Class ShoppingCartValidatorTest
 *
 * @package UmberFirm\Component\Validator\Tests\Unit\Constraints\ShoppingCart
 */
class ShoppingCartValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException
     */
    public function testValidateWithIncorrectObject()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
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

    public function testValidateWithEmptyShoppingCart()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $object = new PublicOrder();
        $object->setShoppingCart(null);

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageNull)->assertRaised();
    }

    public function testValidateWithEmptyCustomer()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $object = new PublicOrder();
        $object->setShoppingCart(new \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart());
        $object->setCustomer(null);

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageNull)->assertRaised();
    }

    public function testValidateWithEmptyShop()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $object = new PublicOrder();
        $object->setShoppingCart(new \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart());
        $object->setCustomer(new Customer());
        $object->setShop(null);

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageNull)->assertRaised();
    }

    public function testValidateWithEmptyCustomerInShoppingCart()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $shoppingCartEntity = new  \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart();
        $shoppingCartEntity->setCustomer(null);

        $object = new PublicOrder();
        $object->setShoppingCart($shoppingCartEntity);
        $object->setCustomer(new Customer());
        $object->setShop(new Shop());

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageInvalid)
            ->atPath('property.path.shoppingCart')
            ->assertRaised();
    }

    public function testValidateWithEmptyShopInShoppingCart()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $shoppingCartEntity = new  \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart();
        $shoppingCartEntity->setCustomer(new Customer());
        $shoppingCartEntity->setShop(null);

        $object = new PublicOrder();
        $object->setShoppingCart($shoppingCartEntity);
        $object->setCustomer(new Customer());
        $object->setShop(new Shop());

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageInvalid)
            ->atPath('property.path.shoppingCart')
            ->assertRaised();
    }

    public function testValidateNoViolation()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $shoppingCartEntity = new  \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart();
        $shoppingCartEntity->setCustomer(new Customer());
        $shoppingCartEntity->setShop(new Shop());

        $object = new PublicOrder();
        $object->setShoppingCart($shoppingCartEntity);
        $object->setCustomer(new Customer());
        $object->setShop(new Shop());

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNoInValid()
    {
        $constraint = new ShoppingCart(
            [
                'messageInvalid' => 'myMessage Invalid',
                'messageNull' => 'myMessage Null',
                'shoppingCart' => 'shoppingCart',
            ]
        );

        $uuid = $this->createMock(Uuid::class);
        $shop = $this->createMock(Shop::class);
        $shop->expects($this->once())->method('getId')->willReturn($uuid);
        $shoppingCartEntity = new  \UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart();
        $shoppingCartEntity->setCustomer(new Customer());
        $shoppingCartEntity->setShop($shop);

        $object = new PublicOrder();
        $object->setShoppingCart($shoppingCartEntity);
        $object->setCustomer(new Customer());
        $object->setShop(new Shop());

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->messageInvalid)
            ->atPath('property.path.shoppingCart')
            ->assertRaised();
    }

    /**
     * @return ShoppingCartValidator
     */
    protected function createValidator(): ShoppingCartValidator
    {
        return new ShoppingCartValidator();
    }
}
