<?php

namespace UmberFirm\Bundle\EmployeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class EmployeeLoginType
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Form
 */
class EmployeeLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [new NotBlank(), new Email()],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'constraints' => [new NotBlank()],
                ]
            );
    }
}
