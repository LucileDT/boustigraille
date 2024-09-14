<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240912162426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add recipe durations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe ADD preparation_duration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD cooking_duration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD rest_duration VARCHAR(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN recipe.preparation_duration IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN recipe.cooking_duration IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN recipe.rest_duration IS \'(DC2Type:dateinterval)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP preparation_duration');
        $this->addSql('ALTER TABLE recipe DROP cooking_duration');
        $this->addSql('ALTER TABLE recipe DROP rest_duration');
    }
}
