<?php

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;

/**
 * Class ShoppingCartItemType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class ShoppingCartItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('price')
            ->add('quantity')
            ->add('productVariant');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShoppingCartItem::class,
            ]
        );
    }
}
