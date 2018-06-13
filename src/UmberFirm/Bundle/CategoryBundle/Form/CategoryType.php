<?php

namespace UmberFirm\Bundle\CategoryBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;

/**
 * Class CategoryType
 *
 * @package UmberFirm\Bundle\CategoryBundle\Form
 */
class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityType::class, ['required' => false, 'class' => Category::class,])
            ->add('reference')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Category::class,
            ]
        );
    }
}
