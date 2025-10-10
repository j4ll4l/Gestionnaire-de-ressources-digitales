<?php

namespace App\DataFixtures;


use App\Entity\Categorie;
use App\Entity\Section;
use App\Entity\Ressources;
use App\Entity\Tag;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $tags = [];
        for ($t = 0; $t < 10; $t++) {
            $tag = new Tag();
            $tag->setNom($faker->unique()->word());
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Créer 5 catégories
        for ($i = 0; $i < 5; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->word);
            $categorie->setDescription($faker->sentence);
            $categorie->setPublishedAt(new DateTime());
            $manager->persist($categorie);

            // Pour chaque catégorie, créer 7 sections
            for ($j = 0; $j < 7; $j++) {
                $section = new Section();
                $section->setNom($faker->word);
                $section->setIdCategorie($categorie);
                $section->setPublishedAt(new DateTime());
                $manager->persist($section);

                // Pour chaque section, créer entre 3 et 6 ressources
                $nbRessources = 5;
                for ($k = 0; $k < $nbRessources; $k++) {
                    $ressource = new Ressources();
                    $ressource->setNom($faker->words(3, true));
                    $ressource->setUrl($faker->url);
                    $ressource->setDescription($faker->sentence);
                    $ressource->setIdSection($section);
                    $ressource->setPublishedAt(new DateTime());
                    //  Ajouter entre 1 et 3 tags aléatoires
                    $randomTags = $faker->randomElements($tags, rand(1, 3));
                    foreach ($randomTags as $tag) {
                        $ressource->addTag($tag);
                    }
                    $manager->persist($ressource);
                }
                
            }

            
            $manager->flush();
        }
    }
}
