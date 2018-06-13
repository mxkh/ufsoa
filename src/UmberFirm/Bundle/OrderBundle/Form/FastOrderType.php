<?php

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class FastOrderType
 *
 * @package UmberFirm\Bundle\PublicBundle\Form\Order\DomainObject
 */
class FastOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add(
                'promocode',
                EntityType::class,
                [
                    'class' => Promocode::class,
                    'description' => 'Promocode Uuid object. Before send, verification required.',
                ]
            )
            ->add('phone')
            ->add(//TODO: productVariant filter by shop.
                'productVariant',
                EntityType::class,
                [
                    'class' => ProductVariant::class,
                    'description' => 'ProductVariant Uuid object.',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => FastOrder::class,
            ]
        );
    }
}
