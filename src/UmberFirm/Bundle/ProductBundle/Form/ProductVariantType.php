<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class ProductVariantType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class ProductVariantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product')
            ->add('shop')
            ->add('medias')
            ->add('productVariantAttributes');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductVariant::class,
            ]
        );
    }
}
