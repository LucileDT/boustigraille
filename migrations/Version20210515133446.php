<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210515133446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add comment field to ingredients';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ADD comment VARCHAR(1000) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ingredient DROP comment');
    }
}
