<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221112154627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add function to transliterate strings to make special characters match Latin ones';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION unaccent');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP EXTENSION unaccent');
    }
}
