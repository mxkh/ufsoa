<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\Store;

/**
 * Class StoreType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class StoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('name')
            ->add('supplier')
            ->add('isActive')
            ->add('storeEnum')
            ->add('reference')
            ->add('geolocation')
            ->add('contacts')
            ->add('storeSocialProfiles')
            ->add('shops');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Store::class,
            ]
        );
    }
}
