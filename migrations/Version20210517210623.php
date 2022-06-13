<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210517210623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add unity size in ingredients';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ADD unity_size DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient DROP unity_size');
    }
}
