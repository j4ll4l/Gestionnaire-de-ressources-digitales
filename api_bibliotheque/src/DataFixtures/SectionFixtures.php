<?php

namespace App\DataFixtures;

use App\Entity\Section;
use App\Entity\Categorie;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SectionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $section = new Section();
            $section->setNom($faker->word);


            $categorie = $this->getReference('category_' . rand(0, 4), Categorie::class);
            $section->setIdCategorie($categorie);

            $manager->persist($section);
            $this->addReference('section_' . $i, $section);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategorieFixtures::class,
        ];
    }
}
