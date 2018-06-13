<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;

/**
 * Class CustomerSignUpType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('phone')
            ->add(
                'password',
                null,
                [
                    'constraints' => [new NotBlank()],
                ]
            );

        $builder
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();

                    if (true === empty($data['email']) && true === empty($data['phone'])) {
                        $form->addError(new FormError('customer.invalid.credentials'));

                        return;
                    }
                }
            );
    }
}
