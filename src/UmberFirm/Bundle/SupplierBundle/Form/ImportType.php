<?php

namespace UmberFirm\Bundle\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;

/**
 * Class ImportType
 *
 * @package UmberFirm\Bundle\SupplierBundle\Form
 */
class ImportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->add('version')
            ->add('supplier')
            ->add('shop')
            ->add('filename')
            ->add('mimeType')
            ->add('extension');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Import::class,
            ]
        );
    }
}
