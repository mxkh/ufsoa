<?php

namespace UmberFirm\Bundle\ManufacturerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 * Class ManufacturerType
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Form
 */
class ManufacturerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('name')
            ->add('website')
            ->add('reference')
            ->add('shops');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Manufacturer::class,
            ]
        );
    }
}
