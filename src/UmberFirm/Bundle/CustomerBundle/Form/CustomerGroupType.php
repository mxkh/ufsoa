<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;

/**
 * Class CustomerGroupType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class)
            ->add('customers');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CustomerGroup::class
            ]
        );
    }
}
