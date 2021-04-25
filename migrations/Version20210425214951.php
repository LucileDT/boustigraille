<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425214951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Ingredients to the database.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, label VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, shop VARCHAR(255) DEFAULT NULL, portion_size INT DEFAULT NULL, measure_type VARCHAR(255) NOT NULL, proteins INT NOT NULL, carbohydrates INT NOT NULL, fat INT NOT NULL, energy INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP INDEX uniq_8d93d649f85e0677');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649f85e0677 ON "user" (username)');
    }
}
