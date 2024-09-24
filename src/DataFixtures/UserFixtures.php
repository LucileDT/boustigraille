<?php

namespace App\DataFixtures;

use App\Entity\Responsibility;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const BASIL_USER_REFERENCE = 'basil-user';
    public const BRUNEHILDE_USER_REFERENCE = 'brunehilde-user';
    public const SYLVAIN_USER_REFERENCE = 'sylvain-user';
    public const SERAPHINA_USER_REFERENCE = 'seraphina-user';
    public const FARAH_USER_REFERENCE = 'farah-user';
    public const ISHANA_USER_REFERENCE = 'ishana-user';

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

        // admin users
        $admin = new User();
        $admin->setUsername('admin');
        $password = $this->hasher->hashPassword($admin, 'a');
        $admin->setPassword($password);
        $admin->addResponsibility($roleAdmin);

        // base users (private)
        $basil = new User();
        $basil->setUsername('basil');
        $password = $this->hasher->hashPassword($basil, 'a');
        $basil->setPassword($password);

        $brunehilde = new User();
        $brunehilde->setUsername('brunehilde');
        $password = $this->hasher->hashPassword($brunehilde, 'a');
        $brunehilde->setPassword($password);

        // base users (semi private)
        $sylvain = new User();
        $sylvain->setUsername('sylvain');
        $sylvain->setDoShowUsernameOnRecipe(true);
        $password = $this->hasher->hashPassword($sylvain, 'a');
        $sylvain->setPassword($password);

        $seraphina = new User();
        $seraphina->setUsername('séraphina');
        $seraphina->setdoShowWrittenMealListToOthers(true);
        $password = $this->hasher->hashPassword($seraphina, 'a');
        $seraphina->setPassword($password);

        // base users (public)
        $farah = new User();
        $farah->setUsername('farah');
        $farah->setDoShowUsernameOnRecipe(true);
        $farah->setdoShowWrittenMealListToOthers(true);
        $password = $this->hasher->hashPassword($farah, 'a');
        $farah->setPassword($password);

        $ishana = new User();
        $ishana->setUsername('ishana');
        $ishana->setDoShowUsernameOnRecipe(true);
        $ishana->setdoShowWrittenMealListToOthers(true);
        $password = $this->hasher->hashPassword($ishana, 'a');
        $ishana->setPassword($password);

        $manager->persist($basil);
        $manager->persist($brunehilde);
        $manager->persist($sylvain);
        $manager->persist($seraphina);
        $manager->persist($farah);
        $manager->persist($ishana);
        $manager->persist($admin);
        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $this->addReference(self::BASIL_USER_REFERENCE, $basil);
        $this->addReference(self::BRUNEHILDE_USER_REFERENCE, $brunehilde);
        $this->addReference(self::SYLVAIN_USER_REFERENCE, $sylvain);
        $this->addReference(self::SERAPHINA_USER_REFERENCE, $seraphina);
        $this->addReference(self::FARAH_USER_REFERENCE, $farah);
        $this->addReference(self::ISHANA_USER_REFERENCE, $ishana);
    }

    public function getDependencies(): array
    {
        return [
            FollowTypeFixtures::class,
        ];
    }
}
