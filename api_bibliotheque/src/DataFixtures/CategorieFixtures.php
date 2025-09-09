<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 5; $i++) {
    $category = new Categorie();
    $category->setNom($faker->word);
    $category->setDescription($faker->sentence);
    $manager->persist($category);
    $this->addReference('category_' . ($i - 1), $category); // Ajoutez cette ligne
}

        $manager->flush();
    }
}
