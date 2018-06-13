<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;

/**
 * Class SupplierManufacturerMappingType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class SupplierManufacturerMappingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplierManufacturer')
            ->add('supplier')
            ->add('manufacturer');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SupplierManufacturerMapping::class,
            ]
        );
    }
}
