<?php

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;

/**
 * Class ShoppingCartType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class ShoppingCartType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('amount')
            ->add('shop')
            ->add('customer')
            ->add('shoppingCartItems');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShoppingCart::class,
            ]
        );
    }
}
