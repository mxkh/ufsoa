<?php

namespace UmberFirm\Bundle\EmployeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;

/**
 * Class EmployeeType
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Form
 */
class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('employeeGroup')
            ->add('birthday', DateTimeType::class, ['date_format' => 'yyyy/MM/dd', 'widget' => 'single_text'])
            ->add('password', PasswordType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Employee::class,
            ]
        );
    }
}
