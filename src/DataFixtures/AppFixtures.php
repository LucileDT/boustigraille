<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Entity\Responsibility;
use App\Entity\Tag;
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
        $password = $this->hasher->hashPassword($user, 'a');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();

        // -- INGREDIENTS -- //
        $patate = new Ingredient();
        $patate->setLabel('Pomme de terre');
        $patate->setMeasureType('g');
        $patate->setProteins(1.8);
        $patate->setCarbohydrates(16.7);
        $patate->setFat(0.3);
        $patate->setEnergy(80.5);
        $patate->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($patate);

        $oignon = new Ingredient();
        $oignon->setLabel('Oignon');
        $oignon->setMeasureType('g');
        $oignon->setUnitSize('100');
        $oignon->setProteins(1.4);
        $oignon->setCarbohydrates(9);
        $oignon->setFat(0,2);
        $oignon->setEnergy(43);
        $oignon->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($oignon);

        $pst = new Ingredient();
        $pst->setLabel('Protéines texturées de soja');
        $pst->setBarCode('3380390221504');
        $pst->setPortionSize(50);
        $pst->setMeasureType('g');
        $pst->setProteins(48.5);
        $pst->setCarbohydrates(17.2);
        $pst->setFat(5.9);
        $pst->setEnergy(337);
        $pst->setHasStockCheckNeededBeforeBuying(false);
        $manager->persist($pst);

        $poireau = new Ingredient();
        $poireau->setLabel('Poireau');
        $poireau->setMeasureType('g');
        $poireau->setUnitSize('150');
        $poireau->setProteins(1.4);
        $poireau->setCarbohydrates(4.9);
        $poireau->setFat(0.2);
        $poireau->setEnergy(27.4);
        $poireau->setHasStockCheckNeededBeforeBuying(false);
        $manager->persist($poireau);

        $carotte = new Ingredient();
        $carotte->setLabel('Carotte');
        $carotte->setMeasureType('g');
        $carotte->setUnitSize('125');
        $carotte->setProteins(0.6);
        $carotte->setCarbohydrates(7.6);
        $carotte->setFat(0.2);
        $carotte->setEnergy(40.2);
        $carotte->setHasStockCheckNeededBeforeBuying(false);
        $manager->persist($carotte);

        $huile = new Ingredient();
        $huile->setLabel('Huile d\'olive');
        $huile->setMeasureType('ml');
        $huile->setProteins(0);
        $huile->setCarbohydrates(0);
        $huile->setFat(92);
        $huile->setEnergy(828);
        $huile->setHasStockCheckNeededBeforeBuying(true);
        $manager->persist($huile);

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
        $patateQuantity->setQuantity('250');
        $patateQuantity->setIsMeasuredByUnit(false);
        $patateQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($patateQuantity);

        $oignonQuantity = new IngredientQuantityForRecipe();
        $oignonQuantity->setIngredient($oignon);
        $oignonQuantity->setQuantity('50');
        $oignonQuantity->setIsMeasuredByUnit(false);
        $oignonQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($oignonQuantity);

        $pstQuantity = new IngredientQuantityForRecipe();
        $pstQuantity->setIngredient($pst);
        $pstQuantity->setQuantity('60');
        $pstQuantity->setIsMeasuredByUnit(false);
        $pstQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($pstQuantity);

        $poireauQuantity = new IngredientQuantityForRecipe();
        $poireauQuantity->setIngredient($poireau);
        $poireauQuantity->setQuantity('1');
        $poireauQuantity->setIsMeasuredByUnit(true);
        $poireauQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($poireauQuantity);

        $carotteQuantity = new IngredientQuantityForRecipe();
        $carotteQuantity->setIngredient($carotte);
        $carotteQuantity->setQuantity('2');
        $carotteQuantity->setIsMeasuredByUnit(true);
        $carotteQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($carotteQuantity);

        $huileQuantity = new IngredientQuantityForRecipe();
        $huileQuantity->setIngredient($huile);
        $huileQuantity->setQuantity('15');
        $huileQuantity->setIsMeasuredByUnit(false);
        $huileQuantity->setRecipe($poeleeCampagnarde);
        $manager->persist($huileQuantity);

        // -- TAGS -- //
        $facile = new Tag();
        $facile->setLabel('Facile');
        $manager->persist($facile);
        $difficile = new Tag();
        $difficile->setLabel('Difficile');
        $manager->persist($difficile);
        $rapide = new Tag();
        $rapide->setLabel('Rapide');
        $manager->persist($rapide);
        $long = new Tag();
        $long->setLabel('Long');
        $manager->persist($long);
        $vegetarien = new Tag();
        $vegetarien->setLabel('Végé');
        $manager->persist($vegetarien);
        $vegan = new Tag();
        $vegan->setLabel('Vegan');
        $manager->persist($vegan);

        $poeleeCampagnarde->addTag($long);
        $poeleeCampagnarde->addTag($vegetarien);
        $poeleeCampagnarde->addTag($vegan);
        $manager->persist($poeleeCampagnarde);

        $manager->flush();
    }
}
