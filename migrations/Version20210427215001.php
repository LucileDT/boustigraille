<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427215001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Revove unicity constraint on ingredientQuantity > ingredient to allow multiple recipes to use the same ingredient.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('DROP INDEX uniq_3d18e32f933fe08c');
        $this->addSql('CREATE INDEX IDX_3D18E32F933FE08C ON ingredient_quantity_for_recipe (ingredient_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX IDX_3D18E32F933FE08C');
        $this->addSql('CREATE UNIQUE INDEX uniq_3d18e32f933fe08c ON ingredient_quantity_for_recipe (ingredient_id)');
    }
}
