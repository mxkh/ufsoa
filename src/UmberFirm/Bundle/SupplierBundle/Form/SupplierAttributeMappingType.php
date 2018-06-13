<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;

/**
 * Class SupplierAttributeMappingType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class SupplierAttributeMappingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplierAttributeKey')
            ->add('supplierAttributeValue')
            ->add('supplier')
            ->add('attribute');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SupplierAttributeMapping::class,
            ]
        );
    }
}
