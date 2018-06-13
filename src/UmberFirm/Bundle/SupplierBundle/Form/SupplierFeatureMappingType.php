<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;

/**
 * Class SupplierFeatureMappingType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class SupplierFeatureMappingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplierFeatureKey')
            ->add('supplierFeatureValue')
            ->add('supplier')
            ->add('featureValue');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SupplierFeatureMapping::class,
            ]
        );
    }
}
