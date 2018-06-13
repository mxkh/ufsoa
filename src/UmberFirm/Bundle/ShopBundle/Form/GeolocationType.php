<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\Geolocation;

/**
 * Class GeolocationType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class GeolocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude')
            ->add('longitude')
            ->add('stores');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Geolocation::class,
            ]
        );
    }
}
