<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture implements DependentFixtureInterface
{
    public const POTATO_INGREDIENT_REFERENCE = 'potato-ingredient';
    public const ONION_INGREDIENT_REFERENCE = 'onion-ingredient';
    public const PROTEIN_INGREDIENT_REFERENCE = 'pst-ingredient';
    public const LEEK_INGREDIENT_REFERENCE = 'leek-ingredient';
    public const CARROT_INGREDIENT_REFERENCE = 'carrot-ingredient';
    public const OIL_INGREDIENT_REFERENCE = 'oil-ingredient';
    public const SUSHI_RICE_INGREDIENT_REFERENCE = 'sushi-rice-ingredient';
    public const SOY_SAUCE_INGREDIENT_REFERENCE = 'soy-sauce-ingredient';
    public const SWEET_SOY_SAUCE_INGREDIENT_REFERENCE = 'sweet-sow-sauce-ingredient';

    public const GRATED_COMTE_INGREDIENT_REFERENCE = 'grated-comte-ingredient';
    public const SPREADABLE_CHEESE_INGREDIENT_REFERENCE = 'spreadable-cheese-ingredient';
    public const COW_MILK_INGREDIENT_REFERENCE = 'cow-milk-ingredient';
    public const EGG_INGREDIENT_REFERENCE = 'egg-ingredient';

    public const HAM_INGREDIENT_REFERENCE = 'ham-ingredient';
    public const LARDOON_INGREDIENT_REFERENCE = 'lardoon-ingredient';
    public const SALMOON_INGREDIENT_REFERENCE = 'salmoon-ingredient';
    public const BURGER_INGREDIENT_REFERENCE = 'burger-ingredient';

    public function load(ObjectManager $manager): void
    {
        // -- VEGAN INGREDIENTS -- //
        $potato = new Ingredient();
        $potato->setLabel('Pomme de terre');
        $potato->setMeasureType('g');
        $potato->setProteins(1.8);
        $potato->setCarbohydrates(16.7);
        $potato->setFat(0.3);
        $potato->setEnergy(80.5);
        $potato->setHasStockCheckNeededBeforeBuying(true);
        $potato->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $potato->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($potato);

        $onion = new Ingredient();
        $onion->setLabel('Oignon');
        $onion->setMeasureType('g');
        $onion->setUnitSize('100');
        $onion->setProteins(1.4);
        $onion->setCarbohydrates(9);
        $onion->setFat(0,2);
        $onion->setEnergy(43);
        $onion->setHasStockCheckNeededBeforeBuying(true);
        $onion->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $onion->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($onion);

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
        $pst->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $pst->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($pst);

        $leek = new Ingredient();
        $leek->setLabel('Poireau');
        $leek->setMeasureType('g');
        $leek->setUnitSize('150');
        $leek->setProteins(1.4);
        $leek->setCarbohydrates(4.9);
        $leek->setFat(0.2);
        $leek->setEnergy(27.4);
        $leek->setHasStockCheckNeededBeforeBuying(false);
        $leek->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $leek->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($leek);

        $carrot = new Ingredient();
        $carrot->setLabel('Carotte');
        $carrot->setMeasureType('g');
        $carrot->setUnitSize('125');
        $carrot->setProteins(0.6);
        $carrot->setCarbohydrates(7.6);
        $carrot->setFat(0.2);
        $carrot->setEnergy(40.2);
        $carrot->setHasStockCheckNeededBeforeBuying(false);
        $carrot->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $carrot->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($carrot);

        $oil = new Ingredient();
        $oil->setLabel('Huile d\'olive');
        $oil->setMeasureType('ml');
        $oil->setProteins(0);
        $oil->setCarbohydrates(0);
        $oil->setFat(92);
        $oil->setEnergy(828);
        $oil->setHasStockCheckNeededBeforeBuying(true);
        $oil->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $oil->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($oil);

        $sushiRice = new Ingredient();
        $sushiRice->setLabel('Riz à sushi');
        $sushiRice->setMeasureType('g');
        $sushiRice->setProteins(6.8);
        $sushiRice->setCarbohydrates(76);
        $sushiRice->setFat(1.3);
        $sushiRice->setEnergy(345);
        $sushiRice->setHasStockCheckNeededBeforeBuying(true);
        $sushiRice->setPortionSize(70);
        $sushiRice->setStore($this->getReference(StoreFixtures::ASIAN_GROCERY_STORE_REFERENCE));
        $sushiRice->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $sushiRice->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($sushiRice);

        $soySauce = new Ingredient();
        $soySauce->setLabel('Sauce soja salée');
        $soySauce->setMeasureType('ml');
        $soySauce->setProteins(10);
        $soySauce->setCarbohydrates(3.2);
        $soySauce->setFat(0);
        $soySauce->setEnergy(77);
        $soySauce->setShopBatchSize(500);
        $soySauce->setBarCode(8715035110502);
        $soySauce->setHasStockCheckNeededBeforeBuying(true);
        $soySauce->setStore($this->getReference(StoreFixtures::LECLERC_STORE_REFERENCE));
        $soySauce->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $soySauce->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($soySauce);

        $sweetSoySauce = new Ingredient();
        $sweetSoySauce->setLabel('Sauce soja salée');
        $sweetSoySauce->setMeasureType('ml');
        $sweetSoySauce->setProteins(10);
        $sweetSoySauce->setCarbohydrates(3.2);
        $sweetSoySauce->setFat(0);
        $sweetSoySauce->setEnergy(77);
        $sweetSoySauce->setShopBatchSize(975);
        $sweetSoySauce->setBarCode(8715035250802);
        $sweetSoySauce->setHasStockCheckNeededBeforeBuying(true);
        $sweetSoySauce->setStore($this->getReference(StoreFixtures::ASIAN_GROCERY_STORE_REFERENCE));
        $sweetSoySauce->addTag($this->getReference(TagFixtures::VEGAN_TAG_REFERENCE));
        $sweetSoySauce->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($sweetSoySauce);

        // -- VEGETARIAN INGREDIENTS -- //
        $gratedComte = new Ingredient();
        $gratedComte->setLabel('Comté râpé');
        $gratedComte->setMeasureType('g');
        $gratedComte->setProteins(26);
        $gratedComte->setCarbohydrates(0);
        $gratedComte->setFat(34);
        $gratedComte->setEnergy(410);
        $gratedComte->setHasStockCheckNeededBeforeBuying(false);
        $gratedComte->setBarCode(3123930771059);
        $gratedComte->setShopBatchSize(150);
        $gratedComte->setStore($this->getReference(StoreFixtures::LIDL_STORE_REFERENCE));
        $gratedComte->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($gratedComte);

        $spreadableCheese = new Ingredient();
        $spreadableCheese->setLabel('Fromage ail et fines herbes');
        $spreadableCheese->setMeasureType('g');
        $spreadableCheese->setProteins(6.8);
        $spreadableCheese->setCarbohydrates(3.5);
        $spreadableCheese->setFat(27.2);
        $spreadableCheese->setEnergy(288);
        $spreadableCheese->setHasStockCheckNeededBeforeBuying(false);
        $spreadableCheese->setBarCode(4056489301370);
        $spreadableCheese->setShopBatchSize(150);
        $spreadableCheese->setStore($this->getReference(StoreFixtures::LIDL_STORE_REFERENCE));
        $spreadableCheese->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($spreadableCheese);

        $cowMilk = new Ingredient();
        $cowMilk->setLabel('Lait de vache');
        $cowMilk->setMeasureType('ml');
        $cowMilk->setProteins(3.3);
        $cowMilk->setCarbohydrates(4.8);
        $cowMilk->setFat(1.6);
        $cowMilk->setEnergy(47);
        $cowMilk->setShopBatchSize(1000);
        $cowMilk->setHasStockCheckNeededBeforeBuying(true);
        $cowMilk->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($cowMilk);

        $egg = new Ingredient();
        $egg->setLabel('Œufs');
        $egg->setMeasureType('g');
        $egg->setProteins(12);
        $egg->setCarbohydrates(1);
        $egg->setFat(10);
        $egg->setEnergy(145);
        $egg->setUnitSize(40);
        $egg->setHasStockCheckNeededBeforeBuying(true);
        $egg->addTag($this->getReference(TagFixtures::VEGETARIAN_TAG_REFERENCE));
        $manager->persist($egg);

        // -- CARNIST INGREDIENTS -- //
        $ham = new Ingredient();
        $ham->setLabel('Jambon blanc avec couenne');
        $ham->setMeasureType('g');
        $ham->setProteins(20.2);
        $ham->setCarbohydrates(0.5);
        $ham->setFat(1.8);
        $ham->setEnergy(97);
        $ham->setUnitSize(40);
        $ham->setHasStockCheckNeededBeforeBuying(false);
        $ham->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $manager->persist($ham);

        $lardoon = new Ingredient();
        $lardoon->setLabel('Lardons fumés');
        $lardoon->setMeasureType('g');
        $lardoon->setProteins(18.6);
        $lardoon->setCarbohydrates(0.5);
        $lardoon->setFat(14.5);
        $lardoon->setEnergy(207);
        $lardoon->setHasStockCheckNeededBeforeBuying(false);
        $lardoon->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $manager->persist($lardoon);

        $salmoon = new Ingredient();
        $salmoon->setLabel('Saumon');
        $salmoon->setMeasureType('g');
        $salmoon->setProteins(20);
        $salmoon->setCarbohydrates(0.5);
        $salmoon->setFat(16);
        $salmoon->setEnergy(224);
        $salmoon->setHasStockCheckNeededBeforeBuying(false);
        $salmoon->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $manager->persist($salmoon);

        $burger = new Ingredient();
        $burger->setLabel('Steak haché surgelé');
        $burger->setMeasureType('g');
        $burger->setProteins(17);
        $burger->setCarbohydrates(0.5);
        $burger->setFat(20);
        $burger->setEnergy(248);
        $burger->setShopBatchSize(1000);
        $burger->setUnitSize(100);
        $burger->setHasStockCheckNeededBeforeBuying(false);
        $burger->addTag($this->getReference(TagFixtures::CARNIST_TAG_REFERENCE));
        $manager->persist($burger);

        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::POTATO_INGREDIENT_REFERENCE, $potato);
        $this->addReference(self::ONION_INGREDIENT_REFERENCE, $onion);
        $this->addReference(self::PROTEIN_INGREDIENT_REFERENCE, $pst);
        $this->addReference(self::LEEK_INGREDIENT_REFERENCE, $leek);
        $this->addReference(self::CARROT_INGREDIENT_REFERENCE, $carrot);
        $this->addReference(self::OIL_INGREDIENT_REFERENCE, $oil);
        $this->addReference(self::SUSHI_RICE_INGREDIENT_REFERENCE, $sushiRice);
        $this->addReference(self::SOY_SAUCE_INGREDIENT_REFERENCE, $soySauce);
        $this->addReference(self::SWEET_SOY_SAUCE_INGREDIENT_REFERENCE, $sweetSoySauce);

        $this->addReference(self::GRATED_COMTE_INGREDIENT_REFERENCE, $gratedComte);
        $this->addReference(self::SPREADABLE_CHEESE_INGREDIENT_REFERENCE, $spreadableCheese);
        $this->addReference(self::COW_MILK_INGREDIENT_REFERENCE, $cowMilk);
        $this->addReference(self::EGG_INGREDIENT_REFERENCE, $egg);

        $this->addReference(self::HAM_INGREDIENT_REFERENCE, $ham);
        $this->addReference(self::LARDOON_INGREDIENT_REFERENCE, $lardoon);
        $this->addReference(self::SALMOON_INGREDIENT_REFERENCE, $salmoon);
        $this->addReference(self::BURGER_INGREDIENT_REFERENCE, $burger);

    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
            StoreFixtures::class,
        ];
    }
}
