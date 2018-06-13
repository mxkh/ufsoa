<?php

namespace UmberFirm\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;

/**
 * Class MimeTypeType
 *
 * @package UmberFirm\Bundle\MediaBundle\Form
 */
class MimeTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mediaEnum')
            ->add('name')
            ->add('template');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => MimeType::class,
            ]
        );
    }
}
