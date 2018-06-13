<?php

namespace UmberFirm\Bundle\EmployeeBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;

/**
 * Class EmployeeGroupType
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Form
 */
class EmployeeGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('employees');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => EmployeeGroup::class,
            ]
        );
    }
}
