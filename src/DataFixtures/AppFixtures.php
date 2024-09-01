<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Entity\Responsibility;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * Load the fixtures.
     */
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
        $password = $this->hasher->hashPassword($user, '-+');
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
        $patate->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($patate);

        $oignon = new Ingredient();
        $oignon->setLabel('Oignon');
        $oignon->setMeasureType('g');
        $oignon->setProteins(1,4);
        $oignon->setCarbohydrates(9);
        $oignon->setFat(0,2);
        $oignon->setEnergy(43);
        $oignon->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($oignon);

        $boeuf = new Ingredient();
        $boeuf->setLabel('Viande de boeuf');
        $boeuf->setMeasureType('g');
        $boeuf->setProteins(23);
        $boeuf->setCarbohydrates(0);
        $boeuf->setFat(2,3);
        $boeuf->setEnergy(113);
        $boeuf->setHasStockCheckNeededBeforeBuying(false);
        $manager->persist($boeuf);

        $oeuf = new Ingredient();
        $oeuf->setLabel('Oeuf');
        $oeuf->setMeasureType('g');
        $oeuf->setUnitSize('53');
        $oeuf->setProteins(12);
        $oeuf->setCarbohydrates(0,7);
        $oeuf->setFat(10);
        $oeuf->setEnergy(141);
        $oeuf->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($oeuf);

        $manager->flush();

        // -- RECIPES -- //
        $poeleeCampagnarde = new Recipe();
        $poeleeCampagnarde->setName('Poêlée campagnarde');
        $poeleeCampagnarde->setAuthor($user);
        $manager->persist($poeleeCampagnarde);

        $manager->flush();

        // -- INGREDIENTQUANTITYFORRECIPE -- //
        $patateQuantity = new IngredientQuantityForRecipe();
        $patateQuantity->setIngredient($patate);
        $patateQuantity->setQuantity('300');
        $patateQuantity->setIsMeasuredByUnit(false);
        $patateQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($patateQuantity);

        $oignonQuantity = new IngredientQuantityForRecipe();
        $oignonQuantity->setIngredient($oignon);
        $oignonQuantity->setQuantity('200');
        $oignonQuantity->setIsMeasuredByUnit(false);
        $oignonQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($oignonQuantity);

        $boeufQuantity = new IngredientQuantityForRecipe();
        $boeufQuantity->setIngredient($boeuf);
        $boeufQuantity->setQuantity('250');
        $boeufQuantity->setIsMeasuredByUnit(false);
        $boeufQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($boeufQuantity);

        $oeufQuantity = new IngredientQuantityForRecipe();
        $oeufQuantity->setIngredient($oeuf);
        $oeufQuantity->setQuantity('1');
        $oeufQuantity->setIsMeasuredByUnit(true);
        $oeufQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($oeufQuantity);

        $manager->flush();
    }
}
