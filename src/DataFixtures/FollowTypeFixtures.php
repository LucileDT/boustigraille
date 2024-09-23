<?php

namespace App\DataFixtures;

use App\Entity\FollowType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FollowTypeFixtures extends Fixture
{
    public const USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE = 'username-on-recipe-follow-type';
    public const MEAL_LIST_FOLLOW_TYPE_REFERENCE = 'meal-list-follow-type';

    public function load(ObjectManager $manager): void
    {
        $usernameOnRecipeFollowType = new FollowType();
        $usernameOnRecipeFollowType->setCode(FollowType::USERNAME_ON_RECIPE);
        $usernameOnRecipeFollowType->setLabel('Accès au pseudonyme dans les recettes');
        $usernameOnRecipeFollowType->setDescription('Une autre personne inscrite sur votre instance Boustigraille vous propose de voir son pseudo sur les recettes qu\'elle a écrite.');
        $manager->persist($usernameOnRecipeFollowType);

        $mealListFollowType = new FollowType();
        $mealListFollowType->setCode(FollowType::MEAL_LIST);
        $mealListFollowType->setLabel('Accès aux listes de repas');
        $mealListFollowType->setDescription('Une autre personne inscrite sur votre instance Boustigraille vous propose d\'accéder à ses listes de repas.');
        $manager->persist($mealListFollowType);

        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE, $usernameOnRecipeFollowType);
        $this->addReference(self::MEAL_LIST_FOLLOW_TYPE_REFERENCE, $mealListFollowType);
    }
}
