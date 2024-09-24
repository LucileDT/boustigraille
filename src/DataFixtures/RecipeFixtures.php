<?php

namespace App\DataFixtures;

use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Service\RecipeService;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public const COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE = 'country-side-pan-fry-recipe';
    public const CHEESE_OMELET_RECIPE_REFERENCE = 'cheese-omelet-recipe';
    public const SASHIMI_RECIPE_REFERENCE = 'sashimi-recipe';
    public const VEGAN_FRIED_RICE_RECIPE_REFERENCE = 'vegan-fried-rice-recipe';
    public const POTATOES_AND_CHEESE_RECIPE_REFERENCE = 'potatoes-and-cheese-recipe';
    public const STEAK_AND_POTATOES_RECIPE_REFERENCE = 'steak-and-potatoes-recipe';
    public const HAM_ROSTIES_RECIPE_REFERENCE = 'ham-rosties-recipe';
    public const POLENTA_RECIPE_REFERENCE = 'polenta-recipe';
    public const VEGAN_CHIRASHI_RECIPE_REFERENCE = 'vegan-chirashi-recipe';
    public const PST_RECIPE_REFERENCE = 'pst-recipe';
    public const VEGETARIAN_CHIRASHI_RECIPE_REFERENCE = 'vegetarian-chirashi-recipe';
    public const GNOCCHI_RECIPE_REFERENCE = 'gnocchi-recipe';

    public function load(ObjectManager $manager): void
    {
        // ------------------------------- //
        // -- RECIPE 1: PAN FRY (vegan) -- //
        // ------------------------------- //
        $countrySidePanFry = new Recipe();
        $countrySidePanFry->setName('Poêlée campagnarde');
        $countrySidePanFry->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $countrySidePanFry->addTag($this->getReference(TagFixtures::LONG_TAG_REFERENCE));
        $countrySidePanFry->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $countrySidePanFry->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $countrySidePanFry->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::MEDIUM_DIFFICULTY_LEVEL_REFERENCE));
        $manager->persist($countrySidePanFry);

        $potatoQuantity = new IngredientQuantityForRecipe();
        $potatoQuantity->setIngredient($this->getReference(IngredientFixtures::POTATO_INGREDIENT_REFERENCE));
        $potatoQuantity->setQuantity(250);
        $potatoQuantity->setIsMeasuredByUnit(false);
        $potatoQuantity->setRecipe($countrySidePanFry);
        $manager->persist($potatoQuantity);

        $onionQuantity = new IngredientQuantityForRecipe();
        $onionQuantity->setIngredient($this->getReference(IngredientFixtures::ONION_INGREDIENT_REFERENCE));
        $onionQuantity->setQuantity(50);
        $onionQuantity->setIsMeasuredByUnit(false);
        $onionQuantity->setRecipe($countrySidePanFry);
        $manager->persist($onionQuantity);

        $pstQuantity = new IngredientQuantityForRecipe();
        $pstQuantity->setIngredient($this->getReference(IngredientFixtures::PROTEIN_INGREDIENT_REFERENCE));
        $pstQuantity->setQuantity(60);
        $pstQuantity->setIsMeasuredByUnit(false);
        $pstQuantity->setRecipe($countrySidePanFry);
        $manager->persist($pstQuantity);

        $leekQuantity = new IngredientQuantityForRecipe();
        $leekQuantity->setIngredient($this->getReference(IngredientFixtures::LEEK_INGREDIENT_REFERENCE));
        $leekQuantity->setQuantity(1);
        $leekQuantity->setIsMeasuredByUnit(true);
        $leekQuantity->setRecipe($countrySidePanFry);
        $manager->persist($leekQuantity);

        $carrotQuantity = new IngredientQuantityForRecipe();
        $carrotQuantity->setIngredient($this->getReference(IngredientFixtures::CARROT_INGREDIENT_REFERENCE));
        $carrotQuantity->setQuantity(2);
        $carrotQuantity->setIsMeasuredByUnit(true);
        $carrotQuantity->setRecipe($countrySidePanFry);
        $manager->persist($carrotQuantity);

        $oilQuantity = new IngredientQuantityForRecipe();
        $oilQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $oilQuantity->setQuantity(15);
        $oilQuantity->setIsMeasuredByUnit(false);
        $oilQuantity->setRecipe($countrySidePanFry);
        $manager->persist($oilQuantity);

        // ------------------------------------------ //
        // -- RECIPE 2: CHEESE OMELET (vegetarian) -- //
        // ------------------------------------------ //
        $cheeseOmelet = new Recipe();
        $cheeseOmelet->setName('Omelette du fromage');
        $cheeseOmelet->setAuthor($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $cheeseOmelet->addTag($this->getReference(TagFixtures::QUICK_TAG_REFERENCE));
        $cheeseOmelet->addTag($this->getReference(TagFixtures::EASY_TAG_REFERENCE));
        $cheeseOmelet->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $cheeseOmelet->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::EASY_DIFFICULTY_LEVEL_REFERENCE));
        $this->getImageForRecipe(
            'https://images.pexels.com/photos/1277939/pexels-photo-1277939.jpeg?auto=compress&cs=tinysrgb&w=400&dpr=1',
            'omelet.jpeg'
        );
        $cheeseOmelet->setMainPictureFilename('omelet.jpeg');
        $manager->persist($cheeseOmelet);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($cheeseOmelet);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::EGG_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(2);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($cheeseOmelet);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::COW_MILK_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(20);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($cheeseOmelet);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::GRATED_COMTE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(60);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($cheeseOmelet);
        $manager->persist($ingredientQuantity);

        // --------------------------------- //
        // -- RECIPE 3: SASHIMI (carnist) -- //
        // --------------------------------- //
        $sashimi = new Recipe();
        $sashimi->setName('Sashimi');
        $sashimi->setAuthor($this->getReference(UserFixtures::FARAH_USER_REFERENCE));
        $sashimi->addTag($this->getReference(TagFixtures::HARD_TAG_REFERENCE));
        $sashimi->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $sashimi->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::HARD_DIFFICULTY_LEVEL_REFERENCE));
        $this->getImageForRecipe(
            'https://images.pexels.com/photos/28559517/pexels-photo-28559517/free-photo-of-gros-plan-sur-un-assortiment-de-sushis-et-de-sashimis-frais.jpeg?auto=compress&cs=tinysrgb&w=400&dpr=1',
            'sashimi.jpeg'
        );
        $sashimi->setMainPictureFilename('sashimi.jpeg');
        $manager->persist($sashimi);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SALMOON_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(300);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($sashimi);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SWEET_SOY_SAUCE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(20);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($sashimi);
        $manager->persist($ingredientQuantity);


        // ---------------------------------------- //
        // -- RECIPE 4: VEGAN FRIED RICE (vegan) -- //
        // ---------------------------------------- //
        $veganFriedRice = new Recipe();
        $veganFriedRice->setName('Fried rice végan');
        $veganFriedRice->setAuthor($this->getReference(UserFixtures::ISHANA_USER_REFERENCE));
        $veganFriedRice->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $veganFriedRice->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $veganFriedRice->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::MEDIUM_DIFFICULTY_LEVEL_REFERENCE));
        $this->getImageForRecipe(
            'https://images.pexels.com/photos/343871/pexels-photo-343871.jpeg?auto=compress&cs=tinysrgb&w=400&dpr=1',
            'fried-rice.jpeg'
        );
        $veganFriedRice->setMainPictureFilename('fried-rice.jpeg');
        $manager->persist($veganFriedRice);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SOY_SAUCE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(20);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($veganFriedRice);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SUSHI_RICE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(100);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($veganFriedRice);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($veganFriedRice);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::CARROT_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(1);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($veganFriedRice);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::ONION_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(1);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($veganFriedRice);
        $manager->persist($ingredientQuantity);


        // ------------------------------------------------ //
        // -- RECIPE 5: POTATOES AND CHEESE (vegetarian) -- //
        // ------------------------------------------------ //
        $potatoesAndCheese = new Recipe();
        $potatoesAndCheese->setName('Patates au four au fromage');
        $potatoesAndCheese->setAuthor($this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE));
        $potatoesAndCheese->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $potatoesAndCheese->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::MEDIUM_DIFFICULTY_LEVEL_REFERENCE));
        $this->getImageForRecipe(
            'https://images.pexels.com/photos/162763/delicious-garnish-potatoes-fried-162763.jpeg?auto=compress&cs=tinysrgb&w=400&dpr=1',
            'potatoes.jpeg'
        );
        $potatoesAndCheese->setMainPictureFilename('potatoes.jpeg');
        $manager->persist($potatoesAndCheese);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::POTATO_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(4);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($potatoesAndCheese);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($potatoesAndCheese);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SPREADABLE_CHEESE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(100);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($potatoesAndCheese);
        $manager->persist($ingredientQuantity);


        // -------------------------------------------- //
        // -- RECIPE 6: STEAK AND POTATOES (carnist) -- //
        // -------------------------------------------- //
        $potatoesAndSteak = new Recipe();
        $potatoesAndSteak->setName('Patates sautées et steak haché');
        $potatoesAndSteak->setAuthor($this->getReference(UserFixtures::FARAH_USER_REFERENCE));
        $potatoesAndSteak->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $potatoesAndSteak->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::MEDIUM_DIFFICULTY_LEVEL_REFERENCE));
        $manager->persist($potatoesAndSteak);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($potatoesAndSteak);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::BURGER_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(2);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($potatoesAndSteak);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::POTATO_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(2);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($potatoesAndSteak);
        $manager->persist($ingredientQuantity);

        // ------------------------------------- //
        // -- RECIPE 7: HAM ROSTIES (carnist) -- //
        // ------------------------------------- //
        $hamRosties = new Recipe();
        $hamRosties->setName('Croque-rösti');
        $hamRosties->setAuthor($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $hamRosties->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $hamRosties->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::HARD_DIFFICULTY_LEVEL_REFERENCE));
        $hamRosties->setComment('Collectivisé depuis https://www.madrange.fr/nos-recettes/croque-rosti-au-jambon-blanc-et-au-saint-marcellin');
        $manager->persist($hamRosties);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::POTATO_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(2);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($hamRosties);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::ONION_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(0.5);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($hamRosties);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(20);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($hamRosties);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::HAM_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(1);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($hamRosties);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::GRATED_COMTE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(80);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($hamRosties);
        $manager->persist($ingredientQuantity);

        // --------------------------------- //
        // -- RECIPE 8: POLENTA (carnist) -- //
        // --------------------------------- //
        $polenta = new Recipe();
        $polenta->setName('Gratin de polenta aux lardoons');
        $polenta->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $polenta->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $polenta->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::EASY_DIFFICULTY_LEVEL_REFERENCE));
        $polenta->setProcess('
1. Faire précuire la polenta selon les instructions indiquées sur la boîte.
2. Faire revenir les lardons et oignons émincés.
3. Les incorporer à la polenta cuite.
4. Ajouter la crème et le parmesan puis mélanger. Saler et poivrer.
5. Verser le mélange dans un plat à gratin et saupoudrer de fromage râpé.
6. Mettre au four en mode grill pour gratiner.
        ');
        $manager->persist($polenta);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::POLENTA_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(70);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($polenta);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::LARDOON_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(50);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($polenta);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::ONION_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(1);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($polenta);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::CREAM_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(50);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($polenta);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::GRATED_COMTE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(50);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($polenta);
        $manager->persist($ingredientQuantity);

        // -------------------------------- //
        // -- RECIPE 9: CHIRASHI (vegan) -- //
        // -------------------------------- //
        $chirashiVegan = new Recipe();
        $chirashiVegan->setName('Chirashi carottes');
        $chirashiVegan->setAuthor($this->getReference(UserFixtures::ISHANA_USER_REFERENCE));
        $chirashiVegan->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $chirashiVegan->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $chirashiVegan->addTag($this->getReference(TagFixtures::LONG_TAG_REFERENCE));
        $chirashiVegan->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::HARD_DIFFICULTY_LEVEL_REFERENCE));
        $chirashiVegan->setRestDuration(new DateInterval('PT1H30M'));
        $manager->persist($chirashiVegan);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SUSHI_RICE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(100);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegan);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::CARROT_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(3);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($chirashiVegan);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegan);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SWEET_SOY_SAUCE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(30);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegan);
        $manager->persist($ingredientQuantity);

        // ---------------------------------------- //
        // -- RECIPE 10: CARAMELIZED PST (vegan) -- //
        // ---------------------------------------- //
        $caramelizedPST = new Recipe();
        $caramelizedPST->setName('Protéines de soja texturées caramélisées');
        $caramelizedPST->setAuthor($this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE));
        $caramelizedPST->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $caramelizedPST->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $caramelizedPST->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::MEDIUM_DIFFICULTY_LEVEL_REFERENCE));
        $caramelizedPST->setPreparationDuration(new DateInterval('PT10M'));
        $caramelizedPST->setCookingDuration(new DateInterval('PT15M'));
        $caramelizedPST->setRestDuration(new DateInterval('PT10M'));
        $caramelizedPST->setProcess('
- Lancer la cuisson du riz.
- Faire bouillir de l\'eau puis couper le feu.
- Verser les PST dans l\'eau chaude et les laisser gonfler une dizaine de minutes.
- Faire chauffer la poêle et y verser les PST, l\'huile, la sauce soja et le sirop d\'érable. Ajouter également 200ml d\'eau.
- Laisser réduire à feu moyen en remuant.
- Lorsque les PST sont liées et bien colorées, servir avec un bol de riz.

*Pour la caramélisation, si vous n’avez pas de sirop d’érable, vous pouvez utiliser du sirop d’agave ou du sucre.*
        ');
        $caramelizedPST->setComment('Collectivisé depuis https://deliacious.com/2022/02/proteines-soja-texturees-caramel.html');
        $manager->persist($caramelizedPST);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SOY_SAUCE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(45);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($caramelizedPST);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::PROTEIN_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(120);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($caramelizedPST);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(30);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($caramelizedPST);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::MAPLE_SYRUP_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(45);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($caramelizedPST);
        $manager->persist($ingredientQuantity);

        // -------------------------------------- //
        // -- RECIPE 11: CHIRASHI (vegetarian) -- //
        // -------------------------------------- //
        $chirashiVegetarian = new Recipe();
        $chirashiVegetarian->setName('Chirashi légumes et fromage');
        $chirashiVegetarian->setAuthor($this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE));
        $chirashiVegetarian->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $chirashiVegetarian->addTag($this->getReference(TagFixtures::LONG_TAG_REFERENCE));
        $chirashiVegetarian->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::HARD_DIFFICULTY_LEVEL_REFERENCE));
        $chirashiVegetarian->setRestDuration(new DateInterval('PT1H30M'));
        $manager->persist($chirashiVegetarian);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SUSHI_RICE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(100);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegetarian);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::CARROT_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(1);
        $ingredientQuantity->setIsMeasuredByUnit(true);
        $ingredientQuantity->setRecipe($chirashiVegetarian);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SPREADABLE_CHEESE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(50);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegetarian);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegetarian);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SWEET_SOY_SAUCE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(30);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($chirashiVegetarian);
        $manager->persist($ingredientQuantity);

        // ------------------------------------------------------ //
        // -- RECIPE 12: GREEN VEGETABLES GNOCCHI (vegetarian) -- //
        // ------------------------------------------------------ //
        $greenVegetableGnocchi = new Recipe();
        $greenVegetableGnocchi->setName('Gnocchis aux légumes verts');
        $greenVegetableGnocchi->setAuthor($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $greenVegetableGnocchi->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $greenVegetableGnocchi->addTag($this->getReference(TagFixtures::QUICK_TAG_REFERENCE));
        $greenVegetableGnocchi->addTag($this->getReference(TagFixtures::EASY_TAG_REFERENCE));
        $greenVegetableGnocchi->setDifficultyLevel($this->getReference(DifficultyLevelFixtures::EASY_DIFFICULTY_LEVEL_REFERENCE));
        $greenVegetableGnocchi->setPreparationDuration(new DateInterval('PT10M'));
        $greenVegetableGnocchi->setCookingDuration(new DateInterval('PT10M'));
        $greenVegetableGnocchi->setProcess('
1. Faire cuire les gnocchis comme indiqué sur le paquet.
2. Pendant ce temps, faire réchauffer les épinards au micro-ondes.
3. Une fois les gnocchis et épinards prêts, les faire dorer avec l\'huile d\'olive.
4. Assaisonner et servir.
');
        $this->getImageForRecipe(
            'https://images.pexels.com/photos/788766/pexels-photo-788766.jpeg?auto=compress&cs=tinysrgb&w=400&dpr=1',
            'gnocchis.jpeg'
        );
        $greenVegetableGnocchi->setMainPictureFilename('gnocchis.jpeg');
        $manager->persist($greenVegetableGnocchi);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::GNOCCHI_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(160);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($greenVegetableGnocchi);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::SPINACH_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(200);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($greenVegetableGnocchi);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::OIL_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(15);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($greenVegetableGnocchi);
        $manager->persist($ingredientQuantity);

        $ingredientQuantity = new IngredientQuantityForRecipe();
        $ingredientQuantity->setIngredient($this->getReference(IngredientFixtures::GRATED_COMTE_INGREDIENT_REFERENCE));
        $ingredientQuantity->setQuantity(50);
        $ingredientQuantity->setIsMeasuredByUnit(false);
        $ingredientQuantity->setRecipe($greenVegetableGnocchi);
        $manager->persist($ingredientQuantity);

        $manager->flush();

        $this->addReference(self::COUNTRY_SIDE_PAN_FRY_RECIPE_REFERENCE, $countrySidePanFry);
        $this->addReference(self::CHEESE_OMELET_RECIPE_REFERENCE, $cheeseOmelet);
        $this->addReference(self::SASHIMI_RECIPE_REFERENCE, $sashimi);
        $this->addReference(self::VEGAN_FRIED_RICE_RECIPE_REFERENCE, $veganFriedRice);
        $this->addReference(self::POTATOES_AND_CHEESE_RECIPE_REFERENCE, $potatoesAndCheese);
        $this->addReference(self::STEAK_AND_POTATOES_RECIPE_REFERENCE, $potatoesAndSteak);
        $this->addReference(self::HAM_ROSTIES_RECIPE_REFERENCE, $hamRosties);
        $this->addReference(self::POLENTA_RECIPE_REFERENCE, $polenta);
        $this->addReference(self::VEGAN_CHIRASHI_RECIPE_REFERENCE, $chirashiVegan);
        $this->addReference(self::PST_RECIPE_REFERENCE, $caramelizedPST);
        $this->addReference(self::VEGETARIAN_CHIRASHI_RECIPE_REFERENCE, $chirashiVegetarian);
        $this->addReference(self::GNOCCHI_RECIPE_REFERENCE, $greenVegetableGnocchi);
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

    private function getImageForRecipe(string $pictureUrl, string $filename): void
    {
        $fullFilePath = getcwd() . '/public/uploads/pictures/' . $filename;
        // create new cURL session
        $ch = curl_init();

        // cURL configuration
        curl_setopt($ch, CURLOPT_URL, $pictureUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // to avoid printing of the response

        // cURL call to get product data
        $rawFile = curl_exec($ch);

        // close cURL session
        curl_close($ch);

        // save file
        if (file_exists($fullFilePath)){
            unlink($fullFilePath);
        }
        $fp = fopen($fullFilePath,'x');
        fwrite($fp, $rawFile);
        fclose($fp);

        // resize file
        RecipeService::resizeImage($fullFilePath);
    }
}
