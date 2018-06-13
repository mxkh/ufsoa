<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;

/**
 * Class ShopDeliveryCityPaymentType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class ShopDeliveryCityPaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shopPayment');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShopDeliveryCityPayment::class,
            ]
        );
    }
}
