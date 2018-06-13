<?php

namespace UmberFirm\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;

/**
 * Class SelectionItemType
 *
 * @package UmberFirm\Bundle\ProductBundle\Form
 */
class SelectionItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selection')
            ->add('product');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SelectionItem::class,
            ]
        );
    }
}
