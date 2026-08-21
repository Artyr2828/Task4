<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

final class LastSeenListener
{
    public function __construct(private TokenStorageInterface $tokenStorage, private EntityManagerInterface $entityManager){}
    #[AsEventListener]
    public function onRequestEvent(RequestEvent $event): void
    {
      if (!$event->isMainRequest()) {return;}
       $request = $event->getRequest();
       $currentRoute = $request->attributes->get('_route');
       if (!in_array($currentRoute, ['app_home'], true)){
         return;
       }
       $token = $this->tokenStorage->getToken();
       if (!$token){
         return;
       }
       $user = $token->getUser();
       $user->setLastSeen(new \DateTimeImmutable());
       $this->entityManager->flush();
    }
}
