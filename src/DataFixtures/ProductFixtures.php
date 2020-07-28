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
                'image' => 'nerf1.jpg',
                'hot' => true,
            ],
            [
                'sku' => 'BUNDLE-NERF-04025190',
                'name' => '4 Nerf© Elite Disruptor',
                'description' => '+ 2 OFFERTS',
                'price' => 5190,
                'msrp' => 7990,
                'inventory' => 120,
                'image' => 'nerf2.jpg',
                'hot' => false,
            ],
            [
                'sku' => 'BUNDLE-NERF-01124490',
                'name' => '1 Nerf© Green Moustigre',
                'description' => '+ 12 OFFERTS',
                'price' => 4490,
                'msrp' => 5990,
                'inventory' => 12,
                'image' => 'nerf4.jpg',
                'hot' => true,
            ],
            [
                'sku' => 'BUNDLE-NERF-01003990',
                'name' => '1 Nerf© Elite Rapid Strike',
                'description' => '',
                'price' => 3990,
                'msrp' => 5990,
                'inventory' => 50,
                'image' => 'nerf3.jpg',
                'hot' => false,
            ],
        ];

        foreach ($raw_products as $raw_product) {
            $product = new Product();

            foreach($raw_product as $key => $value) {
                $setter = 'set'. ucfirst($key);
                if(method_exists(Product::class, $setter))
                {
                    $product->$setter($value);
                }
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
