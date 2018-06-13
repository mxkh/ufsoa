<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;

/**
 * Class SupplierStoreMappingType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class SupplierStoreMappingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplierStore')
            ->add('supplier')
            ->add('store');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SupplierStoreMapping::class,
            ]
        );
    }
}
