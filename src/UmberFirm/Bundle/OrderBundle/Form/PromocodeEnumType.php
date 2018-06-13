<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;

/**
 * Class PromocodeEnumType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class PromocodeEnumType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('calculate')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PromocodeEnum::class,
            ]
        );
    }
}
