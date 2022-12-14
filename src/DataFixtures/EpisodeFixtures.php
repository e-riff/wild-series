<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\episode;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < ProgramFixtures::getNbOfPrograms(); $i++)
        {
            $nbEpisodes = $faker->numberBetween(1, 15);
            $episodesLength = $faker->numberBetween(18, 62);


            for ($j = 1 ; $j <= SeasonFixtures::NBSEASONS; $j++)
            {
                for ($k = 1 ; $k <= $nbEpisodes ; $k++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->streetName());
                    $episode->setNumber($k);
                    $episode->setDuration($faker->numberBetween($episodesLength-4, $episodesLength+4));
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSlug($this->slugger->slug($episode->getTitle()));
                    $episode->setSeason($this->getReference('program_'.$i.'_season_'.$j));
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
