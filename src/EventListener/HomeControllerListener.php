<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Controller\HomeController;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;

final class HomeControllerListener
{

    public function onControllerEvent(ControllerEvent $event, Security $security, UserRepository $userRepository): void
    {
        $controller = $event->getController();
        if (is_array($controller)){
          $controller = $controller[0];
        }
        if ($controller instanceof HomeController){
          $user = $security->getUser();

          //if ($user === null){
      //      return $this->redirectToRoute('app_register');
          //}
        //  if ($userRepository->find($user->getId())->)
        }
    }
}
