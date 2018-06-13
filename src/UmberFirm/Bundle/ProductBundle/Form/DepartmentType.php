<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\Department;

/**
 * Class DepartmentType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class DepartmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article')
            ->add('ean13')
            ->add('upc')
            ->add('price')
            ->add('salePrice')
            ->add('quantity')
            ->add('productVariant')
            ->add('supplier')
            ->add('store')
            ->add('shop');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Department::class,
            ]
        );
    }
}
