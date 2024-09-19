<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240915122121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a difficulty level to recipes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE difficulty_level_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE difficulty_level (id INT NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(511) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE recipe ADD difficulty_level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13764890943 FOREIGN KEY (difficulty_level_id) REFERENCES difficulty_level (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DA88B13764890943 ON recipe (difficulty_level_id)');
        $this->addSql('INSERT INTO difficulty_level VALUES (nextval(\'tag_id_seq\'), \'Facile\', \'Repas réalisable par tout le monde et qui ne requiert aucun outil particulier.\')');
        $this->addSql('INSERT INTO difficulty_level VALUES (nextval(\'tag_id_seq\'), \'Intermédiaire\', \'Repas réalisable avec quelques difficultés et/ou qui demande des outils non conventionnels.\')');
        $this->addSql('INSERT INTO difficulty_level VALUES (nextval(\'tag_id_seq\'), \'Difficile\', \'Repas chiant à réaliser et/ou qui requiert des outils spécifiques.\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13764890943');
        $this->addSql('DROP SEQUENCE difficulty_level_id_seq CASCADE');
        $this->addSql('DROP TABLE difficulty_level');
        $this->addSql('DROP INDEX IDX_DA88B13764890943');
        $this->addSql('ALTER TABLE recipe DROP difficulty_level_id');
    }
}
