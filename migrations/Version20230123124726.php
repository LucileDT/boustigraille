<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230123124726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Define if there a need to check an ingredient before buying it';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient ADD has_stock_check_needed_before_buying BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient DROP has_stock_check_needed_before_buying');
    }
}
