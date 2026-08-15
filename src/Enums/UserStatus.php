<?php
namespace App\Enums;

enum UserStatus: string {
  case UNVERIFIED = 'unverified';
  case ACTIVE = 'active';
  case BLOCKED = 'blocked';
}
