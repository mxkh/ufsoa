<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('customerGroup')
            ->add('shop')
            ->add('password');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Customer::class
            ]
        );
    }
}
