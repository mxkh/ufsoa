<?php

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;

/**
 * Class OrderItemType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class OrderItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('order')
            ->add('productVariant')
            ->add('amount')
            ->add('price')
            ->add('quantity');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => OrderItem::class,
            ]
        );
    }
}
