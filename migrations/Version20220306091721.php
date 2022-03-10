<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306091721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create meal_list tables and relations.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE meal_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE meal_quantity_for_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE meal_list (id INT NOT NULL, person_name VARCHAR(255) NOT NULL, comment TEXT DEFAULT NULL, start_date DATE NOT NULL, is_starting_at_lunch BOOLEAN DEFAULT false NOT NULL, end_date DATE NOT NULL, is_ending_at_lunch BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE meal_quantity_for_list (id INT NOT NULL, meal_list_id INT NOT NULL, meal_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B44DF4E441DA492 ON meal_quantity_for_list (meal_list_id)');
        $this->addSql('CREATE INDEX IDX_7B44DF4E639666D6 ON meal_quantity_for_list (meal_id)');
        $this->addSql('ALTER TABLE meal_quantity_for_list ADD CONSTRAINT FK_7B44DF4E441DA492 FOREIGN KEY (meal_list_id) REFERENCES meal_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meal_quantity_for_list ADD CONSTRAINT FK_7B44DF4E639666D6 FOREIGN KEY (meal_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_quantity_for_list DROP CONSTRAINT FK_7B44DF4E441DA492');
        $this->addSql('DROP SEQUENCE meal_list_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE meal_quantity_for_list_id_seq CASCADE');
        $this->addSql('DROP TABLE meal_list');
        $this->addSql('DROP TABLE meal_quantity_for_list');
    }
}
