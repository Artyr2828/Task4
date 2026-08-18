<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enums\UserStatus;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Security\Core\User\EquatableInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)] //name
    #[Constraints\NotBlank(message: "Your name cannot be blank")]
    #[Constraints\Length(min: 3, max: 30, minMessage: "Your name must be at least {{ limit }} characters long", maxMessage: "Your name cannot exceed {{ limit }} characters")]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)] //email
    private ?string $email = null;

    #[ORM\Column(length: 255)] //pass
    private string $password;

    #[ORM\Column(enumType: UserStatus::class)]
    private UserStatus $status = UserStatus::UNVERIFIED; //obj(type: UserStatus)

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $lastSeen;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $registeredAt;


    public function __construct() {
      $time = new \DateTimeImmutable();
      $this->lastSeen = $time;
      $this->registeredAt = $time;
    }

    public function getRoles(): array{
      return ["UserRole"];
    }

    public function eraseCredentials(): void{}

    public function getUserIdentifier(): string {
      return $this->email;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPassword(): string {
      return $this->password;
    }

    public function setPassword(string $password): static {
      $this->password = $password;
      return $this;
    }

    public function getRegisteredAt(): \DateTimeImmutable {
      return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): static {
      $this->registeredAt = $registeredAt;
      return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): static {
        $this->email = $email;
        return $this;
    }

    public function getStatus(): UserStatus {
        return $this->status;
    }

    public function setStatus(UserStatus $status): static {
        $this->status = $status;
        return $this;
    }

    public function getLastSeen(): ?\DateTimeImmutable {
        return $this->lastSeen;
    }

    public function setLastSeen(\DateTimeImmutable $lastSeen): static {
        $this->lastSeen = $lastSeen;
        return $this;
    }

    public function isEqualTo(UserInterface $user): bool {
      if ($user->status === UserStatus::BLOCKED){
        return false;
      }
      return true;
    }
}
