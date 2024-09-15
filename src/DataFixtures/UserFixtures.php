<?php

namespace App\DataFixtures;

use App\Entity\Responsibility;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const REGULAR_USER_REFERENCE = 'regular-user';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // -- USERS -- //
        $roleAdmin = new Responsibility(
            1,
            'ROLE_ADMIN',
            'Administrateurice de la base de données',
            'Permet à l\'utilisateurice de créer, éditer et supprimer les comptes utilisateurs de la base de données, ainsi que de gérer les repas et ingrédients.'
        );

        $manager->persist($roleAdmin);

        // Admin User
        $admin = new User();
        $admin->setUsername('admin');
        $password = $this->hasher->hashPassword($admin, 'a');
        $admin->setPassword($password);
        $admin->addResponsibility($roleAdmin);

        // Base user
        $user = new User();
        $user->setUsername('base_user');
        $password = $this->hasher->hashPassword($user, 'a');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $this->addReference(self::REGULAR_USER_REFERENCE, $user);
    }
}
