<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 8; $i++) {
            $tag = new Tag();
            $tag->setNom($faker->word);
            $manager->persist($tag);
            $this->addReference('tag_' . $i, $tag);
        }

        $manager->flush();
    }
}
