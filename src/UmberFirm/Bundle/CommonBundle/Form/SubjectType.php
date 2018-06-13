<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;

/**
 * Class SubjectType
 *
 * @package UmberFirm\Bundle\CommonBundle\Form
 */
class SubjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isActive')
            ->add('shop')
            ->add('translations', TranslationsType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Subject::class,
            ]
        );
    }
}
