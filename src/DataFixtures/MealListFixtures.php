<?php

namespace App\DataFixtures;

use App\Entity\MealList;
use App\Entity\MealQuantityForList;
use App\Entity\Tag;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MealListFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        // meal list 1
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P8D')));
        $mealList->setEndDate($now->sub(new DateInterval('P1D')));
        $mealList->setIsStartingAtLunch(true);
        $mealList->setIsEndingAtLunch(true);
        $mealList->setPersonName($this->getReference(UserFixtures::ADMIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::POLENTA_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::SASHIMI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::GNOCCHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 2
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P1D')));
        $mealList->setEndDate($now->add(new DateInterval('P6D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::ADMIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::CHEESE_OMELET_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::POLENTA_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::GNOCCHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 3
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $mealList->setStartDate($now->add(new DateInterval('P7D')));
        $mealList->setEndDate($now->add(new DateInterval('P14D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::ADMIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::POTATOES_AND_CHEESE_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::STEAK_AND_POTATOES_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::HAM_ROSTIES_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 4
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE));
        $mealList->setStartDate($now->add(new DateInterval('P3D')));
        $mealList->setEndDate($now->add(new DateInterval('P10D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_FRIED_RICE_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 5
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P5D')));
        $mealList->setEndDate($now->add(new DateInterval('P2D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_FRIED_RICE_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 6
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P12D')));
        $mealList->setEndDate($now->sub(new DateInterval('P5D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_FRIED_RICE_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 7
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::FARAH_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P12D')));
        $mealList->setEndDate($now->sub(new DateInterval('P5D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::FARAH_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::GNOCCHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::STEAK_AND_POTATOES_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        // meal list 8
        $mealList = new MealList();
        $mealList->setAuthor($this->getReference(UserFixtures::FARAH_USER_REFERENCE));
        $mealList->setStartDate($now->sub(new DateInterval('P5D')));
        $mealList->setEndDate($now->add(new DateInterval('P2D')));
        $mealList->setIsStartingAtLunch(false);
        $mealList->setIsEndingAtLunch(false);
        $mealList->setPersonName($this->getReference(UserFixtures::FARAH_USER_REFERENCE)->getUsername());

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::PST_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::VEGAN_CHIRASHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::GNOCCHI_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(4);
        $manager->persist($mealQuantity);

        $mealQuantity = new MealQuantityForList();
        $mealQuantity->setMealList($mealList);
        $mealQuantity->setMeal($this->getReference(RecipeFixtures::STEAK_AND_POTATOES_RECIPE_REFERENCE));
        $mealQuantity->setQuantity(2);
        $manager->persist($mealQuantity);
        $manager->persist($mealList);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RecipeFixtures::class,
        ];
    }
}
