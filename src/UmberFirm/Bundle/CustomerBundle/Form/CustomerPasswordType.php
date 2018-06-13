<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerPassword;

/**
 * Class CustomerPasswordType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword')
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 6]),
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CustomerPassword::class,
            ]
        );
    }
}
