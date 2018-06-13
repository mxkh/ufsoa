<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;

/**
 * Class ShopDeliveryCityType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class ShopDeliveryCityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShopDeliveryCity::class,
            ]
        );
    }
}
