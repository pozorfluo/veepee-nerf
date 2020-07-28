<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $country_names = [
            'France',
            'Belgique',
            'Luxembourg',
        ];
        foreach ($country_names as $country_name) {
            $country = new Country();
            $country->setName($country_name);
            $manager->persist($country);
        }

        $manager->flush();
    }
}
