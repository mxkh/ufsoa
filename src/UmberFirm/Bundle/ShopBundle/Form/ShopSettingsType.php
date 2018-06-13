<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;

/**
 * Class ShopSettingsType.
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class ShopSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attribute')
            ->add('value');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ShopSettings::class,
            ]
        );
    }
}
