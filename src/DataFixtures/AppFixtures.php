<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
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

        // -- INGREDIENTS -- //
        $patate = new Ingredient();
        $patate->setLabel('Pomme de terre');
        $patate->setMeasureType('g');
        $patate->setProteins(1,8);
        $patate->setCarbohydrates(16,7);
        $patate->setFat(0,3);
        $patate->setEnergy(80,5);
        $manager->persist($patate);

        $oignon = new Ingredient();
        $oignon->setLabel('Oignon');
        $oignon->setMeasureType('g');
        $oignon->setProteins(1,4);
        $oignon->setCarbohydrates(9);
        $oignon->setFat(0,2);
        $oignon->setEnergy(43);
        $manager->persist($oignon);

        $boeuf = new Ingredient();
        $boeuf->setLabel('Viande de boeuf');
        $boeuf->setMeasureType('g');
        $boeuf->setProteins(23);
        $boeuf->setCarbohydrates(0);
        $boeuf->setFat(2,3);
        $boeuf->setEnergy(113);
        $manager->persist($boeuf);

        $manager->flush();

        // -- RECIPES -- //
        $poeleeCampagnarde = new Recipe();
        $poeleeCampagnarde->setName('Poêlée campagnarde');
        $manager->persist($poeleeCampagnarde);

        $manager->flush();

        // -- INGREDIENTQUANTITYFORRECIPE -- //
        $patateQuantity = new IngredientQuantityForRecipe();
        $patateQuantity->setIngredient($patate);
        $patateQuantity->setQuantity('300');
        $patateQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($patateQuantity);

        $oignonQuantity = new IngredientQuantityForRecipe();
        $oignonQuantity->setIngredient($oignon);
        $oignonQuantity->setQuantity('200');
        $oignonQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($oignonQuantity);

        $boeufQuantity = new IngredientQuantityForRecipe();
        $boeufQuantity->setIngredient($boeuf);
        $boeufQuantity->setQuantity('250');
        $boeufQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($boeufQuantity);

        $manager->flush();
    }
}
