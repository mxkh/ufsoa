<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductSeo;

/**
 * Class ProductType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manufacturer')
            ->add('isHidden')
            ->add('salePrice')
            ->add('price')
            ->add('shop')
            ->add('reference')
            ->add('categories')
            ->add('productFeatures')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Product::class,
            ]
        );
    }
}
