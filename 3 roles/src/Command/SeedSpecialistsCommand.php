<?php

namespace App\Command;

use App\Entity\Specialist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Plaatst 2 specialisten in de user-tabel (opdracht stap 6).
 * Wachtwoord voor beide: specialist123
 */
#[AsCommand(name: 'app:seed-specialists', description: 'Seed twee specialist accounts')]
class SeedSpecialistsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $specialists = [
            ['anna.smit@snelle-ingreep.nl', 'Anna', 'Smit', 'Cardiologie'],
            ['peter.jansen@snelle-ingreep.nl', 'Peter', 'Jansen', 'Orthopedie'],
        ];

        foreach ($specialists as [$email, $first, $last, $spec]) {
            $existing = $this->entityManager->getRepository(Specialist::class)->findOneBy(['email' => $email]);
            if ($existing) {
                $io->note(sprintf('Specialist %s bestaat al.', $email));
                continue;
            }

            $specialist = new Specialist();
            $specialist->setEmail($email);
            $specialist->setFirstName($first);
            $specialist->setLastName($last);
            $specialist->setSpecialization($spec);
            $specialist->setPassword($this->passwordHasher->hashPassword($specialist, 'specialist123'));

            $this->entityManager->persist($specialist);
            $io->success(sprintf('Specialist aangemaakt: %s (wachtwoord: specialist123)', $email));
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
