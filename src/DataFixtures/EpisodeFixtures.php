<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\episode;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < ProgramFixtures::NBPROGRAMS; $i++)
        {
            $nbEpisodes = $faker->numberBetween(1, 15);

            for ($j = 1 ; $j <= SeasonFixtures::NBSEASONS; $j++)
            {
                for ($k = 1 ; $k <= $nbEpisodes ; $k++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->streetName());
                    $episode->setNumber($k);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSeason($this->getReference('program_'.$i.'season_'.$j));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
