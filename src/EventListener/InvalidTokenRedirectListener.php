<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InvalidTokenRedirectListener
{

    public function __construct(private TokenStorageInterface $tokenStorage, private UrlGeneratorInterface $generateUrl){}

    #[AsEventListener(event: 'kernel.request', priority: 5)]
    public function onRequestEvent(RequestEvent $event): void
    {
      return;
      if (!$event->isMainRequest()) {return;}

      $privateRoute = ['app_home'];
      $request = $event->getRequest();
      $currentRoute = $request->attributes->get('_route');

      if (!in_array($currentRoute, $privateRoute, true)){return;}

      $token = $this->tokenStorage->getToken();
      if ($request->hasPreviousSession() && !$token?->getUser()){
        $url = $this->generateUrl->generate('app_login');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
      }

  }
}
