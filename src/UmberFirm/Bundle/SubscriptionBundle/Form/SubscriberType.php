<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SubscriptionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;

/**
 * Class SubscriberType
 *
 * @package UmberFirm\Bundle\SubscriptionBundle\Form
 */
class SubscriberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Subscriber::class,
            ]
        );
    }
}
