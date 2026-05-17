<?php

namespace App\Command;

use App\Entity\AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:seed-admin', description: 'Maakt een admin-account aan')]
class SeedAdminCommand extends Command
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
        $email = 'admin@snelle-ingreep.nl';

        if ($this->entityManager->getRepository(AdminUser::class)->findOneBy(['email' => $email])) {
            $io->warning('Admin bestaat al.');

            return Command::SUCCESS;
        }

        $admin = new AdminUser();
        $admin->setEmail($email);
        $admin->setFirstName('Systeem');
        $admin->setLastName('Beheerder');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Admin: '.$email.' / wachtwoord: admin123');

        return Command::SUCCESS;
    }
}
