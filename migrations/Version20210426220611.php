<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210426220611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Recipes and a way to store Ingredients quantities for a recipe.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SEQUENCE ingredient_quantity_for_recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ingredient_quantity_for_recipe (id INT NOT NULL, ingredient_id INT NOT NULL, recipe_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D18E32F933FE08C ON ingredient_quantity_for_recipe (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_3D18E32F59D8A214 ON ingredient_quantity_for_recipe (recipe_id)');
        $this->addSql('CREATE TABLE recipe (id INT NOT NULL, recipe VARCHAR(5000) DEFAULT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE ingredient_quantity_for_recipe ADD CONSTRAINT FK_3D18E32F933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient_quantity_for_recipe ADD CONSTRAINT FK_3D18E32F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient_quantity_for_recipe DROP CONSTRAINT FK_3D18E32F59D8A214');
        $this->addSql('DROP SEQUENCE ingredient_quantity_for_recipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_id_seq CASCADE');
        $this->addSql('DROP TABLE ingredient_quantity_for_recipe');
        $this->addSql('DROP TABLE recipe');
    }
}
