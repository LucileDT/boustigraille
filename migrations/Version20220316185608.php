<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316185608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename ingredient column unity_size to unit_size';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient RENAME COLUMN unity_size TO unit_size');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient RENAME COLUMN unit_size TO unity_size');
    }
}
