<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210515212449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add picture filename to recipe';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE recipe ADD main_picture_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER INDEX idx_59ac8836a76ed395 RENAME TO IDX_2CB5551A76ED395');
        $this->addSql('ALTER INDEX idx_59ac8836385a88b7 RENAME TO IDX_2CB5551385A88B7');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX idx_2cb5551385a88b7 RENAME TO idx_59ac8836385a88b7');
        $this->addSql('ALTER INDEX idx_2cb5551a76ed395 RENAME TO idx_59ac8836a76ed395');
        $this->addSql('ALTER TABLE recipe DROP main_picture_filename');
    }
}
