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

    public function load(ObjectManager $manager): void
    {
        // -- INGREDIENTS -- //
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

        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::POTATO_INGREDIENT_REFERENCE, $potato);
        $this->addReference(self::ONION_INGREDIENT_REFERENCE, $onion);
        $this->addReference(self::PROTEIN_INGREDIENT_REFERENCE, $pst);
        $this->addReference(self::LEEK_INGREDIENT_REFERENCE, $leek);
        $this->addReference(self::CARROT_INGREDIENT_REFERENCE, $carrot);
        $this->addReference(self::OIL_INGREDIENT_REFERENCE, $oil);
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
        ];
    }
}
