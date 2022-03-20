<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220317205328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add author field on recipe';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe ADD author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP author_id');
    }
}
