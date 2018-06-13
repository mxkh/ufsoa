<?php

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\Order;

/**
 * Class OrderType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shop')
            ->add('customer')
            ->add('quantity')
            ->add('number')
            ->add('shopCurrency')
            ->add('shopDelivery')
            ->add('shopPayment')
            ->add('amount')
            ->add('note')
            ->add('orderItems');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Order::class,
            ]
        );
    }
}
