<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;

/**
 * Class StoreSocialProfileType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class StoreSocialProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('socialProfileEnum')
            ->add('stores');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => StoreSocialProfile::class,
            ]
        );
    }
}
