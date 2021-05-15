<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210515113252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Make ingredient brand not required';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ALTER brand DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ingredient ALTER brand SET NOT NULL');
    }
}
