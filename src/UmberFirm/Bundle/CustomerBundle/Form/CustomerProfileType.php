<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;

/**
 * Class CustomerProfileType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer')
            ->add('firstname')
            ->add('lastname')
            ->add('birthday', DateTimeType::class, ['date_format' => 'yyyy/MM/dd', 'widget' => 'single_text'])
            ->add('gender');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CustomerProfile::class,
            ]
        );
    }
}
