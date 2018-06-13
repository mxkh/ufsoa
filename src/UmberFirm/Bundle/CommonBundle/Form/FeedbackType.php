<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;

/**
 * Class FeedbackType
 *
 * @package UmberFirm\Bundle\CommonBundle\Form
 */
class FeedbackType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('message')
            ->add('subject')
            ->add('customer');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Feedback::class,
            ]
        );
    }
}
