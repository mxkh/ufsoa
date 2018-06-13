<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class SupplierType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class SupplierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('isActive')
            ->add('username')
            ->add('password')
            ->add('shops')
            ->add('stores');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Supplier::class,
            ]
        );
    }
}
