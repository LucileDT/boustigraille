<?php

namespace App\DataFixtures;

use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public const COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE = 'country-side-pan-fry-recipe';

    public function load(ObjectManager $manager): void
    {
        // -- RECIPES -- //
        $countrySidePanFry = new Recipe();
        $countrySidePanFry->setName('Poêlée campagnarde');
        $countrySidePanFry->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $manager->persist($countrySidePanFry);
        $manager->flush();

        // -- INGREDIENTQUANTITYFORRECIPE -- //
        $potatoQuantity = new IngredientQuantityForRecipe();
        $potatoQuantity->setIngredient($this->getReference(IngredientFixtures::POTATO_INGREDIENT_REFERENCE));
        $potatoQuantity->setQuantity('250');
        $potatoQuantity->setIsMeasuredByUnit(false);
        $potatoQuantity->setRecipe($countrySidePanFry);
        $manager->persist($potatoQuantity);

        $onionQuantity = new IngredientQuantityForRecipe();
        $onionQuantity->setIngredient($this->getReference(IngredientFixtures::ONION_INGREDIENT_REFERENCE));
        $onionQuantity->setQuantity('50');
        $onionQuantity->setIsMeasuredByUnit(false);
        $onionQuantity->setRecipe($countrySidePanFry);
        $manager->persist($onionQuantity);

        $pstQuantity = new IngredientQuantityForRecipe();
        $pstQuantity->setIngredient($this->getReference(IngredientFixtures::PROTEIN_INGREDIENT_REFERENCE));
        $pstQuantity->setQuantity('60');
        $pstQuantity->setIsMeasuredByUnit(false);
        $pstQuantity->setRecipe($countrySidePanFry);
        $manager->persist($pstQuantity);

        $leekQuantity = new IngredientQuantityForRecipe();
        $leekQuantity->setIngredient($this->getReference(IngredientFixtures::LEEK_INGREDIENT_REFERENCE));
        $leekQuantity->setQuantity('1');
        $leekQuantity->setIsMeasuredByUnit(true);
        $leekQuantity->setRecipe($countrySidePanFry);
        $manager->persist($leekQuantity);

        $carrotQuantity = new IngredientQuantityForRecipe();
        $carrotQuantity->setIngredient($this->getReference(IngredientFixtures::CARROT_INGREDIENT_REFERENCE));
        $carrotQuantity->setQuantity('2');
        $carrotQuantity->setIsMeasuredByUnit(true);
        $carrotQuantity->setRecipe($countrySidePanFry);
        $manager->persist($carrotQuantity);

        $oilQuantity = new IngredientQuantityForRecipe();
        $oilQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $oilQuantity->setQuantity('15');
        $oilQuantity->setIsMeasuredByUnit(false);
        $oilQuantity->setRecipe($countrySidePanFry);
        $manager->persist($oilQuantity);

        $countrySidePanFry->addTag($this->getReference(TagFixtures::LONG_TAG_REFERENCE));
        $countrySidePanFry->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $countrySidePanFry->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $manager->persist($countrySidePanFry);
        $manager->flush();

        $this->addReference(self::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE, $countrySidePanFry);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            IngredientFixtures::class,
            TagFixtures::class,
            DifficultyLevelFixtures::class,
        ];
    }
}
