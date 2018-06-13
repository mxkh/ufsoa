<?php

declare(strict_types=1);

namespace UmberFirm\Component\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleListener
 *
 * @package UmberFirm\Component\EventListener
 */
class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * LocaleListener constructor.
     *
     * @param string $defaultLocale
     */
    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        $request->setDefaultLocale($this->defaultLocale);

        $this->setLocale($request);
    }

    /**
     * @param Request $request
     */
    private function setLocale(Request $request): void
    {
        if ($locale = $request->attributes->get('_locale')) {
            $request->setLocale($locale);
        } else {
            $request->setLocale($request->getPreferredLanguage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 17]],
        ];
    }
}
