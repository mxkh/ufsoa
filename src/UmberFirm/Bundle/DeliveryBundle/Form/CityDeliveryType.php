<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\DeliveryBundle\Entity\CityDelivery;

/**
 * Class CityDeliveryType
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Form
 */
class CityDeliveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deliveryGroup')
            ->add('city');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CityDelivery::class,
            ]
        );
    }
}
