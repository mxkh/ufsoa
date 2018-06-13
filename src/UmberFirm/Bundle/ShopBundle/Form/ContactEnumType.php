<?php

namespace UmberFirm\Bundle\ShopBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;

/**
 * Class ContactEnumType
 *
 * @package UmberFirm\Bundle\ShopBundle\Form
 */
class ContactEnumType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('contacts');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ContactEnum::class,
            ]
        );
    }
}
