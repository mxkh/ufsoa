<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class ShopPaymentSettingsType
 *
 * @package UmberFirm\Bundle\PaymentBundle\Form
 */
class ShopPaymentSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publicKey')
            ->add('privateKey')
            ->add('returnUrl')
            ->add('merchantAuthType')
            ->add('merchantTransactionType')
            ->add('testMode')
            ->add('shopPayment');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShopPaymentSettings::class,
            ]
        );
    }
}
