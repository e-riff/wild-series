<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMLIST=[
        [
            "Title" => "Carnival Row",
            "Synopsis" => "À la suite d'une guerre perdue par l'Alliance face au Pacte, de nombreux êtres féeriques
            durent fuir leurs royaumes et émigrer dans la république humaine de Burgue.
            Un inspecteur tente d'élucider une série de meurtres commises et rencontre Vignette, une fée. Cara Delevingne <3",
            "Category" => "category_Drame"
        ],
        [
            "Title" => "Arcane",
            "Synopsis" => "Raconte l'intensification des tensions entre deux villes suite à l'apparition de nouvelles
                inventions qui menacent de provoquer une véritable révolution",
            "Category" => "category_Animation"
        ],
        [
            "Title" => "Pushing Daisies",
            "Synopsis" => "Un pâtissier qui a le pouvoir de ramener des morts à la vie résout des mystères de meurtre
                avec son amour d'enfance retrouvé, un enquêteur privé cynique et une serveuse malade d'amour.",
            "Category" => "category_Comédie"
        ],
        [
            "Title" => "The boyz",
            "Synopsis" => "Une histoire d'action centrée sur une équipe de la CIA chargée de maintenir les super-héros
                en ligne, par tous les moyens nécessaires.",
            "Category" => "category_Action"
        ],
        [
            "Title" => "Home for christmas",
            "Synopsis" => "Johanne, éternelle célibataire, va enfin amener un petit ami dans sa famille pour Noël!
              Mais quand elle se fait larguer, il ne lui reste que 24 jours pour le remplacer.",
            "Category" => "category_Romance"
        ],
        [
            "Title" => "Minx",
            "Synopsis" => "Dans les années 1970 à Los Angeles, une jeune féministe sérieuse s'associe à un éditeur à 
            loyer modique pour créer le premier magazine érotique pour femmes.",
            "Category" => "category_Comédie"
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach(self::PROGRAMLIST as $ProgramInfo) {
            $program = new Program();
            $program->setTitle($ProgramInfo["Title"]);
            $program->setSynopsis($ProgramInfo["Synopsis"]);
            $program->setCategory($this->getReference($ProgramInfo["Category"]));
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
