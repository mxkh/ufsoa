<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;

/**
 * Class PromocodeType
 *
 * @package UmberFirm\Bundle\OrderBundle\Form
 */
class PromocodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('value')
            ->add('start', DateTimeType::class, ['date_format' => 'yyyy/MM/dd', 'widget' => 'single_text'])
            ->add('finish', DateTimeType::class, ['date_format' => 'yyyy/MM/dd', 'widget' => 'single_text'])
            ->add('isReusable')
            ->add('limiting')
            ->add('customer')
            ->add('promocodeEnum');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Promocode::class,
            ]
        );
    }
}
