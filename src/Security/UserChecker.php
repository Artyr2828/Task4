<?php
namespace App\Security;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use App\Enums\UserStatus;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface{
  public function checkPreAuth(UserInterface $user): void {

    $status = $user->getStatus();
    if ($status === UserStatus::BLOCKED){
      //error_log("Доходит Проверки" . "\n" . "\n" . "\n" . "\n" . "\n" . "\n");
      throw new CustomUserMessageAccountStatusException("Status Is blocked");
    }
  }

  public function checkPostAuth(UserInterface $user): void {
    if (!$user instanceof User) { return; }
    $status = $user->getStatus();
    if ($status === UserStatus::BLOCKED){
      throw new CustomUserMessageAccountStatusException("Status Is blocked");
    }
  }
}
