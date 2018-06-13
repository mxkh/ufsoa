<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;

/**
 * Class SocialProfileEnumType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class SocialProfileEnumType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('storeSocialProfiles');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SocialProfileEnum::class,
            ]
        );
    }
}
