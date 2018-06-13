<?php

namespace UmberFirm\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\MediaBundle\Entity\Media;

/**
 * Class MediaType
 *
 * @package UmberFirm\Bundle\MediaBundle\Form
 */
class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename')
            ->add('mimeType')
            ->add('extension')
            ->add('mediaEnum');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Media::class,
            ]
        );
    }
}
