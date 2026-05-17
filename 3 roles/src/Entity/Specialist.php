<?php

namespace App\Entity;

use App\Repository\SpecialistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subtype Specialist – accounts staan vooraf in de database (opdracht stap 6).
 */
#[ORM\Entity(repositoryClass: SpecialistRepository::class)]
class Specialist extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_SPECIALIST'];
    }
}
