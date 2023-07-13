<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // for($j=1; $j<=mt_rand(4,6); $j++)
        // {
        //     $produit =new Produit;
        //     $produit->setTitre($this->faker->sentence())
        //             ->setDescription($this->faker->paragraph());
        //     $manager->persist($produit);
            

        // $manager->flush();
        }
}

