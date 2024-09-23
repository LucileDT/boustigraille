<?php

namespace App\DataFixtures;

use App\Entity\DifficultyLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DifficultyLevelFixtures extends Fixture
{
    public const EASY_DIFFICULTY_LEVEL_REFERENCE = 'easy-difficulty-level';
    public const MEDIUM_DIFFICULTY_LEVEL_REFERENCE = 'medium-difficulty-level';
    public const HARD_DIFFICULTY_LEVEL_REFERENCE = 'hard-difficulty-level';

    public function load(ObjectManager $manager): void
    {
        $easy = new DifficultyLevel();
        $easy->setLabel('Facile');
        $easy->setDescription('Repas réalisable par tout le monde et qui ne requiert aucun outil particulier.');
        $manager->persist($easy);

        $medium = new DifficultyLevel();
        $medium->setLabel('Intermédiaire');
        $medium->setDescription('Repas réalisable avec quelques difficultés et/ou qui demande des outils non conventionnels.');
        $manager->persist($medium);

        $hard = new DifficultyLevel();
        $hard->setLabel('Difficile');
        $hard->setDescription('Repas chiant à réaliser et/ou qui requiert des outils spécifiques.');
        $manager->persist($hard);

        $manager->flush();

        // allows other fixtures to access those objects
        $this->addReference(self::EASY_DIFFICULTY_LEVEL_REFERENCE, $easy);
        $this->addReference(self::MEDIUM_DIFFICULTY_LEVEL_REFERENCE, $medium);
        $this->addReference(self::HARD_DIFFICULTY_LEVEL_REFERENCE, $hard);
    }
}
