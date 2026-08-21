<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class ExceptionListener
{
    public function __construct(private UrlGeneratorInterface $urlGenerator){}
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
      if (!$event->isMainRequest()) {return;}

      $privateRoute = ['app_home'];
      $request = $event->getRequest();
      $currentRoute = $request->attributes->get('_route');

      if (!in_array($currentRoute, $privateRoute, true)){return;}

      $exception = $event->getThrowable();
      if (str_contains($exception->getMessage(), 'contain an identifier') || str_contains($exception->getMessage(), 'UserProvider')){
        if ($request->hasSession()) {
          $request->getSession()->invalidate();
        }
        $url = $this->urlGenerator->generate('app_login');
        $event->setResponse(new RedirectResponse($url));
        }
    }
}
