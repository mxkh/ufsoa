<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class PublicOrderType
 *
 * @package UmberFirm\Bundle\PublicBundle\Form
 */
class PublicOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'promocode',
                EntityType::class,
                [
                    'class' => Promocode::class,
                ]
            )
            ->add(
                'shoppingCart',
                EntityType::class,
                [
                    'class' => ShoppingCart::class,
                ]
            )
            ->add(
                'shopPayment',
                EntityType::class,
                [
                    'class' => ShopPayment::class,
                ]
            )
            ->add(
                'shopDelivery',
                EntityType::class,
                [
                    'class' => ShopDelivery::class,
                ]
            )
            ->add(
                'shopCurrency',
                EntityType::class,
                [
                    'class' => ShopCurrency::class,
                ]
            )
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add(
                'city',
                EntityType::class,
                [
                    'class' => City::class,
                ]
            )
            ->add(
                'street',
                EntityType::class,
                [
                    'class' => Street::class,
                ]
            )
            ->add(
                'branch',
                EntityType::class,
                [
                    'class' => Branch::class,
                ]
            )
            ->add('apartment')
            ->add('house')
            ->add('note')
            ->add('email')
            ->add('country', CountryType::class)
            ->add('zip');

        $builder
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var PublicOrder $data */
                    $data = $event->getData();
                    $shoppingCart = $data->getShoppingCart();

                    $form = $event->getForm();

                    if (null !== $shoppingCart && true === $shoppingCart->isArchived()) {
                        $form->addError(new FormError('order.shopping.cart.archived'));

                        return;
                    }
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PublicOrder::class,
            ]
        );
    }
}
