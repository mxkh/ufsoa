<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;

/**
 * Class DeliveryGroupType
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Form
 */
class DeliveryGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DeliveryGroup::class,
            ]
        );
    }
}
