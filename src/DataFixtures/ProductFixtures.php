<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $raw_products = [
            [
                'sku' => 'BUNDLE-NERF-10046490',
                'name' => '10 Nerf© Elite Jolt',
                'description' => '+ 4 OFFERTS',
                'price' => 6490,
                'msrp' => 9490,
                'inventory' => 45,
            ],
            [
                'sku' => 'BUNDLE-NERF-04025190',
                'name' => '4 Nerf© Elite Disruptor',
                'description' => '+ 2 OFFERTS',
                'price' => 5190,
                'msrp' => 7990,
                'inventory' => 120,
            ],
            [
                'sku' => 'BUNDLE-NERF-01003990',
                'name' => '1 Nerf© Elite Rapid Strike',
                'description' => '',
                'price' => 3990,
                'msrp' => 5990,
                'inventory' => 50,
            ],
        ];

        foreach ($raw_products as $raw_product) {
            $product = new Product();
            $product->setSku($raw_product['sku'])
                ->setName($raw_product['name'])
                ->setDescription($raw_product['description'])
                ->setPrice($raw_product['price'])
                ->setMsrp($raw_product['msrp'])
                ->setInventory($raw_product['inventory']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
