<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210512094324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add bar code field to ingredient entity';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ADD bar_code VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ingredient DROP bar_code');
    }
}
