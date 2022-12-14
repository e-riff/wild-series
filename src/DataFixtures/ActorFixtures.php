<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Actor;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 12; $i++) {
            $actor = new Actor();
            $actor->setFirstName($faker->firstName());
            $actor->setLastName($faker->lastName());
            $actor->setBirthDate($faker->dateTimeThisCentury);
            $actor->setImage("unnamed.webp");
            $nbOfPrograms = $faker->numberBetween(1, 5);
            //$faker->unique(true)->numberBetween(1, ProgramFixtures::getNbOfPrograms()-1);
            for ($j=0; $j<$nbOfPrograms; $j++)
            {

                $randomProgramId = $faker->unique()->numberBetween(1, ProgramFixtures::getNbOfPrograms()-1);
                $actor->addProgram($this->getReference('program_' . $randomProgramId));
            }
            $faker->unique(true);
            $manager->persist($actor);
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
