<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
//    private SluggerInterface $slugger;

    public const PROGRAMLIST=[
        [
            "Title" => "Carnival Row",
            "Synopsis" => "À la suite d'une guerre perdue par l'Alliance face au Pacte, de nombreux êtres féeriques
            durent fuir leurs royaumes et émigrer dans la république humaine de Burgue.
            Un inspecteur tente d'élucider une série de meurtres commises et rencontre Vignette, une fée. Cara Delevingne <3",
            "Category" => "category_Drame",
            "Poster" => "carnival_row.webp",
            "Year" => "2019"
        ],
        [
            "Title" => "Arcane",
            "Synopsis" => "Raconte l'intensification des tensions entre deux villes suite à l'apparition de nouvelles
                inventions qui menacent de provoquer une véritable révolution",
            "Category" => "category_Animation",
            "Poster" => "arcane.webp",
            "Year" => "2021"
        ],
        [
            "Title" => "Pushing Daisies",
            "Synopsis" => "Un pâtissier qui a le pouvoir de ramener des morts à la vie résout des mystères de meurtre
                avec son amour d'enfance retrouvé, un enquêteur privé cynique et une serveuse malade d'amour.",
            "Category" => "category_Comédie",
            "Poster" => "pushing_daisies.webp",
            "Year" => "2007"
        ],
        [
            "Title" => "The boyz",
            "Synopsis" => "Une histoire d'action centrée sur une équipe de la CIA chargée de maintenir les super-héros
                en ligne, par tous les moyens nécessaires.",
            "Category" => "category_Action",
            "Poster" => "the_boys.jpg",
            "Year" => "2017"
        ],
        [
            "Title" => "Home for christmas",
            "Synopsis" => "Johanne, éternelle célibataire, va enfin amener un petit ami dans sa famille pour Noël!
              Mais quand elle se fait larguer, il ne lui reste que 24 jours pour le remplacer.",
            "Category" => "category_Romance",
            "Poster" => "home_for_christmas.jpg",
            "Year" => "2019"
        ],
        [
            "Title" => "The Witcher",
            "Synopsis" => "Adaptation Live de la saga littéraire du Sorceleur. Le sorceleur Geralt, un chasseur de monstres mutant, se bat pour trouver sa place dans un monde où les humains se révèlent souvent plus vicieux que les bêtes.",
            "Category" => "category_Aventure",
            "Poster" => "the_witcher.webp",
            "Year" => "2019"
        ],
        [
            "Title" => "Friends",
            "Synopsis" => "Suit les vies personnelles et professionnelles de six amis d''une vingtaine et trentaine d''années vivant à Manhattan.",
            "Category" => "category_Comédie",
            "Poster" => "friends.webp",
            "Year" => "1994"
        ],
        [
            "Title" => "The Mandalorian",
            "Synopsis" => "La série se déroule après la chute de l'Empire et avant l'émergence du Premier Ordre, et suit les épreuves d'un tireur solitaire dans les confins de la galaxie, loin de l\'autorité de la Nouvelle République",
            "Category" => "category_Aventure",
            "Poster" => "the_mandalorian.webp",
            "Year" => "2019"
        ],
        [
            "Title" => "His Dark Materials",
            "Synopsis" => "Deux enfants se lancent dans une aventure magique à travers des univers parallèles.','his_dark_materials.jpg",
            "Category" => "category_Aventure",
            "Poster" => "his_dark_materials.webp",
            "Year" => "2019"
        ],
        [
            "Title" => "Minx",
            "Synopsis" => "Dans les années 1970 à Los Angeles, une jeune féministe sérieuse s'associe à un éditeur à 
            loyer modique pour créer le premier magazine érotique pour femmes.",
            "Category" => "category_Comédie",
            "Poster" => "minx.webp",
            "Year" => "2012"
        ]
    ];


    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public static function getNbOfPrograms():int
    {
        return count(self::PROGRAMLIST);
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach(self::PROGRAMLIST as $key => $ProgramInfo) {
            $program = new Program();
            $program->setTitle($ProgramInfo["Title"]);
            $program->setSynopsis($ProgramInfo["Synopsis"]);
            $program->addCategory($this->getReference($ProgramInfo["Category"]));
            $program->setPoster($ProgramInfo["Poster"]);
            $program->setYear($ProgramInfo["Year"]);
            $program->setCountry($faker->country());
            $program->setSlug($this->slugger->slug($ProgramInfo["Title"]));
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
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
