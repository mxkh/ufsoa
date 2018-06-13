<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;

/**
 * Class AttributeGroupType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class AttributeGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('isColorGroup')
            ->add('position')
            ->add('attributeGroupEnum')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AttributeGroup::class,
            ]
        );
    }
}
