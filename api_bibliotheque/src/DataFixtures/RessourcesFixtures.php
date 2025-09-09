<?php

namespace App\DataFixtures;

use App\Entity\Ressources;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RessourcesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $ressource = new Ressources();
            $ressource->setNom($faker->words(2, true));
            $ressource->setUrl($faker->url);
            $ressource->setDescription($faker->text(200));
            $ressource->setIdSection($this->getReference('section_' . rand(0, 9), \App\Entity\Section::class));

            $tagCount = rand(1, 3);
            $usedTags = [];
            for ($j = 0; $j < $tagCount; $j++) {
                do {
                    $tagRef = 'tag_' . rand(0, 7);
                } while (in_array($tagRef, $usedTags));

                $ressource->addTag($this->getReference($tagRef, \App\Entity\Tag::class));
                $usedTags[] = $tagRef;
            }

            $manager->persist($ressource);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SectionFixtures::class,
            TagFixtures::class,
        ];
    }
}
