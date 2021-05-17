<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210517193916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add shop batch size to ingredients';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ADD shop_batch_size DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ingredient DROP shop_batch_size');
    }
}
