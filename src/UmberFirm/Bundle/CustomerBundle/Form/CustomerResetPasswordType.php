<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerResetPassword;

/**
 * Class CustomerResetPasswordType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerResetPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'resetPasswordCode',
                null,
                [
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'constraints' => [new NotBlank()],
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
                'data_class' => CustomerResetPassword::class,
            ]
        );
    }
}
