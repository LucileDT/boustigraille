<?php

namespace App\DataFixtures;

use App\Entity\Responsibility;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load the fixtures.
     */
    public function load(ObjectManager $manager)
    {
        // -- USERS -- //

        /** @var Responsibility $roleAdmin */
        $roleAdmin = $manager->getRepository(Responsibility::class)->find(1);

        // Admin User
        $admin = new User();
        $admin->setUsername('admin');
        $password = $this->encoder->encodePassword($admin, 'a');
        $admin->setPassword($password);
        $admin->addResponsibility($roleAdmin);

        // Base user
        $user = new User();
        $user->setUsername('base_user');
        $password = $this->encoder->encodePassword($user, '-+');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();

        // -- Ingredients -- //
    }
}
