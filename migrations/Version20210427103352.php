<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427103352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add forgotten name and process fields to Recipe';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE recipe ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD process TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe DROP recipe');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE recipe ADD recipe VARCHAR(5000) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe DROP name');
        $this->addSql('ALTER TABLE recipe DROP process');
    }
}
