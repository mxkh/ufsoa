<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;

/**
 * Class ProductVariantMediaType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class ProductVariantMediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productMedia')
            ->add('productVariant');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductVariantMedia::class,
            ]
        );
    }
}
