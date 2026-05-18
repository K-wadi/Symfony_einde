<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Employee;
use App\Entity\Offer;
use App\Entity\SalonProduct;
use App\Entity\TimeBlock;
use App\Entity\Treatment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Vult de database met testdata voor lokaal ontwikkelen en demo.
class KapsalonFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Inloggen: eigenaresse@kapper.nl / anita@kapper.nl — wachtwoord test1234
        $owner = new User();
        $owner->setEmail('eigenaresse@kapper.nl');
        $owner->setFirstName('Karin');
        $owner->setLastName('de Vries');
        $owner->setRoles(['ROLE_OWNER']);
        $owner->setPassword($this->hasher->hashPassword($owner, 'test1234'));
        $manager->persist($owner);

        $employees = [];
        foreach ([['Anita', 'Kleuren'], ['Manon', 'Knippen'], ['Freek', 'Heren']] as [$name, $spec]) {
            $user = new User();
            $user->setEmail(strtolower($name).'@kapper.nl');
            $user->setFirstName($name);
            $user->setRoles(['ROLE_EMPLOYEE']);
            $user->setPassword($this->hasher->hashPassword($user, 'test1234'));
            $emp = new Employee();
            $emp->setName($name);
            $emp->setSpecialization($spec);
            $emp->setUser($user);
            $manager->persist($user);
            $manager->persist($emp);
            $employees[] = $emp;

            $block = new TimeBlock();
            $block->setEmployee($emp);
            $block->setStartAt(new \DateTimeImmutable('next monday 09:00'));
            $block->setEndAt(new \DateTimeImmutable('next monday 17:00'));
            $manager->persist($block);
        }

        $treatments = [];
        foreach ([
            ['Knippen dames', 45, 3500],
            ['Kleuren', 90, 7500],
            ['Highlights', 120, 9500],
            ['Wassen & föhnen', 30, 2500],
        ] as [$name, $min, $price]) {
            $t = new Treatment();
            $t->setName($name);
            $t->setDescription('Professionele behandeling met Kérastase producten.');
            $t->setDurationMinutes($min);
            $t->setPriceCents($price);
            $manager->persist($t);
            $treatments[] = $t;
        }

        for ($i = 1; $i <= 10; ++$i) {
            $p = new SalonProduct();
            $p->setName('Product '.$i);
            $p->setDescription('Haarverzorging product '.$i);
            $p->setCategory($i % 2 ? 'Shampoo' : 'Styling');
            $p->setPriceCents(1500 + $i * 200);
            $p->setImageUrl('https://placehold.co/400x300/6b8e23/fff?text=Product+'.$i);
            $manager->persist($p);
        }

        $offer = new Offer();
        $offer->setTitle('Voorjaarsactie');
        $offer->setDescription('20% korting op kleurbehandelingen deze maand.');
        $offer->setActive(true);
        $manager->persist($offer);

        // Testlink afspraak beheren: /afspraak/beheer/demo-token-1234567890abcdef
        $appt = new Appointment();
        $appt->setCustomerName('Demo Klant');
        $appt->setCustomerEmail('klant@example.nl');
        $appt->setCustomerPhone('0612345678');
        $appt->setEmployee($employees[0]);
        $appt->setTreatment($treatments[0]);
        $appt->setStartsAt(new \DateTimeImmutable('next tuesday 10:00'));
        $appt->setManageToken('demo-token-1234567890abcdef');
        $manager->persist($appt);

        $manager->flush();
    }
}
