<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519200024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add measure type to ingredientQuantityForRecipe';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient_quantity_for_recipe ADD is_measured_by_unit BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient_quantity_for_recipe DROP is_measured_by_unit');
    }
}
