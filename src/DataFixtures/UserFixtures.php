<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private const USERS_LIST = [
        ['email' => "nem@nem.fr",
            "password" => "nem",
            "role"=>"USER"],
        ['email' => "admin@admin.fr",
            "password" => "admin",
            "role"=>"ADMIN"],

    ];

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        foreach(self::USERS_LIST as $userData)
        {
        $user = new User;
        $user->setEmail($userData["email"]);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $userData["password"]);
        $user->setPassword($hashedPassword);
        $user->setRoles(["ROLE_" . $userData["role"]]);
        $manager->persist($user);
        }
        $manager->flush();
    }
}
