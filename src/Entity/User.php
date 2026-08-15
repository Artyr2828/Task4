<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enums\UserStatus;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)] //name
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
}
