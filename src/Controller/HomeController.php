<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enums\UserStatus;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class HomeController extends AbstractController
{
    public function __construct (private UserRepository $repositoryUser, private EntityManagerInterface $entityManager){}

    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
      $users = $this->repositoryUser->findBy([], ['lastSeen'=>'DESC']);
      return $this->render('home/index.html.twig', [
        'users' => $users,
      ]);
    }

    #[Route('/home', name: 'app_home_action', methods: ['POST'])]
    public function ChangeUsers(Request $request){
      $action = $request->request->get('action');
      $usersId = $request->request->all('selected_users');
      if (empty($usersId)){
        return $this->redirectToRoute('app_home');
      }
      $users = $this->repositoryUser->findBy(['id'=>$usersId]);

      foreach ($users as $user){
        if ($action === 'delete'){
          $this->entityManager->remove($user);
        }
        if ($action === 'block'){
          $user->setStatus(UserStatus::BLOCKED);
        }
        if ($action === 'unblock'){
          $user->setStatus(UserStatus::ACTIVE);
        }
        if ($action === 'delete_unverified'){
          if ($user->getStatus() === UserStatus::UNVERIFIED){
            $this->entityManager->remove($user);
          }
        }
      }
      $this->entityManager->flush();
      return $this->redirectToRoute('app_home');
    }
}
