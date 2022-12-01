<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const NBSEASONS=5;
    public function load(ObjectManager $manager): void
    {
        //Nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        for ($i = 0; $i < ProgramFixtures::NBPROGRAMS; $i++) {
            //$nbSaisons = $faker->numberBetween(1, 15);
            $year = $faker->year();
            for ($j = 1; $j <= self::NBSEASONS; $j++) {
                $season = new Season();
                $season->setNumber($j);
                $season->setYear($year+$j);
                $season->setDescription($faker->paragraphs(3, true));
                $season->setProgram($this->getReference('program_' . $i));
                $manager->persist($season);
                $this->addReference('program_'.$i.'season_'.$j, $season);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
