<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * 10 nep-producten (opdracht stap 5 – smartphones & accessoires).
 */
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('nl_NL');

        $samples = [
            ['iPhone 15 Hoesje', 'Beschermende hoes voor iPhone 15', 25],
            ['Samsung Galaxy S24', 'Krachtige Android smartphone', 899],
            ['USB-C Oplaadkabel', 'Snelle oplaadkabel 2 meter', 19],
            ['Screenprotector Glas', 'Gehard glas voor alle modellen', 12],
            ['Bluetooth Oordopjes', 'Draadloos met noise cancelling', 79],
            ['Powerbank 20000mAh', 'Extra batterij onderweg', 35],
            ['Telefoonhouder Auto', 'Ventilatiebevestiging', 15],
            ['Google Pixel 8', 'Pure Android ervaring', 749],
            ['MagSafe Lader', 'Draadloos opladen 15W', 45],
            ['Rugzak Laptop', 'Met vak voor tablet en telefoon', 59],
        ];

        foreach ($samples as [$name, $description, $price]) {
            $product = (new Product())
                ->setName($name)
                ->setDescription($description)
                ->setPrice($price);
            $manager->persist($product);
        }

        // Extra faker producten tot 10+ (opdracht vraagt 10)
        $manager->flush();
    }
}
