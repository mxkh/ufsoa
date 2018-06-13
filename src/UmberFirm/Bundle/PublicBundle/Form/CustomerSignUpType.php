<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerProfileType;

/**
 * Class CustomerSignUpType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerSignUpType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('profile', CustomerProfileType::class)
            ->add(
                'password',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 6]),
                    ],
                ]
            );

        $builder
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var Customer $customer */
                    $customer = $event->getData();
                    $form = $event->getForm();

                    if (null === $customer->getEmail() && null === $customer->getPhone()) {
                        $form->addError(new FormError('customer.invalid.credentials'));

                        return;
                    }
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Customer::class,
            ]
        );
    }
}
