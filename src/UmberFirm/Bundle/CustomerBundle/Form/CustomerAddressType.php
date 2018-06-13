<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;

/**
 * Class CustomerAddressType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerAddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shop')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('city')
            ->add('delivery')
            ->add('street')
            ->add('branch')
            ->add('apartment')
            ->add('house')
            ->add('country', CountryType::class)
            ->add('zip');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CustomerAddress::class
            ]
        );
    }
}
