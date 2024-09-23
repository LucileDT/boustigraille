<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const EASY_TAG_REFERENCE = 'easy-tag';
    public const HARD_TAG_REFERENCE = 'hard-tag';
    public const QUICK_TAG_REFERENCE = 'quick-tag';
    public const LONG_TAG_REFERENCE = 'long-tag';
    public const CARNIST_TAG_REFERENCE = 'carnist-tag';
    public const VEGETARIAN_TAG_REFERENCE = 'vegetarian-tag';
    public const VEGAN_TAG_REFERENCE = 'vegan-tag';

    public function load(ObjectManager $manager): void
    {
        // -- TAGS -- //
        $easy = new Tag();
        $easy->setLabel('Facile');
        $manager->persist($easy);

        $hard = new Tag();
        $hard->setLabel('Difficile');
        $manager->persist($hard);

        $quick = new Tag();
        $quick->setLabel('Rapide');
        $manager->persist($quick);

        $long = new Tag();
        $long->setLabel('Long');
        $manager->persist($long);

        $carnist = new Tag();
        $carnist->setLabel('Carniste');
        $manager->persist($carnist);

        $vegetarian = new Tag();
        $vegetarian->setLabel('Végé');
        $manager->persist($vegetarian);

        $vegan = new Tag();
        $vegan->setLabel('Vegan');
        $manager->persist($vegan);

        $manager->flush();

        $this->addReference(self::EASY_TAG_REFERENCE, $easy);
        $this->addReference(self::HARD_TAG_REFERENCE, $hard);
        $this->addReference(self::QUICK_TAG_REFERENCE, $quick);
        $this->addReference(self::LONG_TAG_REFERENCE, $long);
        $this->addReference(self::CARNIST_TAG_REFERENCE, $carnist);
        $this->addReference(self::VEGETARIAN_TAG_REFERENCE, $vegetarian);
        $this->addReference(self::VEGAN_TAG_REFERENCE, $vegan);
    }
}
