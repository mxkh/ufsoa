<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;

/**
 * Class SelectionType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class SelectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shop')
            ->add('isActive')
            ->add('items')
            ->add('translations', TranslationsType::class);
        }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Selection::class,
            ]
        );
    }
}
