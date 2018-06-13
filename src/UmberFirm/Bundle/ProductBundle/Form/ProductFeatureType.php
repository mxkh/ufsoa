<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;

/**
 * Class ProductFeatureType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class ProductFeatureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product')
            ->add('feature')
            ->add('productFeatureValues');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductFeature::class,
            ]
        );
    }
}
