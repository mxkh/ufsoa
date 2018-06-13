<?php

declare(strict_types=1);

namespace UmberFirm\Component\EventListener\A2lix;

use A2lix\TranslationFormBundle\Form\EventListener\TranslationsListener as A2lixTranslationsListener;
use Symfony\Component\Form\FormEvent;

/**
 * Class TranslationsListener
 *
 * @package UmberFirm\Component\EventListener\A2lix
 */
class TranslationsListener extends A2lixTranslationsListener
{
    /**
     * {@inheritdoc}
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        if (null === $form->getParent()) {
            return;
        }

        parent::preSetData($event);
    }
}
