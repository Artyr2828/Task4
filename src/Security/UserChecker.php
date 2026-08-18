<?php
namespace App\Security;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use App\Enums\UserStatus;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface{
  public function checkPreAuth(UserInterface $user): void {
    $status = $user->getStatus(); //obj UserStatus
    if (!$user instanceof User) {
            return;
        }
    if ($status === UserStatus::BLOCKED){
      throw new CustomUserMessageAccountStatusException("Status Is blocked");
    }
  }

  public function checkPostAuth(UserInterface $user): void {
    if (!$user instanceof User) { return; }
    $status = $user->getStatus(); //obj UserStatus
    if ($status === UserStatus::BLOCKED){
      throw new CustomUserMessageAccountStatusException("Status Is blocked");
    }
  }
}
