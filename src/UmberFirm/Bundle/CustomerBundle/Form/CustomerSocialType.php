<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class CustomerSocialType
 *
 * @package UmberFirm\Bundle\CustomerBundle\Form
 */
class CustomerSocialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add(
                'socialNetwork',
                EntityType::class,
                [
                    'class' => SocialNetwork::class,
                    'choice_value' => 'name',
                ]
            )
            ->add('socialId', TextType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('birthday', DateTimeType::class, ['date_format' => 'yyyy/MM/dd', 'widget' => 'single_text'])
            ->add('gender', TextType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
