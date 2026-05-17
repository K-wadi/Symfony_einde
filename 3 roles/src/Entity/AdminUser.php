<?php

namespace App\Entity;

use App\Repository\AdminUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subtype Admin – nieuws publiceren en gebruikers beheer (ROLE_ADMIN).
 */
#[ORM\Entity(repositoryClass: AdminUserRepository::class)]
class AdminUser extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_ADMIN'];
    }
}
