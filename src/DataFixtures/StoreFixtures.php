<?php

namespace App\DataFixtures;

use App\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StoreFixtures extends Fixture
{
    public const LIDL_STORE_REFERENCE = 'lidl-store';
    public const CARREFOUR_STORE_REFERENCE = 'carrefour-store';
    public const CASINO_STORE_REFERENCE = 'casino-store';
    public const MONOPRIX_STORE_REFERENCE = 'monoprix-store';
    public const ALDI_STORE_REFERENCE = 'aldi-store';
    public const ASIAN_GROCERY_STORE_REFERENCE = 'asian-grocery-store';
    public const PICARD_STORE_REFERENCE = 'picard-store';
    public const THIRIET_STORE_REFERENCE = 'thiriet-store';
    public const AUCHAN_STORE_REFERENCE = 'auchan-store';
    public const LEADER_PRICE_STORE_REFERENCE = 'leader-price-store';
    public const VALRHONA_STORE_REFERENCE = 'valrhona-store';
    public const LECLERC_STORE_REFERENCE = 'leclerc-store';

    public function load(ObjectManager $manager): void
    {
        $lidl = new Store();
        $lidl->setLabel('Lidl');
        $lidl->setSortNumber(1);
        $manager->persist($lidl);

        $carrefour = new Store();
        $carrefour->setLabel('Carrefour');
        $carrefour->setSortNumber(2);
        $manager->persist($carrefour);

        $casino = new Store();
        $casino->setLabel('Casino');
        $casino->setSortNumber(3);
        $manager->persist($casino);

        $monoprix = new Store();
        $monoprix->setLabel('Monoprix');
        $monoprix->setSortNumber(4);
        $manager->persist($monoprix);

        $aldi = new Store();
        $aldi->setLabel('Aldi');
        $aldi->setSortNumber(5);
        $manager->persist($aldi);

        $asianGrocery = new Store();
        $asianGrocery->setLabel('Épicerie asiatique');
        $asianGrocery->setSortNumber(6);
        $manager->persist($asianGrocery);

        $picard = new Store();
        $picard->setLabel('Picard');
        $picard->setSortNumber(7);
        $manager->persist($picard);

        $thiriet = new Store();
        $thiriet->setLabel('Thiriet');
        $thiriet->setSortNumber(8);
        $manager->persist($thiriet);

        $auchan = new Store();
        $auchan->setLabel('Auchan');
        $auchan->setSortNumber(9);
        $manager->persist($auchan);

        $leaderPrice = new Store();
        $leaderPrice->setLabel('Leader Price');
        $leaderPrice->setSortNumber(10);
        $manager->persist($leaderPrice);

        $valrhona = new Store();
        $valrhona->setLabel('Valrhona');
        $valrhona->setSortNumber(11);
        $manager->persist($valrhona);

        $leclerc = new Store();
        $leclerc->setLabel('Leclerc');
        $leclerc->setSortNumber(12);
        $manager->persist($leclerc);

        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::LIDL_STORE_REFERENCE, $lidl);
        $this->addReference(self::CARREFOUR_STORE_REFERENCE, $carrefour);
        $this->addReference(self::CASINO_STORE_REFERENCE, $casino);
        $this->addReference(self::MONOPRIX_STORE_REFERENCE, $monoprix);
        $this->addReference(self::ALDI_STORE_REFERENCE, $aldi);
        $this->addReference(self::ASIAN_GROCERY_STORE_REFERENCE, $asianGrocery);
        $this->addReference(self::PICARD_STORE_REFERENCE, $picard);
        $this->addReference(self::THIRIET_STORE_REFERENCE, $thiriet);
        $this->addReference(self::AUCHAN_STORE_REFERENCE, $auchan);
        $this->addReference(self::LEADER_PRICE_STORE_REFERENCE, $leaderPrice);
        $this->addReference(self::VALRHONA_STORE_REFERENCE, $valrhona);
        $this->addReference(self::LECLERC_STORE_REFERENCE, $leclerc);
    }
}
