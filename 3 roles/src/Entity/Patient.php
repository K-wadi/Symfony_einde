<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subtype Patient – registratie maakt dit type aan (ROLE_PATIENT).
 */
#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_PATIENT'];
    }
}
